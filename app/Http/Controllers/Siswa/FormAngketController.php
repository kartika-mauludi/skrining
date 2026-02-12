<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Models\Angket;
use App\Models\AngketSoal;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Jawaban;
use Carbon\Carbon;
use App\Models\HasilScore;
use DB;
use App\Helpers\HitungSkor;



class FormAngketController extends Controller
{
    public function index(Request $request){
        // $token = 'kJ1P5C';
        $data = [
            'kelas' => null,
            'siswas' => collect(),
            'angketsoals' => collect(),
            'angket' => null,
        ];
        $oneWeekAgo = Carbon::now()->subWeek();
        $siswaSudahIsi = Jawaban::where('created_at', '>=', $oneWeekAgo)
        ->pluck('siswa_id')
        ->unique();
        if($request->filled('token')){
            $kelas = Kelas::with('sekolah')
            ->whereJsonContains('data_akses', [
                'token' => $request->token
            ])
            ->get();
            if ($kelas->isEmpty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Token tidak valid');
            }
            $sekolah_id = $kelas->first()->sekolah_id;
            $kelas_id = $kelas->first()->id;
            
            $angket = Angket::where(function($query) use($sekolah_id, $kelas_id) {
                // Cari yang sesuai sekolah_id dan kelas_id
                $query->where('sekolah_id', $sekolah_id)
                    ->where('kelas_id', $kelas_id);
            })
            ->orWhere(function($query) {
                // Atau cari yang NULL
                $query->whereNull('sekolah_id')
                    ->whereNull('kelas_id');
            })
            ->orderByRaw('CASE WHEN sekolah_id = ? AND kelas_id = ? THEN 0 ELSE 1 END', 
                [$sekolah_id, $kelas_id])
            ->latest()
            ->first();

            if (!$angket) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Form angket tidak ditemukan');
            }

            $data['siswas'] = Siswa::select([ 'id','kelas_id','no_absen','nis','nama_lengkap', 'jk'])
                ->whereIn('kelas_id', $kelas->pluck('id'))
                ->orderBy('no_absen')->get();
            $data['kelas'] = $kelas;
            $data['angketsoals'] = AngketSoal::with('angket')->get();
            $data['angket'] = $angket;
            $data['angket'] = Angket::latest()->first();
            $data['siswaSudahIsi'] = $siswaSudahIsi;
            $data['token'] = $request->token;
        }
        return view('siswa.formAngket', $data);
    }

    public function store(Request $request){
        // return $request;
        $startWeek = Carbon::now()->startOfWeek(); // Senin
        $endWeek   = Carbon::now()->endOfWeek();   // Minggu

        $cek = jawaban::where('siswa_id',$request->siswa_id)->whereBetween('created_at', [$startWeek, $endWeek])->exists();
        if ($cek) {
                return redirect()->back()->with('error', 
                    'Siswa sudah mengisi angket, silahkan isi minggu depan lagi'
                );
            }
        $pelakuIds = [];
        $kelas = Kelas::select('id', 'nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$request->token)->first();
        foreach ($request->jawaban as $soal_id => $jawaban) {
            if (is_array($jawaban)) {
                foreach ($jawaban as $index => $value) {
                    // return $value;
                   if ($value === 'tidak_ada' || $value == 0) {
                        $skor = 0;
                        $siswaPelaku = null;
                    } else {
                        $skor = 3;
                          $siswaPelaku = Siswa::where('no_absen', $value)
                        ->where('kelas_id', $kelas->id)
                        ->first();
                    }
                        // return $siswaPelaku;
                        Jawaban::create([
                            'siswa_id' => $request->siswa_id,
                            'soal_id'  => $soal_id,
                            'jawaban'  => $skor,
                            'alasan'   => $request->alasan[$soal_id][$index] ?? '-',
                             'id_siswa_pelaku' => $siswaPelaku?->id
                            ]);

                    if ($siswaPelaku) {
                        $pelakuIds[] = $siswaPelaku->id;
                     }
                }
            } 
            // kalau jawaban single (radio / pilihan)
            else {
                    Jawaban::create([
                        'siswa_id' => $request->siswa_id,
                        'soal_id'  => $soal_id,
                        'jawaban'  => $jawaban,
                        'alasan'   => $request->alasan[$soal_id] ?? '-',
                    ]);
            }
        }

        $soal = AngketSoal::select('guru_id')
        ->find(array_key_first($request->jawaban));

      HitungSkor::createSkor(
    $request->siswa_id,
    $request->angket,
    $kelas->id,
    $soal->guru_id ?? null
        );

        if($pelakuIds){
            foreach (array_unique($pelakuIds) as $pelakuId) {
            $punyaJawaban = Jawaban::where('siswa_id', $pelakuId)->whereBetween('created_at', [$startWeek, $endWeek])->exists();
            $pernahDitunjuk = Jawaban::where('id_siswa_pelaku', $pelakuId)->whereBetween('created_at', [$startWeek, $endWeek])->exists();
                if ( $punyaJawaban &&  $pernahDitunjuk) {
                    HitungSkor::updateSkor(
                        $pelakuId,
                        $request->angket,
                        $kelas->id,
                        $soal->guru_id ?? null
                    );
                }
            }

        }

        $data['status'] = Jawaban::join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
        ->where('jawaban.siswa_id', $request->siswa_id)
        ->distinct()
        ->pluck('angket_soals.indikasi_siswa');

        $data['siswa'] = Siswa::select('nis','nama_lengkap','jk')->find($request->siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')
        ->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$request->token)->first();
        $data['feedbacks']= Feedback::all();

       return view('siswa.hasilAngket',$data);
    }


    public function hasil(){
        $siswa_id = 3;
        $token = $request->token ?? 'kJ1P5C' ;
         $data['status'] = Jawaban::join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
        ->where('jawaban.siswa_id', $siswa_id)
        ->distinct()
        ->pluck('angket_soals.indikasi_siswa');
        $data['siswa'] = Siswa::find($siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$token)->first();
        $data['jawabans']=Jawaban::where('siswa_id', $siswa_id)->get();
        $data['feedbacks']= Feedback::all();
       return view('siswa.hasilAngket',$data);
    }
    public function hasil1(Request $request){
        $siswa_id = 2;
        $token = $request->token ?? 'kJ1P5C' ;
        $data['siswa'] = Siswa::find($siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$token)->first();
        $data['jawabans']= Jawaban::where('siswa_id', $siswa_id)->get();
       return view('siswa.hasilAngketKorban',$data);
    }

      public function hasil2(){
        $siswa_id = 2;
        $token ='kJ1P5C' ;
        $data['siswa'] = Siswa::find($siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$token)->first();
        $data['jawabans']= Jawaban::where('siswa_id', $siswa_id)->get();
       return view('siswa.hasilAngketPelaku',$data);
    }
}

<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
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
            $kelas = Kelas::with('sekolah')->where('akses_token', $request->token)
            ->get();
            if ($kelas->isEmpty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Token tidak valid');
            }
            $data['siswas'] = Siswa::select([ 'id','kelas_id','no_absen','nis','nama_lengkap', 'jk'])
                ->whereIn('kelas_id', $kelas->pluck('id'))
                ->orderBy('no_absen')->get();
            $data['kelas'] = $kelas;
            $data['angketsoals'] = AngketSoal::with('angket')->get();
            $data['angket'] = Angket::find(2);
            $data['siswaSudahIsi'] = $siswaSudahIsi;
            $data['token'] = $request->token;
        }

      
        return view('siswa.formAngket', $data);
    }

    public function store(Request $request){
        // return $request;
        foreach ($request->jawaban as $soal_id => $jawaban) {
            if (is_array($jawaban)) {
                foreach ($jawaban as $index => $value) {
                 $jawab =   Jawaban::create([
                            'siswa_id' => $request->siswa_id,
                            'soal_id'  => $soal_id,
                            'jawaban'  => $value,
                            'alasan'   => $request->alasan[$soal_id][$index] ?? '-',
                            ]);
                }
            } 
            // kalau jawaban single (radio / pilihan)
            else {
              $jawab =  Jawaban::create([
                        'siswa_id' => $request->siswa_id,
                        'soal_id'  => $soal_id,
                        'jawaban'  => $jawaban,
                        'alasan'   => $request->alasan[$soal_id] ?? '-',
                    ]);
            }
        }

        $soal = AngketSoal::select('guru_id')
        ->find(array_key_first($request->jawaban));

        $countsStatus = AngketSoal::where('angket_id', $request->angket)
        ->where(function ($q) use ($soal) {
            $q->whereNull('guru_id');

            if ($soal && $soal->guru_id) {
                $q->orWhere('guru_id', $soal->guru_id);
            }
        })
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

        $sumJawabanKorban = Jawaban::where('siswa_id',$request->siswa_id)
        ->whereHas('angket_soals', fn ($q) => $q->where('indikasi_siswa','korban'))
        ->sum('jawaban');

         $sumJawabanPelaku = Jawaban::where('siswa_id',$request->siswa_id)
        ->whereHas('angket_soals', fn ($q) => $q->where('indikasi_siswa','pelaku'))
        ->sum('jawaban');

        $jumlahSoalPelaku = $countsStatus['pelaku'] ?? 0;
        $jumlahSoalKorban = $countsStatus['korban'] ?? 0;

        return [
            'jmlsoalPelaku'=> $jumlahSoalPelaku,
            'jmlsoalKorban'=> $jumlahSoalKorban,
            'jmljawabankorban' => $sumJawabanKorban,
            'jmljawabanpelaku' => $sumJawabanPelaku
    ];

            // $data['siswa'] = Siswa::find($request->siswa_id);
            // $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$request->token)->first();
            // $data['jawabans']=Jawaban::where('siswa_id', $request->siswa_id)->get();
            
       
    
        $skor = '';
        
    //    return view('siswa.hasilAngket',$data);
    }

    public function hasil(){
        $siswa_id = 2;
        $token ='kJ1P5C' ;
        $data['siswa'] = Siswa::find($siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$token)->first();
        $data['jawabans']=Jawaban::where('siswa_id', $siswa_id)->get();
       return view('siswa.hasilAngketKorban',$data);
    }

      public function hasil2(){
        $siswa_id = 2;
        $token ='kJ1P5C' ;
        $data['siswa'] = Siswa::find($siswa_id);
        $data['kelas'] = Kelas::select('nama_kelas','sekolah_id')->with('sekolah:id,nama_sekolah,alamat_lengkap,no_tlp,website,email')->where('akses_token',$token)->first();
        $data['jawabans']=Jawaban::where('siswa_id', $siswa_id)->get();
       return view('siswa.hasilAngketPelaku',$data);
    }
}

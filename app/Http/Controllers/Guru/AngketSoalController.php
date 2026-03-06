<?php

namespace App\Http\Controllers\Guru;

use App\Helpers\TextHelper;
use App\Http\Controllers\Controller;
use App\Models\Angket;
use App\Models\AngketSoal;
use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AngketSoalController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->filled('angket_id')) {
            return abort(404);
        }

        $guruId = auth()->user()->guru->id;
        
        $soal = AngketSoal::where('angket_id', $request->angket_id)
        ->with('guru:id,nip,nama_lengkap')
        ->where('guru_id', $guruId)
        ->orWhere('guru_id', '=', null)
        ->orderBy('sequence', 'asc')
        ->get();

        if (request()->ajax()) {
            return response()->json([
                'data' => $soal
            ]);
        }

        $data['angket'] = Angket::find($request->angket_id);
        $data['guruId'] = $guruId;

        return view('guru.soal.index', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->soal as $item) {
                AngketSoal::updateOrCreate(['id' => $item['id']],[
                    'angket_id'        => $request->angket_id,
                    'sequence'         => $item['sequence'],
                    'soal'             => TextHelper::cleanSummernote($item['soal'] ?? null),
                    'lokasi_kejadian'  => $item['tipe_soal'] === 'keterangan' ? null : ($item['ruang'] ?? null),
                    'tipe_soal'        => $item['tipe_soal'],
                    'indikasi_siswa'   => $item['tipe_soal']  === 'keterangan' ? null : ($item['indikator'] ?? null),
                    'detail_tipe_soal' => $item['opsi'] ?? null,
                    'indikasi_bully'   => $item['tipe_soal'] === 'keterangan' ? "-" : ($item['indikasi_bully'] ?? '-'),
                    'bobot'            => 3,
                    'guru_id'          => auth()->user()->guru->id
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data berhasil dimasukkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 422,
                'message' => 'Data gagal ditambahkan',
                'error'   => $e->getMessage()
            ], 422);
        }
    }

    public function edit(Request $request)
    {
        if (!$request->filled('angket_id')) {
            return abort(404);
        }

        $guruId = auth()->user()->guru->id;

        $soal = AngketSoal::where('angket_id', $request->angket_id)
        ->with('guru:id,nip,nama_lengkap')
        ->where('guru_id', $guruId)
        ->orderBy('sequence', 'asc')
        ->get();

        return response()->json([
            'data' => $soal
        ]);
    }

    public function destroy(AngketSoal $angketSoal)
    {
        try {
            $angketSoal->delete();
            return response()->json([
                'status' => 200,
                'message' => "Berhasil hapus soal"
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menghapus soal',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function printSoal($angketId){
        $guruId = auth()->user()->guru->id;
        $siswa = Siswa::whereHas('kelas.sekolah', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->select('no_absen','nama_lengkap')
        ->orderBy('no_absen','asc')
        ->get();
        
        $soal = AngketSoal::where('angket_id', $angketId)
        ->with('guru:id,nip,nama_lengkap')
        ->where('guru_id', $guruId)
        ->orWhere('guru_id', '=', null)
        ->orderBy('sequence', 'asc')
        ->get();

        $angket = Angket::select('nama_angket')->find($angketId);

        $data['siswas'] = $siswa;
        $data['soals'] = $soal;
        $data['angket']= $angket;

        return view('guru.soal.print-soal',$data);
    }
}

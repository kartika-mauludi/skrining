<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->pluck('id')
        ->toArray();

        $kelas = Kelas::whereIn('sekolah_id', $sekolah)
        ->get();

        if (request()->ajax()) {
            $data = Siswa::whereIn('kelas_id', $kelas->pluck('id'))
            ->with('kelas')
            ->orderBy('no_absen')->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.siswa.index', ['kelas' => $kelas]);
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');

        try{
            Siswa::create($input);
            $message = 'Data siswa berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.siswa.index')->with('message', $message);
    }

    public function edit(Siswa $siswa)
    {
        return response()->json([
            'data' => $siswa
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $input = $request->except('_token');

        try{
            $siswa->update($input);
            $message = 'Data siswa berhasil diperbarui';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.siswa.index')->with('message', $message);
    }

    public function destroy(Siswa $siswa)
    {
        try{
            $siswa->delete();
            $message = 'Data siswa berhasil dihapus';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return response()->json([
            'message' => $message
        ]);
    }

    public function import(Request $request)
    {
       
        $data = $request->input('data');
        $kelasId = $request->kelas_id;

        if (!is_array($data) || empty($data)) {
            return response()->json(['status' => 400, 'message' => 'Data tidak valid!']);
        }

        $response = new StreamedResponse(function () use ($data, $kelasId) {

            try {
                $insertedCount = 0;
                $updatedCount = 0;
                $total = count($data);

                foreach ($data as $index => $item) {

                    // validasi minimal
                    if (!isset(
                        $item['nis'],
                        $item['no_absen'],
                        $item['nama_lengkap'],
                    )) {
                        throw new \Exception("Format data tidak valid di index {$index}");
                    }

                    $record = Siswa::updateOrCreate(
                        ['nis' => $item['nis']],
                        [
                            'kelas_id' => $kelasId,
                            'no_absen' => $item['no_absen'],
                            'nama_lengkap' => $item['nama_lengkap'],
                            'tempat_lahir' => $item['tempat_lahir'],
                            'tgl_lahir' => Carbon::parse($item['tgl_lahir'])->format('Y-m-d'),
                            'alamat' => $item['alamat'],
                            'nama_wali' => $item['nama_wali'],
                            'no_tlp_wali' => $item['no_tlp_wali'],
                        ]
                    );

                    $record->wasRecentlyCreated
                        ? $insertedCount++
                        : $updatedCount++;

                    echo json_encode([
                        'status' => 200,
                        'type'   => 'progress',
                        'message'=> 'Mengimport data...',
                        'current'=> $index + 1,
                        'total'  => $total,
                        'processed' => $insertedCount + $updatedCount,
                    ]) . "\n";

                    ob_flush();
                    flush();
                }

                echo json_encode([
                    'status' => 200,
                    'type'   => 'done',
                    'message'=> 'Import selesai',
                    'processed' => $insertedCount + $updatedCount,
                ]) . "\n";

                ob_flush();
                flush();

            } catch (\Throwable $e) {

                echo json_encode([
                    'status'  => 500,
                    'type'    => 'error',
                    'message' => $e->getMessage(),
                    'line'    => $e->getLine(),
                    'file'    => basename($e->getFile()),
                ]) . "\n";

                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Cache-Control', 'no-cache');
        return $response;
    }
}

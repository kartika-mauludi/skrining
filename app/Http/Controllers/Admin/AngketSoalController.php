<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angket;
use Illuminate\Http\Request;
use App\Models\AngketSoal;
use Validator;
use Exception;
use DB;
use App\Helpers\TextHelper;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AngketSoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
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
                    'bobot'            => 3
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'Data berhasil dimasukkan'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 422,
                'message' => 'Data gagal ditambahkan',
                'error'   => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $angket = Angket::find($id);
        $soal = AngketSoal::where('angket_id',$id)->orderBy('sequence','asc')->get();
        return view('admin.angketSoal.index',compact('angket','soal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AngketSoal $angketsoal)
    {
         try {
        $angketsoal->delete();
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

    public function destroyAll(Request $request)
    {
        try {
            AngketSoal::where('angket_id', $request->angket_id)->delete();

            return response()->json([
                'status'  => 200,
                'message' => 'Semua soal berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Gagal menghapus semua soal',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function data(){
        $data = AngketSoal::with('guru:id,nip,nama_lengkap')
        ->orderBy('sequence','asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function import(Request $request)
    {
        $data = $request->input('data');
        
       if (!is_array($data) || empty($data)) {
            return response()->stream(function () {
                echo json_encode([
                    'status' => 400,
                    'message' => 'Data tidak valid!'
                ]) . "\n";
            }, 400, ['Content-Type' => 'application/json']);
        }


        $response = new StreamedResponse(function () use ($data) {
            $insertedCount = 0;
            $updatedCount = 0;

        $maxSequence = AngketSoal::max('sequence') ?? 0;
            foreach ($data as $index => $item) {
                 $maxSequence++;
                if (!isset($item['soal']) || !isset($item['tipe_soal']) || !isset($item['ruang_lingkup']) || !isset($item['indikator']) || !isset($item['indikasi'])) {
                    echo json_encode(['status' => 400, 'message' => 'Format data tidak valid!']);
                    ob_flush();
                    flush();
                    return;
                }

                $record = AngketSoal::Create(
                    ['angket_id' => $item['angketId'], 'soal' => $item['soal'],'sequence'=> $maxSequence , 'tipe_soal' => $item['tipe_soal'], 'lokasi_kejadian' => $item['ruang_lingkup'], 'indikasi_siswa' => $item['indikator'], 'indikasi_bully' => $item['indikasi']],
                );

                if ($record->wasRecentlyCreated) {
                    $insertedCount++;
                } else {
                    $updatedCount++;
                }

                echo json_encode([
                    'status' => 200,
                    'message' => 'Mengimport data...',
                    'progress' => ($index + 1) . '/' . count($data),
                    'procesed' => $insertedCount + $updatedCount
                ]) . "\n";
                ob_flush();
                flush();
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Import selesai!',
                'procesed' => $insertedCount + $updatedCount
            ]) . "\n";
            ob_flush();
            flush();
        });

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

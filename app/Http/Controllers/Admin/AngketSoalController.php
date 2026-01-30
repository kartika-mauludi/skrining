<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angket;
use Illuminate\Http\Request;
use App\Models\AngketSoal;
use Validator;
use Exception;
use DB;

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
        DB::beginTransaction();

        try {
            foreach ($request->soal as $item) {
                AngketSoal::create([
                    'angket_id'        => $request->angket_id,
                    'sequence'         => $item['sequence'],
                    'soal'             => $item['pertanyaan'],
                    'lokasi_kejadian'  => $item['tipe_soal'] === 'keterangan' ? null : ($item['ruang'] ?? null),
                    'tipe_soal'        => $item['tipe_soal'],
                    'indikasi_siswa'   => $item['tipe_soal']  === 'keterangan' ? null : ($item['indikator'] ?? null),
                    'detail_tipe_soal' => $item['opsi'] ?? null,
                    'bobot'            => 1
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $angket = Angket::find($id);
        return view('admin.angketSoal.index',compact('angket'));
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
    public function destroy(string $id)
    {
        //
    }

    public function data(){
        $data = AngketSoal::all();

        return response()->json([
            'data' => $data
        ]);
    }
}

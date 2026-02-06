<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use DB;
use Exception;
use Validator;

class TanggapanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.tanggapan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = Validator::make($request->all(),[
            'feedback_deskripsi' => 'required',
            'status' => 'required'
        ],[
            'feedback_deskripsi.required' =>'Tanggapan tidak boleh dikosongkan',
            'status.required' =>'Pilih status tanggapan dikosongkan',
        ]);


        if($input->fails()){
             return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ], 422);
        }

        $validate = $input->validated();

      DB::beginTransaction();

        try {
            Feedback::create([
                'feedback_deskripsi' => $validate['feedback_deskripsi'],
                'status'             => $validate['status'],
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Guru berhasil ditambahkan'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Gagal menambah guru',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Feedback::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feedback $tanggapan)
    {
        $validasi = $request->except('_token', '_method', 'password');
        $input = Validator::make($request->all(),[
            'feedback_deskripsi' => 'required',
            'status' => 'required'
        ],[
            'feedback_deskripsi.required' =>'Tanggapan tidak boleh dikosongkan',
            'status.required' =>'Pilih status tanggapan dikosongkan',
        ]);

        if($input->fails()){
            return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ], 422);
        }

        $status = 400;
        $message = 'gagal edit data';

         $validatedData = $input->validated();
         $result = $tanggapan->update([
                'feedback_deskripsi'  => $validatedData['feedback_deskripsi'],
                'status'             => $validatedData['status'],
              
         ]);
         
         if($result){
            $status = 200;
            $message = 'data berhasil di update';
         }

         return response()->json([
            'status' => $status,
            'message' => $message
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feedback $tanggapan)
    {
         try {
        $tanggapan->delete();
        return response()->json([
            'status' => 200,
            'message' => "Berhasil hapus user"
        ]);
        } catch (Exception $e) {
        return response()->json([
            'status' => 500,
            'message' => 'Gagal menghapus user',
            'error' => $e->getMessage()
        ]);
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            Feedback::query()->delete();
            return response()->json([
                'status'  => 200,
                'message' => 'Semua Tanggapan berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status'  => 500,
                'message' => 'Gagal menghapus semua tanggapan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function data(){
        $data = Feedback::all();
        return response()->json([
            'data' => $data
        ]);
    }


}

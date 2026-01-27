<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Angket;
use Validator;
use Exception;


class AngketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.angket.index');
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
        $input = validator::make($request->all(),[
            'nama_angket' => 'required'
        ],[
            'nama_angket.required' => 'Judul angket harus di isi'
        ]);

        if($input->fails()){
            return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ],422);
        }

        $status = 400;
        $message = "Data gagal di tambah";

        $validate = $input->validated();

        $result = Angket::create([
            'nama_angket' => $validate['nama_angket']
        ]);

        if($result){
            $status = 200;
            $message = 'Data berhasil di masukkan';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
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
        $data = Angket::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Angket $angket)
    {
        $validasi = $request->except('_token', '_method', 'password');
        $input = Validator::make($request->all(),[
            'nama_angket' => 'required',
         ],[
            'nama_angket.required' =>'Angket tidak boleh dikosongkan',
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
         $result = $angket->update([
            'nama_angket'           => $validatedData['nama_angket'],
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
    public function destroy(Angket $angket)
    {
         try {
        $angket->delete();
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

    public function data(){
        $data = Angket::all();
        return response()->json([
            'data' => $data
        ]);
    }
}

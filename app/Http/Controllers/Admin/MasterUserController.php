<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Exception;

class MasterUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('admin.masteruser.index');
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
        
        $input = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
        ],[
            'name.required' =>'Nama tidak boleh dikosongkan',
            'email.required' => 'email tidak boleh dikosongkan',
            'email.unique' => 'email sudah pernah didaftarkan',
            'password.required' => 'password tidak boleh dikosongkan',
            'password.min' => 'password minimal 8 character',
            'role.required' => 'role tidak boleh dikosongkan'
        ]);

        if($input->fails()){
             return response()->json([
                'status' => false,
                'errors' => $input->errors()
            ], 422);
        }

        $validate = $input->validated();

        $status = 400;
        $message = "User gagal di tambah";

        $result = user::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => bcrypt($validate['password']),
            'role' => $validate['role'],
        ]);

         if($result){
            $status = 200;
            $message = "Data Berhasil Ditambah";
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
        $data = User::find($id);
        return response()->json($data);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $masteruser)
    {
         $validasi = $request->except('_token', '_method', 'password');
        $input = Validator::make($validasi,[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$masteruser->id,
            'role' => 'required',
        ],[
            'name.required' =>'Nama tidak boleh dikosongkan',
            'email.required' => 'email tidak boleh dikosongkan',
            'email.unique' => 'email sudah pernah didaftarkan',
            'role.required' => 'role tidak boleh dikosongkan'
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

         if($request->filled('password')){
            $validatedData['password'] = Hash::make($request->password);
         }

         $result = $masteruser->update($validatedData);
         
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
    public function destroy(User $masteruser)
    {
        
        try {
        $masteruser->roles()->detach();
        $masteruser->delete();

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
        $data = User::all();
        return response()->json([
            "data" => $data
        ]);     
    }


}

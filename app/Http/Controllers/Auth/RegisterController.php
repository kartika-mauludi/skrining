<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\User;
use App\Models\Guru;
use Exception;

class RegisterController extends Controller
{
    public function register(Request $request){
        // return $request;
         $input = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'nama_lengkap' => 'required',
            'nip' => 'required|unique:gurus,nip',
            
        ],[
            'name.required' =>'Usernmae tidak boleh dikosongkan',
            'nama_lengkap.required' =>'Nama lengkap tidak boleh dikosongkan',
            'nip.required' =>'NIP tidak boleh dikosongkan',
            'nip.unique' => 'NIP sudah pernah terpakai',
            'email.required' => 'email tidak boleh dikosongkan',
            'email.unique' => 'email sudah pernah didaftarkan',
            'password.required' => 'password tidak boleh dikosongkan',
            'password.min' => 'password minimal 8 character',
            'password.confirmed' => 'Password tidak sama dengan password konfirmasi'
        ]);

        if($input->fails()){
            // return "error";
            return redirect()->back()
            ->withErrors($input)
            ->withInput();
        }

        $validate = $input->validated();

      DB::beginTransaction();

        try {
            $user = User::create([
                'name'     => $validate['name'],
                'email'    => $validate['email'],
                'password' => bcrypt($validate['password']),
                'role'     => 'guru',
            ]);

            Guru::create([
                'user_id'       => $user->id,
                'nip'           => $validate['nip'],
                'nama_lengkap'  => $validate['nama_lengkap'],
                'alamat'        => $request->filled('alamat') ? $request->alamat : '-',
                'tempat_lahir'  => $request->filled('tempat_lahir') ? $request->tempat_lahir : '-',
                'tgl_lahir'     => $request->filled('tgl_lahir') ? $request->tgl_lahir : Null,
                'no_tlp'        => $request->filled('no_tlp') ? $request->no_tlp : '-',
                'email'         => $validate['email']
            ]);

            DB::commit();

                  return redirect()->back()->with('success', 
                    'Registrasi Berhasil'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return redirect()->back()->with('error', 
                'Gagal Registrasi'
            );
        }
    }
}

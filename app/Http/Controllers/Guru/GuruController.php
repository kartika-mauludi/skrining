<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guru;
use auth;
use App\Models\User;
use Hash;

class GuruController extends Controller
{
    public function index(){
        $profil = User::with('guru')->find(auth::user()->id);
        return view('guru.profil',compact('profil'));
    }

    public function update(Request $request){
       $guru = Guru::where('user_id',$request->id)->first();

        try {

            if ($request->filled('password')) {
                $input['password'] = Hash::make($request->password);
            }

            $guru->update([
                'nama_lengkap'  => $request->nama_lengkap,
                'no_tlp'        => $request->no_tlp,
                'alamat'        => $request->alamat,
                'tempat_lahir'  => $request->tempat_lahir,
                'tgl_lahir'     => $request->tgl_lahir
            ]);

            return redirect()->back()->with('success', 
            'Data Berhasil di Simpan'
            );

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 
            'Data Gagal di Simpan'
            );
        }
       

    }
}

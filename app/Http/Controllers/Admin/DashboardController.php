<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengaturan;
use Auth;
use Hash;
use App\Models\Sekolah;
use App\Models\Guru;
use App\Models\Angket;


class DashboardController extends Controller
{     public function index(){
        $data['sekolah'] = Sekolah::count();
        $data['guru'] = Guru::count();
        $data['angket'] = Angket::count();
        return view('admin.index',$data);
    }

    public function profil(){
       $profil = auth()->user();
       $pengaturan = Pengaturan::first();
        return view('admin.profil',compact('profil','pengaturan'));
    }

    public function pengaturan(Request $request){
        // return $request;
        $jenis = $request->jenis;
        $id = $request->id;
        try {
           if($jenis === "profil" ){
            $input = $request->except('_token', '_method', 'password');

            if ($request->filled('password')) {
                $input['password'] = Hash::make($request->password);
            }
            $user = User::find($id);
            $user->update($input);
        }

         if($jenis === "pengaturan"){
            $input = $request->except('_token', '_method');
            Pengaturan::updateOrCreate(
                [
                    'id' => $id
                ],
                $input);
        }

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

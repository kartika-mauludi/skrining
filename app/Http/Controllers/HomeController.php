<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admin(){
         return view('admin.index');
    }

     public function guru(){
        $guruId = auth()->user()->guru->id;
       
        $siswa = Siswa::whereHas('kelas.sekolah', function ($query) use ($guruId) {
            $query->where('guru_id', $guruId);
        })->select('no_absen')
        ->count();

        $laki = Siswa::whereHas('kelas.sekolah', function ($query) use ($guruId) {
        $query->where('guru_id', $guruId);
        })->where('jk','=','laki-laki')
        ->count();

        $perempuan = Siswa::whereHas('kelas.sekolah', function ($query) use ($guruId) {
        $query->where('guru_id', $guruId);
        })->where('jk','=','perempuan')
        ->count();


        $siswaId = Siswa::whereHas('kelas.sekolah', function ($query) use ($guruId) {
        $query->where('guru_id', $guruId);
        })->select('id')
        ->get();



        $data['siswa']= $siswa;
        $data['laki'] = $laki;
        $data['perempuan'] = $perempuan;

        return view('guru.index',$data);
    }

    public function siswa(){

    }
}

<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Angket;
use App\Models\AngketSoal;
use App\Models\Siswa;

class FormAngketController extends Controller
{
    public function index(){
        $data['angketsoals'] = AngketSoal::with('angket')->get();
        $data['angket'] = angket::find(2);
        $data['siswas'] = siswa::all();

        return view('siswa.formAngket', $data);
    }
}

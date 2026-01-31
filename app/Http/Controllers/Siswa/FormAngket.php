<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Angket;
use App\Models\AngketSoal;

class FormAngket extends Controller
{
    public function index(){
        $angkets = AngketSoal::all();
        return $angkets;
        return view('siswa.formAngket');
    }
}

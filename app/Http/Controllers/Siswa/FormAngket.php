<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;

class FormAngket extends Controller
{
    public function index(){
        return view('siswa.formAngket');
    }
}

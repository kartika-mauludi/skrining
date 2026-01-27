<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
         return view('guru.index');
    }

    public function siswa(){

    }
}

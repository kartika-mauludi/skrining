<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;

class ReportController extends Controller
{
    public function index(){
        $data['sekolah'] = Sekolah::all();
        return view('admin.report.index',$data);
    }
}

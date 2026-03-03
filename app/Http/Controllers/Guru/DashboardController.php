<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->pluck('id')
        ->toArray();

        $data['siswa'] = Siswa::whereHas('kelas', function($query) use ($sekolah) {
            $query->whereIn('sekolah_id', $sekolah);
        })->count();

        return view('guru.index', $data);
    }
}

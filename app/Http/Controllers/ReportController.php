<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function sosiogram(Request $request)
    {
        $data['sekolah'] = Sekolah::where('guru_id', auth()->user()->guru->id)->get();

        if ($request->filled('kelas')) {
            $siswa = Siswa::where('kelas_id', $request->kelas)->get();
            $data['request'] = $request->only('sekolah', 'kelas');
            $data['kelas'] = Kelas::find($request->kelas);
            $data['siswa'] = $siswa;
            $data['reports'] = Jawaban::whereIn('siswa_id', $siswa->pluck('id')->unique())->get();
        }

        return view('guru.report-sosiogram.index', $data);
    }
}

<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sosiogram(Request $request)
    {
        $data['sekolah'] = Sekolah::where('guru_id', auth()->user()->guru->id)->get();

        if ($request->filled('kelas')) {
            $siswa = Siswa::where('kelas_id', $request->kelas)->get();
            $reports = Jawaban::whereIn('siswa_id', $siswa->pluck('id')->unique())->get();
            $pelakuIds = $reports->pluck('id_siswa_pelaku')
            ->filter(fn($id) => !is_null($id))
            ->unique()
            ->values()
            ->toArray();

            $data['request'] = $request->only('sekolah', 'kelas');
            $data['kelas'] = Kelas::find($request->kelas);
            $data['siswa'] = $siswa;
            $data['reports'] = $reports;
            $data['mostReported'] = Jawaban::select('id_siswa_pelaku', DB::raw('COUNT(*) as count'))
            ->with('siswapelaku:id,nis,nama_lengkap')
            ->whereNotNull('id_siswa_pelaku')
            ->groupBy('id_siswa_pelaku')
            ->orderBy('count', 'DESC')
            ->first();
            $data['notReporteds'] = Siswa::where('kelas_id', $request->kelas)
            ->whereNotIn('id', $pelakuIds)->get();
        }

        return view('guru.report-sosiogram.index', $data);
    }
}

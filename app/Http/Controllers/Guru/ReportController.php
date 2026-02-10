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
    public function index()
    {
        return view('guru.report.index');
    }

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

            $pairs = [];

            foreach ($reports as $r) {
                $pairs[] = $r->siswa_id . '-' . $r->id_siswa_pelaku;
            }

            $mutualPairs = [];

            foreach ($reports as $r) {
                $reverse = $r->id_siswa_pelaku . '-' . $r->siswa_id;

                if (in_array($reverse, $pairs)) {
                    $key = collect([$r->siswa_id, $r->id_siswa_pelaku])
                        ->sort()
                        ->implode('-');

                    $mutualPairs[$key] = [
                        'a' => $r->siswa_id,
                        'b' => $r->id_siswa_pelaku,
                    ];
                }
            }

            $data['mutualReporteds'] = collect($mutualPairs)->map(function ($pair) {
                return [
                    'siswa_a' => Siswa::find($pair['a']),
                    'siswa_b' => Siswa::find($pair['b']),
                ];
            })->values();

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

    public function matriks(Request $request)
    {
        $data = [];

        $data['sekolah'] = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->get();

        if ($request->filled('kelas')) {
            $kelas = Kelas::findOrFail($request->kelas);

            $siswa = Siswa::where('kelas_id', $kelas->id)
                ->orderBy('nama_lengkap')
                ->get();

            $reports = Jawaban::whereIn('siswa_id', $siswa->pluck('id'))
            ->whereNotNull('id_siswa_pelaku')
            ->select('siswa_id as id_siswa_pelapor', 'id_siswa_pelaku as id_siswa_terlapor')
            ->get();

            $data['kelas']   = $kelas;
            $data['siswa']   = $siswa;
            $data['reports'] = $reports;
            $data['request'] = $request->only('sekolah', 'kelas');
        }

        return view('guru.report-matriks.index', $data);
    }

    public function korban(Siswa $siswa)
    {
        $data['kelas'] = $siswa->kelas;
        $data['siswa'] = $siswa;

        return view('guru.report.korban', $data);
    }

    public function pelaku(Siswa $siswa)
    {
        $data['kelas'] = $siswa->kelas;
        $data['siswa'] = $siswa;

        return view('guru.report.pelaku', $data);
    }
}

<?php

namespace App\Http\Controllers\Guru;

use App\Helpers\HitungSkor;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\HasilScore;
use App\Models\Jawaban;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    static $indikatorBully = ['verbal', 'fisik', 'sosial'];
    static $indikatorCiberBully = ['impersonation', 'visual_sexual', 'written_verbal', 'online_exclusion'];
    static $lokasiKejadian = ['sosmed', 'game', 'lingkungan kelas', 'lainnya'];

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
        $gaugeMeter = HasilScore::where('siswa_id', $siswa->id)
        ->avg('skor_korban');
        $feedbacks  = Feedback::select('id', 'feedback_deskripsi')
        ->whereIn('status', ['korban', 'netral'])
        ->where(function($query) {
            $query->where('id_guru', auth()->user()->guru->id)
                ->orWhere('id_guru', null);
        })
        ->get();

        $locationCount = [];

        foreach ($this::$lokasiKejadian as $lokasi) {
            $jawaban = Jawaban::withCount('angket_soals')
            ->whereRelation('angket_soals', 'lokasi_kejadian', $lokasi)
            ->where('id_siswa_pelaku', '!=', null)
            ->where('siswa_id', $siswa->id)
            ->count();

            $locationCount[$lokasi] = $jawaban;    
        }

        $indikator = array_merge($this::$indikatorBully, $this::$indikatorCiberBully);

        $reportReasons = [];

        foreach ($this::$indikatorBully as $bully) {
            $jawaban = Jawaban::with('angket_soals', 'siswapelaku')
            ->whereRelation('angket_soals', 'indikasi_bully', $bully)
            ->where('id_siswa_pelaku', '!=', null)
            ->where('siswa_id', $siswa->id)
            ->get()
            ->map(function ($model) {
                return [
                    'pelaku' => optional($model->siswapelaku)->nama_lengkap,
                    'alasan' => $model->alasan
                ];
            });

            $reportReasons[$bully] = $jawaban;
        }

        $data['kelas'] = $siswa->kelas;
        $data['siswa'] = $siswa;
        $data['gaugeMeter'] = $gaugeMeter ?? 0;
        $data['feedbacks'] = $feedbacks;
        $data['indikator'] = $indikator;
        $data['skorKorbanAll'] = HitungSkor::hitungKorbanPerIndikator($siswa->id, $indikator);
        $data['skorKorban'] = HitungSkor::hitungKorbanPerIndikator($siswa->id, $this::$indikatorBully);
        $data['skorKorbanCyber'] = HitungSkor::hitungKorbanPerIndikator($siswa->id, $this::$indikatorCiberBully);
        $data['locationCount'] = $locationCount;
        $data['reportReasons'] = $reportReasons;

        return view('guru.report.korban', $data);
    }

    public function pelaku(Siswa $siswa)
    {
        $gaugeMeter = HasilScore::where('siswa_id', $siswa->id)
        ->avg('skor_pelaku');
        $feedbacks  = Feedback::select('id', 'feedback_deskripsi')
        ->whereIn('status', ['pelaku', 'netral'])
        ->where(function($query) {
            $query->where('id_guru', auth()->user()->guru->id)
                ->orWhere('id_guru', null);
        })
        ->get();
        $countAsPelaku = Jawaban::where('id_siswa_pelaku', $siswa->id)
        ->count();

        $indikator = array_merge($this::$indikatorBully, $this::$indikatorCiberBully);

        $locationCount = [];

        foreach ($this::$lokasiKejadian as $lokasi) {
            $jawaban = Jawaban::withCount('angket_soals')
            ->whereRelation('angket_soals', 'lokasi_kejadian', $lokasi)
            ->where('id_siswa_pelaku', $siswa->id)
            ->count();

            $locationCount[$lokasi] = $jawaban;    
        }

        $reportReasons = [];

        foreach ($this::$indikatorBully as $bully) {
            $jawaban = Jawaban::with('angket_soals', 'siswa')
            ->whereRelation('angket_soals', 'indikasi_bully', $bully)
            ->where('id_siswa_pelaku', $siswa->id)
            ->get()
            ->map(function ($model) {
                return [
                    'korban' => optional($model->siswa)->nama_lengkap,
                    'alasan' => $model->alasan
                ];
            });

            $reportReasons[$bully] = $jawaban;
        }

        $data['kelas'] = $siswa->kelas;
        $data['siswa'] = $siswa;
        $data['gaugeMeter'] = $gaugeMeter ?? 0;
        $data['feedbacks'] = $feedbacks;
        $data['indikator'] = $indikator;
        $data['skorKorbanAll'] = 0;
        $data['skorKorban'] = 0;
        $data['skorKorbanCyber'] = 0;
        $data['locationCount'] = $locationCount;
        $data['reportReasons'] = $reportReasons;
        $data['countAsPelaku'] = $countAsPelaku;

        return view('guru.report.pelaku', $data);
    }
}

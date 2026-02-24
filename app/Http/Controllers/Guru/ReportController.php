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
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class ReportController extends Controller
{
    static $indikatorBully = ['verbal', 'fisik', 'sosial'];
    static $indikatorCiberBully = ['impersonation', 'visual_sexual', 'written_verbal', 'online_exclusion'];
    static $lokasiKejadian = ['sosmed', 'game', 'lingkungan kelas', 'lainnya'];

    public function index()
    {
        $data['sekolah'] = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->get();

        return view('guru.report.index', $data);
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

    public function pelaku(Siswa $siswa)
    {
        $data = $this->getPelakuData($siswa);

        return view('guru.report.pelaku', $data);
    }

    public function korban(Siswa $siswa)
    {
        $data = $this->getKorbanData($siswa);

        return view('guru.report.korban', $data);
    }

    public function printPelaku(Request $request, Siswa $siswa)
    {
        $data = $this->getPelakuData($siswa);

        // $data['historyImage']  = $request->history_image;
        // $data['kategoriImage'] = $request->kategori_image;
        // $data['cyberImage']    = $request->cyber_image;
        // $data['gaugeImage']    = $request->gauge_image;

        return view('guru.report.pelaku-print', $data);
    }

    public function printPdfPelaku(Request $request, Siswa $siswa)
    {
        $data = $this->getPelakuData($siswa);

        $data['historyImage']  = $request->history_image;
        $data['kategoriImage'] = $request->kategori_image;
        $data['cyberImage']    = $request->cyber_image;
        $data['gaugeImage']    = $request->gauge_image;

        $pdf = Pdf::loadView('guru.report.pelaku-pdf', $data)
        ->setPaper('a4', 'portrait');

        return $pdf->stream('report-pelaku.pdf');
    }

    public function printKorban(Request $request, Siswa $siswa)
    {
        $data = $this->getKorbanData($siswa);

        $data['historyImage'] = $request->history_image;
        $data['kategoriImage'] = $request->kategori_image;
        $data['cyberImage'] = $request->cyber_image;
        $data['gaugeImage'] = $request->gauge_image;

        return view('guru.report.korban-print', $data);
    }

    public function printPdfKorban(Request $request, Siswa $siswa)
    {
        $data = $this->getKorbanData($siswa);

        $data['historyImage'] = $request->history_image;
        $data['kategoriImage'] = $request->kategori_image;
        $data['cyberImage'] = $request->cyber_image;
        $data['gaugeImage'] = $request->gauge_image;

        $pdf = Pdf::loadView('guru.report.korban-pdf', $data)
        ->setPaper('a4', 'portrait');

        return $pdf->stream('report-korban.pdf');
    }

    public function downloadAll(Request $request)
    {
        $siswas = Siswa::where('kelas_id', $request->kelas)
        ->get();

        $type = $request->type;
        $dataPerSiswa = [];

        foreach ($siswas as $siswa) {
            if ($type == 'pelaku') {
                $dataPerSiswa[] = $this->getPelakuData($siswa);
            } elseif ($type == 'korban') {
                $dataPerSiswa[] = $this->getKorbanData($siswa);
            } else {
                return back()->with('message', 'Tipe laporan tidak ditemukan');
            }
        }

        $data['datas'] = $dataPerSiswa;
        $data['type'] = $type;

        return view('guru.report.report-all', $data);

        // if ($siswas->isEmpty()) {
        //     return back()->with('message', 'Tidak ada siswa.');
        // }

        // $zipFileName = 'report-' . $request->type . '-kelas-' . $request->kelas . '.zip';
        // $zipPath = storage_path('app/' . $zipFileName);

        // $zip = new ZipArchive;

        // if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

        //     $colors = ['#3b82f6', '#8b5cf6', '#ec4899'];

        //     foreach ($siswas as $siswa) {
        //         if ($request->type === 'korban') {
        //             $data = $this->getKorbanData($siswa);

        //             $datasets = collect($data['skorKorbanAll']['datasets'])
        //                 ->values()
        //                 ->map(function ($ds, $index) use ($colors) {
        //                     $color = $colors[$index % count($colors)];

        //                     return [
        //                         "label" => $ds['label'],
        //                         "data" => $ds['data'],
        //                         "borderColor" => $color,
        //                         "backgroundColor" => $color . '33',
        //                         "pointBackgroundColor" => $color,
        //                         "borderWidth" => 2,
        //                         "tension" => 0.5,
        //                         "fill" => false
        //                     ];
        //                 })
        //                 ->toArray();

        //             $historyConfig = [
        //                 "type" => "line",
        //                 "data" => [
        //                     "labels" => $data['skorKorbanAll']['labels'],
        //                     "datasets" => $datasets
        //                 ],
        //                 "options" => [
        //                     "plugins" => [
        //                         "legend" => ["display" => false],
        //                         "annotation" => [
        //                             "annotations" => [
        //                                 [
        //                                     "type" => "box",
        //                                     "yMin" => 0,
        //                                     "yMax" => 20,
        //                                     "backgroundColor" => "rgba(36,198,74,0.2)"
        //                                 ],
        //                                 [
        //                                     "type" => "box",
        //                                     "yMin" => 20,
        //                                     "yMax" => 50,
        //                                     "backgroundColor" => "rgba(214,242,90,0.2)"
        //                                 ],
        //                                 [
        //                                     "type" => "box",
        //                                     "yMin" => 50,
        //                                     "yMax" => 100,
        //                                     "backgroundColor" => "rgba(230,34,54,0.2)"
        //                                 ]
        //                             ]
        //                         ]
        //                     ],
        //                     "scales" => [
        //                         "y" => [
        //                             "min" => 0,
        //                             "max" => 100,
        //                             "ticks" => [
        //                                 "callback" => "function(value){ if(value==20)return 'Aman'; if(value==50)return 'Hati-hati'; if(value==100)return 'Bahaya'; return ''; }"
        //                             ],
        //                             "afterBuildTicks" => "function(scale){ scale.ticks = [20,50,100]; }"
        //                         ],
        //                         "x" => [
        //                             "grid" => ["display" => false]
        //                         ]
        //                     ]
        //                 ]
        //             ];

        //             $nilai = round($data['gaugeMeter'], 2);

        //             $gaugeConfig = [
        //                 "type" => "gauge",
        //                 "data" => [
        //                     "labels" => ["Aman", "Hati Hati", "Bahaya"],
        //                     "datasets" => [[
        //                         "data" => [20, 50, 100],
        //                         "minValue" => 0,
        //                         "maxValue" => 100,
        //                         "value" => $nilai,
        //                         "backgroundColor" => ["#09e63c", "#d4f544", "#cb3600"],
        //                         "borderWidth" => 1
        //                     ]]
        //                 ],
        //                 "options" => [
        //                     "responsive" => true,
        //                     "needle" => [
        //                         "radiusPercentage" => 2,
        //                         "widthPercentage" => 2.2,
        //                         "lengthPercentage" => 50,
        //                         "color" => "#FF6112"
        //                     ]
        //                 ]
        //             ];

        //             $buildBar = function ($source) use ($colors) {
        //                 return collect($source['datasets'])
        //                     ->values()
        //                     ->map(function ($ds, $index) use ($colors) {
        //                         $color = $colors[$index % count($colors)];

        //                         return [
        //                             "label" => $ds['label'],
        //                             "data" => $ds['data'],
        //                             "backgroundColor" => $color . '33',
        //                             "borderColor" => $color,
        //                             "borderWidth" => 1
        //                         ];
        //                     })
        //                     ->toArray();
        //             };

        //             $kategoriConfig = [
        //                 "type" => "bar",
        //                 "data" => [
        //                     "labels" => $data['skorKorban']['labels'],
        //                     "datasets" => $buildBar($data['skorKorban'])
        //                 ],
        //                 "options" => [
        //                     "plugins" => ["legend" => ["display" => false]],
        //                     "scales" => [
        //                         "y" => ["beginAtZero" => true]
        //                     ]
        //                 ]
        //             ];

        //             $cyberConfig = [
        //                 "type" => "bar",
        //                 "data" => [
        //                     "labels" => $data['skorKorbanCyber']['labels'],
        //                     "datasets" => $buildBar($data['skorKorbanCyber'])
        //                 ],
        //                 "options" => [
        //                     "plugins" => ["legend" => ["display" => false]],
        //                     "scales" => [
        //                         "y" => ["beginAtZero" => true]
        //                     ]
        //                 ]
        //             ];

        //             $view = 'guru.report.korban-pdf';
        //         }
    
        //         if ($request->type === 'pelaku') {
        //             $data = $this->getPelakuData($siswa);

        //             $historyConfig = [
        //                 "type" => "bar",
        //                 "data" => [
        //                     "labels" => $data['skorKorbanAll']['labels'],
        //                     "datasets" => $data['skorKorbanAll']['datasets']
        //                 ],
        //                 "options" => [
        //                     "plugins" => [
        //                         "legend" => ["display" => true]
        //                     ],
        //                     "scales" => [
        //                         "y" => [
        //                             "beginAtZero" => true
        //                         ]
        //                     ]
        //                 ]
        //             ];

        //             $nilai = round($data['gaugeMeter'], 2);

        //             $gaugeConfig = [
        //                 "type" => "doughnut",
        //                 "data" => [
        //                     "labels" => ["Skor", "Sisa"],
        //                     "datasets" => [[
        //                         "data" => [$nilai, 100 - $nilai],
        //                         "backgroundColor" => ["#dc3545", "#e9ecef"]
        //                     ]]
        //                 ],
        //                 "options" => [
        //                     "rotation" => -90,
        //                     "circumference" => 180,
        //                     "plugins" => [
        //                         "legend" => ["display" => false]
        //                     ]
        //                 ]
        //             ];

        //             $kategoriConfig = [
        //                 "type" => "bar",
        //                 "data" => [
        //                     "labels" => $data['skorKorban']['labels'],
        //                     "datasets" => $data['skorKorban']['datasets']
        //                 ],
        //                 "options" => [
        //                     "plugins" => [
        //                         "legend" => ["display" => true]
        //                     ],
        //                     "scales" => [
        //                         "y" => [
        //                             "beginAtZero" => true
        //                         ]
        //                     ]
        //                 ]
        //             ];

        //             $cyberConfig = [
        //                 "type" => "bar",
        //                 "data" => [
        //                     "labels" => $data['skorKorbanCyber']['labels'],
        //                     "datasets" => $data['skorKorbanCyber']['datasets']
        //                 ],
        //                 "options" => [
        //                     "plugins" => [
        //                         "legend" => ["display" => true]
        //                     ],
        //                     "scales" => [
        //                         "y" => [
        //                             "beginAtZero" => true
        //                         ]
        //                     ]
        //                 ]
        //             ];

        //             $view = 'guru.report.pelaku-pdf';
        //         }

        //         $data['historyImage'] = $this->makeChartUrl($historyConfig, 700, 300);
        //         $data['gaugeImage'] = $this->makeChartUrl($gaugeConfig, 400, 250);
        //         $data['kategoriImage'] = $this->makeChartUrl($kategoriConfig, 600, 250);
        //         $data['cyberImage'] = $this->makeChartUrl($cyberConfig, 600, 250);

        //         return $this->makeChartUrl($gaugeConfig, 700, 300);

        //         // $data['historyImage'] = $request->history_image;
        //         // $data['kategoriImage'] = $request->kategori_image;
        //         // $data['cyberImage'] = $request->cyber_image;
        //         // $data['gaugeImage'] = $request->gauge_image;

        //         $pdf = Pdf::loadView($view, $data)
        //         ->setPaper('a4', 'portrait');

        //         $fileName = $siswa->nama_lengkap . '-' . $request->type . '.pdf';

        //         $zip->addFromString($fileName, $pdf->output());
        //     }

        //     $zip->close();
        // }

        // return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function getPelakuData(Siswa $siswa)
    {
        $gaugeMeter = HasilScore::where('siswa_id', $siswa->id)
            ->avg('skor_pelaku');

        $feedbacks = Feedback::select('id', 'feedback_deskripsi')
            ->whereIn('status', ['pelaku', 'netral'])
            ->where(function ($query) {
                $query->where('id_guru', auth()->user()->guru->id)
                    ->orWhereNull('id_guru');
            })
            ->get();

        $countAsPelaku = Jawaban::where('id_siswa_pelaku', $siswa->id)
            ->count();

        $indikator = array_merge(
            $this::$indikatorBully,
            $this::$indikatorCiberBully
        );

        // =============================
        // Lokasi Kejadian
        // =============================
        $locationCount = [];

        foreach ($this::$lokasiKejadian as $lokasi) {
            $locationCount[$lokasi] = Jawaban::whereRelation(
                'angket_soals',
                'lokasi_kejadian',
                $lokasi
            )
                ->where('id_siswa_pelaku', $siswa->id)
                ->count();
        }

        // =============================
        // Alasan & Korban
        // =============================
        $reportReasons = [];

        foreach ($this::$indikatorBully as $bully) {
            $reportReasons[$bully] = Jawaban::with('siswa')
                ->whereRelation('angket_soals', 'indikasi_bully', $bully)
                ->where('id_siswa_pelaku', $siswa->id)
                ->get()
                ->map(function ($model) {
                    return [
                        'korban' => optional($model->siswa)->nama_lengkap,
                        'alasan' => $model->alasan
                    ];
                });
        }

        // =============================
        // Skor
        // =============================
        $skorAll = HitungSkor::hitungPelakuPerIndikator(
            $siswa->id,
            $indikator
        );

        $allValues = collect($skorAll['datasets'])
            ->pluck('data')
            ->flatten()
            ->filter(fn ($v) => $v > 0);

        $countSikap = HasilScore::where('siswa_id', $siswa->id)
            ->whereColumn('skor_korban', '<', 'skor_pelaku')
            ->count();

        return [
            'kelas'             => $siswa->kelas,
            'siswa'             => $siswa,
            'gaugeMeter'        => $gaugeMeter ?? ($allValues->count() > 0 ? $allValues->avg() : 0),
            'feedbacks'         => $feedbacks,
            'indikator'         => $indikator,
            'skorKorbanAll'     => $skorAll,
            'skorKorban'        => HitungSkor::hitungPelakuPerIndikator($siswa->id, $this::$indikatorBully),
            'skorKorbanCyber'   => HitungSkor::hitungPelakuPerIndikator($siswa->id, $this::$indikatorCiberBully),
            'locationCount'     => $locationCount,
            'reportReasons'     => $reportReasons,
            'countAsPelaku'     => $countAsPelaku,
            'countSikap'        => $countSikap,
        ];
    }

    private function getKorbanData(Siswa $siswa)
    {
        $gaugeMeter = HasilScore::where('siswa_id', $siswa->id)
            ->avg('skor_korban');

        $feedbacks = Feedback::select('id', 'feedback_deskripsi')
            ->whereIn('status', ['korban', 'netral'])
            ->where(function ($query) {
                $query->where('id_guru', auth()->user()->guru->id)
                    ->orWhere('id_guru', null);
            })
            ->get();

        $locationCount = [];

        foreach ($this::$lokasiKejadian as $lokasi) {
            $locationCount[$lokasi] = Jawaban::whereRelation('angket_soals', 'lokasi_kejadian', $lokasi)
                ->whereNotNull('id_siswa_pelaku')
                ->where('siswa_id', $siswa->id)
                ->count();
        }

        $indikator = array_merge($this::$indikatorBully, $this::$indikatorCiberBully);

        $reportReasons = [];

        foreach ($this::$indikatorBully as $bully) {
            $reportReasons[$bully] = Jawaban::with('siswapelaku')
                ->whereRelation('angket_soals', 'indikasi_bully', $bully)
                ->whereNotNull('id_siswa_pelaku')
                ->where('siswa_id', $siswa->id)
                ->get()
                ->map(function ($model) {
                    return [
                        'pelaku' => optional($model->siswapelaku)->nama_lengkap,
                        'alasan' => $model->alasan
                    ];
                });
        }

        return [
            'kelas' => $siswa->kelas,
            'siswa' => $siswa,
            'gaugeMeter' => $gaugeMeter ?? 0,
            'feedbacks' => $feedbacks,
            'indikator' => $indikator,
            'skorKorbanAll' => HitungSkor::hitungKorbanPerIndikator($siswa->id, $indikator),
            'skorKorban' => HitungSkor::hitungKorbanPerIndikator($siswa->id, $this::$indikatorBully),
            'skorKorbanCyber' => HitungSkor::hitungKorbanPerIndikator($siswa->id, $this::$indikatorCiberBully),
            'locationCount' => $locationCount,
            'reportReasons' => $reportReasons,
        ];
    }

}

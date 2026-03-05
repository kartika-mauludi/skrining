<?php
namespace App\Helpers;

use App\Models\Jawaban;
use App\Models\AngketSoal;
use Carbon\Carbon;
use App\Models\HasilScore;
use App\Models\Siswa;
use DB;

class HitungSkor
{
    /** HITUNG SAJA (TANPA DB WRITE) */
    public static function hitung(int $siswaId, int $angketId,int $kelasId, ?int $guruId = null): array
    {
        $startWeek = Carbon::now()->startOfWeek(); // Senin
        $endWeek   = Carbon::now()->endOfWeek();   // Minggu
        $countsSoalJawaban = Jawaban::where('siswa_id', $siswaId)
        ->whereHas('angket_soals', function ($q) use ($angketId, $guruId) {
            $q->where('angket_id', $angketId)
            ->where(function ($q2) use ($guruId) {
                $q2->whereNull('guru_id');
                if ($guruId) {
                    $q2->orWhere('guru_id', $guruId);
                }
            });
        })
        ->whereBetween('jawaban.created_at', [$startWeek, $endWeek])
        ->join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
        ->select('angket_soals.indikasi_siswa', DB::raw('COUNT(jawaban.id) as total'))
        ->groupBy('angket_soals.indikasi_siswa')
        ->pluck('total', 'angket_soals.indikasi_siswa');

        $sumJawabanKorban = Jawaban::where('siswa_id',$siswaId)
        ->whereHas('angket_soals', fn ($q) => $q->where('indikasi_siswa','korban'))
        ->whereBetween('jawaban.created_at', [$startWeek, $endWeek])
        ->sum('jawaban');

        $sumJawabanPelaku = Jawaban::where('siswa_id',$siswaId)
        ->whereHas('angket_soals', fn ($q) => $q->where('indikasi_siswa','pelaku'))
        ->whereBetween('jawaban.created_at', [$startWeek, $endWeek])
        ->sum('jawaban');
        
        // menghitung semua jawaban 
        $countKorban = Jawaban::where('siswa_id',$siswaId)
        ->whereNotNull('id_siswa_pelaku')
        ->whereBetween('jawaban.created_at', [$startWeek, $endWeek])
        ->count('jawaban');

        $totalSiswa = Siswa::where('kelas_id', $kelasId)->count();

        $siswaSudahIsi = Jawaban::whereBetween('created_at', [$startWeek, $endWeek])
        ->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId))
        ->distinct('siswa_id')
        ->count('siswa_id');

       if ($siswaSudahIsi >= $totalSiswa) {
            $countPelaku = 0;
        } else {
            $countPelaku = Jawaban::where('id_siswa_pelaku', $siswaId)
            ->whereBetween('created_at', [$startWeek, $endWeek])
            ->count();
        }

        $jumlahSoalPelaku = $countsSoalJawaban['pelaku'] ?? 0;
        $jumlahSoalKorban = $countsSoalJawaban['korban'] ?? 0;
        $jumlahjadiKorban = $countKorban;
        $jumlahJadiPelaku = $countPelaku;

        // rumus

        $skorPelaku =($jumlahSoalPelaku + $jumlahJadiPelaku) > 0
                    ? (($sumJawabanPelaku + $jumlahJadiPelaku *3)/(($jumlahSoalPelaku + $jumlahJadiPelaku) * 3 )) * 100 : 0;
        $skorKorban = ($jumlahSoalKorban + $jumlahjadiKorban) > 0
                    ? (($sumJawabanKorban)/(($jumlahSoalKorban*3) ?: 1)) * 100 : 0;
        
        return [
            'pelaku' => $skorPelaku,
            'korban' => $skorKorban
        ];
    }

    /** HITUNG + SIMPAN */
    public static function updateSkor($siswaId, $angketId, $kelasId, $guruId = null): void
    {
        $skor = self::hitung($siswaId, $angketId, $kelasId, $guruId);
        HasilScore::updateOrCreate(
            ['siswa_id' => $siswaId],
            [
                'angket_id' => $angketId,
                'skor_korban' => round($skor['korban'],2),
                'skor_pelaku' => round($skor['pelaku'],2),
            ]
        );
    }

    public static function createSkor($siswaId, $angketId, $kelasId, $guruId = null): void
    {
        $skor = self::hitung($siswaId, $angketId, $kelasId, $guruId);
        HasilScore::Create(
            [   'siswa_id' => $siswaId,
                'angket_id' => $angketId,
                'skor_korban' => round($skor['korban'],2),
                'skor_pelaku' => round($skor['pelaku'],2),
            ]
        );
    }

    public static function hitungKorbanPerIndikator(int $siswaId, array $indikator): array
    {
        $data = Jawaban::query()
            ->join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
            ->where('jawaban.siswa_id', $siswaId)
            ->where('angket_soals.indikasi_siswa', 'korban')
            ->selectRaw("
                YEAR(jawaban.created_at) as year,
                WEEK(jawaban.created_at, 1) as week,
                angket_soals.indikasi_bully,
                COUNT(jawaban.id) as total_soal,
                SUM(jawaban.jawaban) as total_skor
            ")
            ->groupBy('year', 'week', 'angket_soals.indikasi_bully')
            ->orderBy('year')
            ->orderBy('week')
            ->get();

        $weeks = [];
        $temp = [];

        foreach ($data as $row) {

            $weekLabel = $row->year . '-W' . str_pad($row->week, 2, '0', STR_PAD_LEFT);
            $weeks[$weekLabel] = $weekLabel;

            $maxSkor = $row->total_soal * 3;

            $persen = $maxSkor > 0
                ? ($row->total_skor / $maxSkor) * 100
                : 0;

            $temp[$weekLabel][$row->indikasi_bully] = round($persen);
        }
        
        $datasets = [];

        foreach ($weeks as $week) {

            $rowData = [];

            foreach ($indikator as $ind) {
                $rowData[] = $temp[$week][$ind] ?? 0;
            }

            $datasets[] = [
                'label' => $week,
                'data'  => $rowData
            ];
        }

        return [
            'labels'   => str_replace('_', ' ', $indikator),
            'datasets' => $datasets
        ];
    }

    public static function hitungPelakuPerIndikator(int $siswaId, array $indikator): array
    {
        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Ambil SELF ASSESSMENT (Soal Pelaku)
        |--------------------------------------------------------------------------
        */
        $soal = Jawaban::query()
            ->join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
            ->where('jawaban.siswa_id', $siswaId)
            ->where('angket_soals.indikasi_siswa', 'pelaku')
            ->selectRaw("
                YEAR(jawaban.created_at) as year,
                WEEK(jawaban.created_at, 1) as week,
                angket_soals.indikasi_bully,
                COUNT(*) as total_soal,
                SUM(jawaban.jawaban) as total_skor
            ")
            ->groupBy('year', 'week', 'angket_soals.indikasi_bully')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Ambil Aduan dari Siswa Lain
        |--------------------------------------------------------------------------
        */
        $aduan = Jawaban::query()
            ->join('angket_soals', 'jawaban.soal_id', '=', 'angket_soals.id')
            ->where('jawaban.id_siswa_pelaku', $siswaId)
            ->selectRaw("
                YEAR(jawaban.created_at) as year,
                WEEK(jawaban.created_at, 1) as week,
                angket_soals.indikasi_bully,
                COUNT(*) as total_aduan
            ")
            ->groupBy('year', 'week', 'angket_soals.indikasi_bully')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Normalisasi jadi 1 Struktur Data
        |--------------------------------------------------------------------------
        */

        $result = [];

        // ==== PROSES SOAL ====
        foreach ($soal as $row) {

            $week = $row->year . '-W' . str_pad($row->week, 2, '0', STR_PAD_LEFT);

            $maxSkor = $row->total_soal * 3;

            $persenSoal = $maxSkor > 0
                ? ($row->total_skor / $maxSkor) * 100
                : 0;

            $result[$week][$row->indikasi_bully]['soal'] = $persenSoal;
        }

        // ==== PROSES ADUAN ====
        foreach ($aduan as $row) {

            $week = $row->year . '-W' . str_pad($row->week, 2, '0', STR_PAD_LEFT);

            // contoh: 1 aduan = 20%
            $persenAduan = min($row->total_aduan * 20, 100);

            $result[$week][$row->indikasi_bully]['aduan'] = $persenAduan;
        }


        /*
        |--------------------------------------------------------------------------
        | 4️⃣ Build Dataset ChartJS
        |--------------------------------------------------------------------------
        */

        ksort($result);

        $datasets = [];

        foreach ($result as $week => $indikatorData) {

            $rowData = [];

            foreach ($indikator as $ind) {

                $skorSoal  = $indikatorData[$ind]['soal']  ?? 0;
                $skorAduan = $indikatorData[$ind]['aduan'] ?? 0;

                if ($skorSoal > 0 && $skorAduan > 0) {
                    $final = ($skorSoal + $skorAduan) / 2;
                } elseif ($skorSoal > 0) {
                    $final = $skorSoal;
                } else {
                    $final = $skorAduan;
                }

                $rowData[] = round($final);
            }

            $datasets[] = [
                'label' => $week,
                'data'  => $rowData
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | 5️⃣ Return Final Structure
        |--------------------------------------------------------------------------
        */
        return [
            'labels'   => array_map(fn($i) => str_replace('_', ' ', $i), $indikator),
            'datasets' => $datasets
        ];
    }

}



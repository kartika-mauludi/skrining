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
                    ? (($sumJawabanKorban)/($jumlahSoalKorban*3)) * 100 : 0;
        
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
}



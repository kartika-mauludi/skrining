<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\AngketSoal;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jawaban;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Str;

class ReportController extends Controller
{
    public function index(Request $request){
        // return $request;
        $data['sekolah'] = Sekolah::all();
        $data['soals'] = AngketSoal::where('guru_id',null)
                        ->whereNot('tipe_soal','keterangan')
                        ->orderBy('sequence')
                        ->get();

        if ($request->filled('kelas')) {

        $kelasIds = array_filter($request->kelas); // hilangkan null

        $bulan  = $request->bulan;
        $tahun  = $request->tahun ?? date('Y');
        $minggu = $request->minggu;

        $reports = Siswa::with(['jawaban' => function($query) use ($bulan, $tahun, $minggu) {

            if ($bulan) {

                $startMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
                $endMonth   = $startMonth->copy()->endOfMonth();

                if ($minggu) {

                    $startDate = $startMonth->copy()->addDays(($minggu - 1) * 7);
                    $endDate   = $startDate->copy()->addDays(6);

                    if ($endDate->gt($endMonth)) {
                        $endDate = $endMonth;
                    }

                    $query->whereBetween('created_at', [$startDate, $endDate]);

                } else {

                    $query->whereBetween('created_at', [$startMonth, $endMonth]);

                }
            }

        }])
        ->whereIn('kelas_id', $kelasIds) // ⬅️ pakai whereIn
        ->orderBy('no_absen')
        ->get();

        $data['kelas']   = Kelas::whereIn('id', $kelasIds)->get();
        $data['reports'] = $reports;
        $data['request'] = $request->only('sekolah', 'kelas');
    }

        // return $data;
        return view('admin.report.index',$data);
    }

    public function sekolah(Sekolah $sekolah){
        return response()->json(['data' => $sekolah->load('kelas')]);
    }

 public function exportCsv(Request $request)
{
    // return $request;
    if ($request->filled('kelas')) {

        $kelasIds = array_filter($request->kelas);
        $kelasCollection = Kelas::whereIn('id', $kelasIds)->get();

    $bulan  = $request->bulan;
    $tahun  = $request->tahun;
    $minggu = $request->minggu;

    $siswa = Siswa::with(['jawaban' => function($query) use ($bulan, $tahun, $minggu) {

        if ($bulan) {

            $startMonth = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $endMonth   = $startMonth->copy()->endOfMonth();

            if ($minggu) {

                $startDate = $startMonth->copy()->addDays(($minggu - 1) * 7);
                $endDate   = $startDate->copy()->addDays(6);

                if ($endDate->gt($endMonth)) {
                    $endDate = $endMonth;
                }

                $query->whereBetween('created_at', [$startDate, $endDate]);
            } else {
                $query->whereBetween('created_at', [$startMonth, $endMonth]);
            }
        }

    }])
    ->whereIn('kelas_id', $kelasIds)
    ->orderBy('no_absen')
    ->get();

    $soals = AngketSoal::orderBy('sequence')->whereNot('tipe_soal','keterangan')->get();

    $response = new StreamedResponse(function () use ($siswa, $soals) {

        $handle = fopen('php://output', 'w');

        // HEADER
        $header = ['#'];
        foreach ($soals as $index => $soal) {
            $header[] = $index + 1;
        }

        fputcsv($handle, $header);

        // DATA
        foreach ($siswa as $index => $row) {

            $dataRow = [
                $index + 1,
            ];

            foreach ($soals as $soal) {

                $jawaban = $row->jawaban
                    ->where('soal_id', $soal->id)
                    ->first();

                $dataRow[] = $jawaban ? $jawaban->jawaban : '-';
            }

            fputcsv($handle, $dataRow);
        }

        fclose($handle);
    });

   if ($kelasCollection->count() === 1) {

    $filename = 'report-' . 
                Str::slug($kelasCollection->first()->nama_kelas) . 
                '.csv';

}else {

    $namaGabungan = $kelasCollection
        ->pluck('nama_kelas')
        ->map(fn($nama) => Str::slug($nama))
        ->implode('-');

    $filename = 'report-' . $namaGabungan . '.csv';
}

    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', "attachment; filename=$filename");

    return $response;
}
}
}

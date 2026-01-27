<?php

namespace App\Http\Controllers;

use App\Models\Angket;
use App\Models\AngketPilihan;
use App\Models\AngketSoal;
use App\Models\Sekolah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AngketController extends Controller
{
    public function index()
    {
        $data['sekolahs'] = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->get();

        if (request()->ajax()) {
            $data = Angket::with('sekolah', 'kelas')
            ->whereRelation('sekolah', 'guru_id', auth()->user()->guru->id)
            ->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.angket.index', $data);
    }

    public function store(Request $request)
    {
        try{
            DB::transaction(function () use ($request){
                $angket = Angket::create([
                    'nama_angket' => $request->nama_angket,
                    'sekolah_id'  => $request->sekolah_id,
                    'kelas_id'    => $request->kelas_id,
                    'akses_token' => Str::random(5)
                ]);

                foreach ($request->soal as $i => $s) {
                    $soal = AngketSoal::create([
                        'angket_id' => $angket->id,
                        'sequence'  => $i + 1,
                        'soal' => $s['pertanyaan'],
                        'tipe_soal' => $s['tipe'],
                        'bobot' => $s['bobot']
                    ]);

                    if (in_array($s['tipe'], ['radio', 'checkbox'])) {
                        foreach ($s['opsi'] as $opsi) {
                            AngketPilihan::create([
                                'angket_soal_id' => $soal->id,
                                'label' => $opsi['label'],
                                'nilai' => $opsi['nilai']
                            ]);
                        }
                    }
                }
            });

            $message = 'Data angket berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.angket.index')->with('message', $message);
    }
}

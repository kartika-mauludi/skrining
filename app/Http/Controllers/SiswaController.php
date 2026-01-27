<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Exception;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::where('guru_id', auth()->user()->guru->id)
        ->pluck('id')
        ->toArray();

        $kelas = Kelas::whereIn('sekolah_id', $sekolah)
        ->get();

        if (request()->ajax()) {
            $data = Siswa::whereIn('kelas_id', $kelas->pluck('id'))
            ->with('kelas')
            ->orderBy('no_absen')->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.siswa.index', ['kelas' => $kelas]);
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');

        try{
            Siswa::create($input);
            $message = 'Data siswa berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.siswa.index')->with('message', $message);
    }
}

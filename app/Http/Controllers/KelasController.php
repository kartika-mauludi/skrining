<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->filled('sekolah_id')) {
            return abort(404);
        }

        if (request()->ajax()) {
            $data = Kelas::where('sekolah_id', $request->sekolah_id)
            ->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.kelas.index')->with('sekolah_id', $request->sekolah_id);
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        $input['akses_token'] = Str::random(6);

        try{
            Kelas::create($input);
            $message = 'Data kelas berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.kelas.index', ['sekolah_id' => $request->sekolah_id])->with('message', $message);
    }

    public function edit(Kelas $kelas)
    {
        return response()->json([
            'data' => $kelas
        ]);
    }

    public function update(Request $request,Kelas $kelas)
    {
        $input = $request->except('_token');

        try{
            $kelas->update($input);
            $message = 'Data kelas berhasil diperbarui';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.kelas.index', ['sekolah_id' => $request->sekolah_id])->with('message', $message);
    }

    public function destroy(Kelas $kelas)
    {
        try{
            $kelas->delete();
            $message = 'Data kelas berhasil dihapus';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }
        
        return response()->json([
            'message' => $message
        ]);
    }

    public function token(Kelas $kelas)
    {
        $guru = Guru::find(auth()->user()->guru->id);
        $token  = strtoupper(Str::random(6));

        // Ambil data JSON → pastikan array
        $tokens = $kelas->data_akses ?? [];

        $found = false;

        foreach ($tokens as &$item) {
            if ($item['guru_id'] == $guru->id) {
                $item['token'] = $token;
                $item['nama_guru'] = $guru->nama_lengkap;
                $found = true;
                break;
            }
        }
        unset($item); // best practice

        // Jika guru belum ada → tambahkan
        if (!$found) {
            $tokens[] = [
                'guru_id' => $guru->id,
                'token'   => $token,
                'nama_guru' => $guru->nama_lengkap
            ];
        }

        try {
            $kelas->update([
                'data_akses' => $tokens
            ]);

            $message = 'Token berhasil diperbarui';
        } catch (Exception $e) {
            report($e);
            $message = 'Gagal memperbarui token';
        }

        return response()->json([
            'message' => $message,
            'data'    => $kelas->fresh()
        ]);
    }
}

TODO: "GANTI ANGKET MENJADI LANGSUNG SOAL UNTUK GURU, AGAR BISA MENAMBAH";
TODO: "GANTI ANGKET MENJADI LANGSUNG SOAL UNTUK GURU, AGAR BISA MENAMBAH TANPA BISA MENGHAPUS ANGKET";
TODO: "CEK TAMBAHAN FEEDBACK DAN SOAL DARI GURU";
<?php

namespace App\Http\Controllers;

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
        $input['akses_token'] = Str::random(6);

        try{
            $kelas->update($input);
            $message = 'Token berhasil diperbarui';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return response()->json([
            'message' => $kelas
        ]);
    }
}

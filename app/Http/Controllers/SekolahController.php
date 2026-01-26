<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Sekolah::where('guru_id', auth()->user()->guru->id)
            ->withCount('kelas')
            ->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.sekolah.index');
    }

    public function store(Request $request)
    {
        $input = $request->except('_token');
        $input['guru_id'] = auth()->user()->guru->id;

        try{
            if ($request->has('logo')) {
                $file = $request->file('logo');
                $file->getClientOriginalName();
                $input['logo'] = $file->storePubliclyAs('logo', time() . "." . $file->getClientOriginalExtension(), 'public');
            }

            Sekolah::create($input);
            $message = 'Data sekolah berhasil dibuat';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.sekolah.index')->with('message', $message);
    }

    public function edit(Sekolah $sekolah)
    {
        return response()->json([
            'data' => $sekolah
        ]);
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        $input = $request->except('_token', '_method');

        try{
            if ($request->has('logo')) {
                $file = $request->file('logo');
                $file->getClientOriginalName();
                if (Storage::disk('public')->exists($sekolah->logo)) {
                    Storage::disk('public')->delete($sekolah->logo ?? '');
                }

                $input['logo'] = $file->storePubliclyAs('logo', time() . "." . $file->getClientOriginalExtension(), 'public');
            }

            $sekolah->update($input);
            $message = 'Data sekolah berhasil diperbarui';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }

        return redirect()->route('guru.sekolah.index')->with('message', $message);
    }

    public function destroy(Sekolah $sekolah)
    {
        try{
            $sekolah->delete();
            $message = 'Data sekolah berhasil dihapus';
        }catch(Exception $x){
            report($x);
            $message = $x->getMessage();
        }
        
        return response()->json([
            'message' => $message
        ]);
    }
}

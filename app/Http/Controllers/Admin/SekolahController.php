<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sekolah;
use Exception;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        

        return view('admin.sekolah.index');
    }

    public function data(){
      
            $data = Sekolah::all();
            return response()->json([
                'data' => $data
            ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('admin.sekolah.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sekolah $sekolah)
    {
         return response()->json(['data' => $sekolah->load('kelas')]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sekolah $sekolah)
    {
        return response()->json([
            'data' => $sekolah
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('admin.sekolah.index')->with('message', $message);
   
    }

    /**
     * Remove the specified resource from storage.
     */
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

<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Angket;

class AngketController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Angket::all();

            return response()->json([
                'data' => $data
            ]);
        }

        return view('guru.angket.index');
    }
}

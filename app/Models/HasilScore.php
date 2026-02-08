<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilScore extends Model
{
    protected $fillable =[
        'siswa_id',
        'angket_id',
        'skor'
    ];

    protected $table ="hasil_scores";
}

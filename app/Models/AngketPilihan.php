<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketPilihan extends Model
{
    protected $fillable = [
        'angket_soal_id',
        'label',
        'nilai',
        'sequence'
    ];
}

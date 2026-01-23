<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketSoal extends Model
{
    protected $fillable = [
        'angket_id',
        'sequence',
        'soal',
        'tipe_soal',
        'detail_tipe_soal',
        'bobot'
    ];

    public function angket()
    {
        return $this->belongsTo(Angket::class);
    }
}

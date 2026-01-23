<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketHasilScore extends Model
{
    protected $fillable = [
        'angket_hasil_id',
        'soal',
        'jawaban',
        'score'
    ];

    public function angketHasil()
    {
        return $this->belongsTo(AngketHasil::class);
    }
}

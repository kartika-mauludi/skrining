<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketSoal extends Model
{
    protected $fillable = [
        'angket_id',
        'sekolah_id',
        'guru_id',
        'sequence',
        'soal',
        'tipe_soal',
        'indikasi_siswa',
        'lokasi_kejadian',
        'bobot'
    ];

    public function angket()
    {
        return $this->belongsTo(Angket::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $fillable =
    [
        'soal_id',
        'siswa_id',
        'jawaban',
        'alasan',
        'skor',
    ];

    protected $table = 'jawaban';

    public function angket()
    {
        return $this->belongsTo(Angket::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function soal(){
        return $this->belongsTo(AngketSoal::class);
    }
}

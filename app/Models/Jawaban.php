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
        'id_siswa_pelaku'
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
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function siswapelaku()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa_pelaku');
    }
}

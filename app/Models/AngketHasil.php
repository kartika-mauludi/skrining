<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketHasil extends Model
{
    protected $fillable = [
        'angket_id',
        'sekolah_id',
        'kelas_id',
        'siswa_id',
        'datetime',
        'indikasi_siswa',
        'level_bullying',
        'lokasi_kejadian',
        'lokasi_kejadian_tambahan'
    ];

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

    public function angketHasilScore()
    {
        return $this->hasMany(AngketHasilScore::class);
    }
}

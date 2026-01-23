<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angket extends Model
{
    protected $fillable = [
        'sekolah_id',
        'kelas_id',
        'nama_angket',
        'akses_token',
        'owner'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function angketSoal()
    {
        return $this->hasMany(AngketSoal::class);
    }

    public function angketHasil()
    {
        return $this->hasMany(AngketHasil::class);
    }
}

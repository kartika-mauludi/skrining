<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = [
        'sekolah_id',
        'nama_kelas'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function angket()
    {
        return $this->hasMany(Angket::class);
    }

    public function angketHasil()
    {
        return $this->hasMany(AngketHasil::class);
    }
}

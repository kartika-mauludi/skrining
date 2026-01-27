<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $fillable = [
        'guru_id',
        'nama_sekolah',
        'no_tlp',
        'alamat_lengkap',
        'logo'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
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

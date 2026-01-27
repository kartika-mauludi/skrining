<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'kelas_id',
        'no_absen',
        'nis',
        'nama_lengkap',
        'tgl_lahir',
        'tempat_lahir',
        'alamat',
        'nama_wali',
        'no_tlp_wali'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function angketHasil()
    {
        return $this->hasMany(AngketHasil::class);
    }
}

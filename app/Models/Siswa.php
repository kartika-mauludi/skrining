<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'kelas_id',
        'no_absen',
        'nis',
        'nama_lengkap',
        'jk',
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

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'nip',
        'nama_lengkap',
        'tgl_lahir',
        'tempat_lahir',
        'alamat',
        'no_tlp'
    ] ;

    public function sekolah()
    {
        return $this->hasMany(Sekolah::class);
    }
}

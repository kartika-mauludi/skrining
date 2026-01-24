<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $fillable = [
        'user_id',
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

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::deleting(function ($guru) {
        if ($guru->user) {
            $guru->user->delete();
        }
    });
}
}

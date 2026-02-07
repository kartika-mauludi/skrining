<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'feedback_deskripsi',
        'status',
        'id_guru'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');    
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = [
        'no_tlp',
        'email'
    ];

    protected $table = "pengaturan";
}

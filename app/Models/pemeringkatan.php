<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pemeringkatan extends Model
{
     protected $fillable = [
        'id',
        'name'
    ];

    protected $table = "ranks";

    public function indikator(){
        return $this->hasMany(Indikatorperingkat::class);
    }
    
}

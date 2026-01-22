<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikatorperingkat extends Model
{
     protected $fillable = [
        'id',
        'rank_id',
        'name'
    ];

    protected $table = 'indikator_rank';


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPeringkat extends Model
{
    protected $fillable = [
        'rank_id',
        'indikator_id',
        'rank_dunia',
        'rank_nasional',
        'rank_jatim',
        'rank_surabaya',
        'tingkat',
    ];

    protected $table = 'data_rank';

    public function Periode(){
        return $this->belongsTo(Periode::class);
    }
}

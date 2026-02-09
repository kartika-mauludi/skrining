<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilScore extends Model
{
  protected $fillable =[
        "siswa_id",
        "angket_id",
        "skor_korban",
        "skor_pelaku"
    ];

    protected $table ="hasil_scores";

      public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}

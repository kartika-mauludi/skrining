<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AngketSoal extends Model
{
    protected $fillable = [
        'angket_id',
        'sekolah_id',
        'guru_id',
        'sequence',
        'soal',
        'tipe_soal',
        'indikasi_siswa',
        'lokasi_kejadian',
        'bobot',
        'indikasi_bully'
    ];

    public function angket()
    {
        return $this->belongsTo(Angket::class);
    }

     public static function cleanSummernote(?string $html): ?string
    {
        if (!$html) {
            return null;
        }

        $html = trim($html);

        // dianggap kosong
        if (in_array($html, ['', '<p><br></p>', '<p></p>'])) {
            return null;
        }

        // hapus <p> pembungkus tunggal saja
        if (preg_match('/^<p>(.*?)<\/p>$/s', $html)) {
            $html = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $html);
        }

        return $html;
    }
}

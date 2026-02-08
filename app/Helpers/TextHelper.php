<?php

namespace App\Helpers;

class TextHelper
{
    public static function cleanSummernote(?string $html): ?string
    {
        if (!$html) return null;

        $html = trim($html);

        // dianggap kosong oleh kita
        if (in_array($html, ['', '<p></p>', '<p><br></p>'])) {
            return null;
        }

        // buang <p> pembungkus
        if (preg_match('/^<p>(.*?)<\/p>$/s', $html)) {
            $html = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $html);
        }

        return $html;
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slugifier
{
    /** Кириллица → латиница для slug. */
    private const MAP = [
        'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh',
        'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
        'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts',
        'ч'=>'ch','ш'=>'sh','щ'=>'shch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
        // узбекские
        'ў'=>'oʻ','ғ'=>'gʻ','қ'=>'q','ҳ'=>'h',
    ];

    public static function make(?string $text, int $maxLength = 80): string
    {
        if (!$text) return '';

        $text = mb_strtolower(trim($text));

        $text = strtr($text, self::MAP);
        $text = strtr($text, array_map('mb_strtoupper', self::MAP));

        // Str::slug сделает финальную очистку (пробелы → дефисы, удалит лишние символы)
        $slug = Str::slug($text, '-');

        return mb_substr($slug, 0, $maxLength);
    }
}

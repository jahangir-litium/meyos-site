<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Минифицирует themes.css и main.js → .min.css/.min.js
 * Запускать перед деплоем:  php artisan meyos:minify
 */
class MinifyAssets extends Command
{
    protected $signature = 'meyos:minify';
    protected $description = 'Минификация themes.css и main.js для prod';

    public function handle(): int
    {
        $publicDir = public_path('assets');

        $css = file_get_contents($publicDir . '/css/themes.css');
        $minCss = $this->minifyCss($css);
        file_put_contents($publicDir . '/css/themes.min.css', $minCss);
        $this->info('CSS: ' . strlen($css) . ' → ' . strlen($minCss) . ' bytes ('
            . round((1 - strlen($minCss) / strlen($css)) * 100) . '% сжатие)');

        $js = file_get_contents($publicDir . '/js/main.js');
        $minJs = $this->minifyJs($js);
        file_put_contents($publicDir . '/js/main.min.js', $minJs);
        $this->info('JS:  ' . strlen($js) . ' → ' . strlen($minJs) . ' bytes ('
            . round((1 - strlen($minJs) / strlen($js)) * 100) . '% сжатие)');

        return self::SUCCESS;
    }

    private function minifyCss(string $css): string
    {
        // Удалить комментарии /* ... */
        $css = preg_replace('!/\*.*?\*/!s', '', $css);
        // Удалить лишние пробелы вокруг символов
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/\s*([{}:;,>+~])\s*/', '$1', $css);
        // Удалить trailing ; в блоке
        $css = str_replace(';}', '}', $css);
        return trim($css);
    }

    private function minifyJs(string $js): string
    {
        // ОСТОРОЖНО: regex-минификатор. Для серьёзного prod использовать terser/esbuild.
        // Здесь — лайтовая обработка: убрать однострочные комментарии и лишние пробелы.
        $js = preg_replace('!/\*.*?\*/!s', '', $js);
        // Удалить //-комментарии (но не внутри строк — упрощённая эвристика)
        $js = preg_replace('!^\s*//.*$!m', '', $js);
        // Многократные пустые строки → одна
        $js = preg_replace("/\n\s*\n+/", "\n", $js);
        return trim($js);
    }
}

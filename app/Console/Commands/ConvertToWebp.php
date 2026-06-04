<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Конвертирует все JPG/PNG в storage/app/public в WebP.
 * Оригиналы остаются — picture/source в blade подменяет на webp при поддержке.
 *
 * Запуск:  php artisan meyos:webp
 * Можно крон-задачей раз в час: чтобы новые загрузки тоже получали webp.
 */
class ConvertToWebp extends Command
{
    protected $signature = 'meyos:webp {--force : Перегенерировать существующие .webp}';
    protected $description = 'Конвертировать JPG/PNG в WebP для ускорения загрузки';

    public function handle(): int
    {
        if (!function_exists('imagewebp')) {
            $this->error('Расширение GD без webp-поддержки. Установите libwebp на сервере.');
            return self::FAILURE;
        }

        $disk = Storage::disk('public');
        $files = $disk->allFiles();
        $images = array_filter($files, fn ($f) => preg_match('/\.(jpe?g|png)$/i', $f));

        $converted = 0; $skipped = 0; $failed = 0;
        $totalBefore = 0; $totalAfter = 0;

        foreach ($images as $file) {
            $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

            if (!$this->option('force') && $disk->exists($webpPath)) {
                $skipped++;
                continue;
            }

            $absSrc = $disk->path($file);
            $absDst = $disk->path($webpPath);
            $totalBefore += filesize($absSrc) ?: 0;

            try {
                $info = @getimagesize($absSrc);
                if (!$info) { $failed++; continue; }

                $im = match ($info[2]) {
                    IMAGETYPE_JPEG => @imagecreatefromjpeg($absSrc),
                    IMAGETYPE_PNG  => @imagecreatefrompng($absSrc),
                    default        => null,
                };
                if (!$im) { $failed++; continue; }

                // Сохраняем прозрачность для PNG
                if ($info[2] === IMAGETYPE_PNG) {
                    imagepalettetotruecolor($im);
                    imagealphablending($im, true);
                    imagesavealpha($im, true);
                }

                imagewebp($im, $absDst, 82); // 82 — оптимальный баланс размер/качество
                imagedestroy($im);

                $totalAfter += filesize($absDst) ?: 0;
                $converted++;
            } catch (\Throwable $e) {
                $failed++;
                $this->warn("$file: " . $e->getMessage());
            }
        }

        $saved = $totalBefore - $totalAfter;
        $pct = $totalBefore > 0 ? round($saved / $totalBefore * 100) : 0;

        $this->info("Сконвертировано: $converted | Пропущено: $skipped | Ошибок: $failed");
        $this->info("Экономия: " . round($saved / 1024) . " КБ ($pct%)");

        return self::SUCCESS;
    }
}

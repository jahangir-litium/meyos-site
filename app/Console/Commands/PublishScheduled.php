<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

/**
 * Не «публикует» в смысле SQL (scope News::published() уже учитывает published_at <= now),
 * а сбрасывает кэш страниц, чтобы свежеопубликованная новость появилась без задержки до 5 минут.
 *
 * Запускается раз в минуту через scheduler.
 */
class PublishScheduled extends Command
{
    protected $signature = 'meyos:publish-scheduled';
    protected $description = 'Прогрев кэша для постов, у которых наступило время публикации';

    public function handle(): int
    {
        // Новости, которые попали в окно [now-2min .. now] и опубликованы
        $count = News::where('is_published', true)
            ->whereBetween('published_at', [now()->subMinutes(2), now()])
            ->count();

        if ($count > 0) {
            foreach (['ru', 'uz', 'en'] as $locale) {
                Cache::forget("page:home:$locale");
            }
            Cache::forget('sitemap.xml');
            $this->info("Published $count scheduled posts — page cache flushed.");
        }

        return self::SUCCESS;
    }
}

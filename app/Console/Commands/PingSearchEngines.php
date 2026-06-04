<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * Раз в сутки пингует Google/Bing/Yandex чтобы они перечитали sitemap.xml.
 * Запуск из routes/console.php: Schedule::command('meyos:ping-sitemap')->daily();
 */
class PingSearchEngines extends Command
{
    protected $signature = 'meyos:ping-sitemap';
    protected $description = 'Уведомить Google/Bing/Yandex о новом sitemap.xml';

    public function handle(): int
    {
        $sitemap = url('/sitemap.xml');
        $engines = [
            'Google' => 'https://www.google.com/ping?sitemap='.urlencode($sitemap),
            'Bing'   => 'https://www.bing.com/ping?sitemap='.urlencode($sitemap),
            'Yandex' => 'https://blogs.yandex.ru/pings/?status=success&url='.urlencode($sitemap),
        ];

        foreach ($engines as $name => $url) {
            try {
                $res = Http::timeout(8)->get($url);
                $this->info("$name: HTTP " . $res->status());
            } catch (\Throwable $e) {
                $this->warn("$name: ошибка — " . $e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}

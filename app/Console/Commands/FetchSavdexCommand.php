<?php

namespace App\Console\Commands;

use App\Services\SavdexParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Запуск парсера SAVDEX из CLI/крона/UI-кнопки.
 *
 *   php artisan meyos:fetch-savdex
 *
 * Используется:
 *  - Scheduler: routes/console.php (ежедневно в 05:30)
 *  - UI-кнопка «Обновить сейчас» в админке: запускается через Symfony Process
 *    в отдельном child-процессе, чтобы не блокировать веб-реквест.
 */
class FetchSavdexCommand extends Command
{
    protected $signature   = 'meyos:fetch-savdex {--prune-days=60 : удалять записи старше N дней}';
    protected $description = 'Парсит savdex.uz и обновляет SavdexListing (мебельные объявления)';

    public function handle(SavdexParser $parser): int
    {
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        $this->info('Старт парсинга savdex.uz…');
        $stats = $parser->sync();

        $pruned = $parser->pruneExpired((int) $this->option('prune-days'));
        $stats['pruned'] = $pruned;

        Log::info('Savdex sync complete (artisan)', $stats);

        $this->info(sprintf(
            'Готово. Найдено в sitemap: %d · загружено: %d · создано: %d · обновлено: %d · ошибок: %d · удалено старых: %d',
            $stats['found_in_sitemap'] ?? 0,
            $stats['fetched'] ?? 0,
            $stats['created'] ?? 0,
            $stats['updated'] ?? 0,
            $stats['errors'] ?? 0,
            $pruned
        ));

        return self::SUCCESS;
    }
}

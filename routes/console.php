<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/* ============ Расписание задач ============
 * Чтобы это работало на проде, добавьте в crontab:
 *   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
 */
Schedule::command('meyos:publish-scheduled')->everyMinute()->withoutOverlapping();
Schedule::command('meyos:ping-sitemap')->dailyAt('03:00')->onOneServer();
Schedule::command('meyos:recalc-partner-stats')->hourly()->onOneServer();

// Раз в сутки парсим мебельные объявления с savdex.uz. Длится 30-60 секунд
// (24-30 запросов) — можно запускать через очередь (dispatch), либо
// синхронно командой — ниже синхронный вариант через Job.
Schedule::job(new \App\Jobs\FetchSavdexListingsJob())->dailyAt('05:30')->onOneServer();


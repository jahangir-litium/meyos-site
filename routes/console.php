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


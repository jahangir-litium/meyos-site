<?php

namespace App\Jobs;

use App\Services\SavdexParser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchSavdexListingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries   = 2;

    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(SavdexParser $parser): void
    {
        // Снимаем PHP time-limit: парсер идёт в 24-30 HTTP-запросов с паузой 300-500ms,
        // это 20-60 секунд. Если job запущен через dispatchAfterResponse() (sync queue),
        // лимит PHP для web-реквеста = 30s по умолчанию, этого недостаточно.
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        $stats = $parser->sync();
        $pruned = $parser->pruneExpired(60);
        Log::info('Savdex sync complete', $stats + ['pruned' => $pruned]);
    }
}

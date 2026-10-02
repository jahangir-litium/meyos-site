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

    /** Backoff между попытками: 60с → 300с. */
    public function backoff(): array
    {
        return [60, 300];
    }

    public function handle(SavdexParser $parser): void
    {
        $stats = $parser->sync();
        $pruned = $parser->pruneExpired(60);
        Log::info('Savdex sync complete', $stats + ['pruned' => $pruned]);
    }
}

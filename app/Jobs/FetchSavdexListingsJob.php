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

    public int $timeout = 300;
    public int $tries   = 1;

    public function handle(SavdexParser $parser): void
    {
        $stats = $parser->sync();
        $pruned = $parser->pruneExpired(60);
        Log::info('Savdex sync complete', $stats + ['pruned' => $pruned]);
    }
}

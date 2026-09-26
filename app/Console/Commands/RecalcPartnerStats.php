<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Models\PartnerView;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalcPartnerStats extends Command
{
    protected $signature = 'meyos:recalc-partner-stats';
    protected $description = 'Пересчёт views_count_30d партнёров + очистка старых записей partner_views';

    public function handle(): int
    {
        // 1) Пересчёт 30-дневного счётчика для всех партнёров
        $counts = PartnerView::query()
            ->where('viewed_at', '>=', now()->subDays(30))
            ->select('partner_id', DB::raw('COUNT(*) as c'))
            ->groupBy('partner_id')
            ->pluck('c', 'partner_id');

        // Сначала обнулим у всех, затем проставим актуальные
        Partner::query()->update(['views_count_30d' => 0]);
        foreach ($counts as $pid => $c) {
            Partner::where('id', $pid)->update(['views_count_30d' => $c]);
        }

        // 2) Чистка сырых записей старше 180 дней (счётчик total уже денормализован)
        $deleted = PartnerView::where('viewed_at', '<', now()->subDays(180))->delete();

        $this->info('Пересчитано партнёров: '.$counts->count().'. Удалено старых записей: '.$deleted);

        return self::SUCCESS;
    }
}

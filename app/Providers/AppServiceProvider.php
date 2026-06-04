<?php

namespace App\Providers;

use App\Observers\PageCacheObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Инвалидация кэша страниц при изменении контента в админке
        $cachedModels = [
            \App\Models\Page::class,
            \App\Models\Benefit::class,
            \App\Models\BusinessCase::class,
            \App\Models\PainSolutionRow::class,
            \App\Models\Program::class,
            \App\Models\ProgramBlock::class,
            \App\Models\ProgramAdvantage::class,
            \App\Models\TaxRow::class,
            \App\Models\JoinStep::class,
            \App\Models\Partner::class,
            \App\Models\Event::class,
            \App\Models\News::class,
            \App\Models\Faq::class,
            \App\Models\TimelineItem::class,
            \App\Models\TeamMember::class,
            \App\Models\Certification::class,
            \App\Models\Document::class,
            \App\Models\Setting::class,
        ];

        foreach ($cachedModels as $model) {
            if (class_exists($model)) {
                $model::observe(PageCacheObserver::class);
            }
        }
    }
}

<?php

namespace App\Providers;

use App\Observers\PageCacheObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // КРИТИЧНО для FilePond/Filament: Livewire генерирует preview URL для temporary
        // uploads через route() → берёт APP_URL. Если APP_URL=http://localhost:8000,
        // а сервер слушает 127.0.0.1:8000 (или другой hostname) → preview URL
        // ведёт на разный origin → CORS-блок → «Загрузка файла…» висит навсегда.
        //
        // Решение: на каждом HTTP-запросе подменяем root URL на тот, по которому
        // реально пришёл запрос. Это решает локальный dev + любые reverse-proxy
        // на проде (когда APP_URL не угадан) автоматически.
        if (request()->server('HTTP_HOST')) {
            $scheme = request()->isSecure() ? 'https' : 'http';
            URL::forceRootUrl($scheme . '://' . request()->getHttpHost());
            if ($scheme === 'https') {
                URL::forceScheme('https');
            }
        }

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

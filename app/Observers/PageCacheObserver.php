<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Сбрасывает Cache::remember для страниц при изменении контента.
 * Регистрируется в AppServiceProvider для нужных моделей.
 */
class PageCacheObserver
{
    public function saved($model): void { $this->flush(); }
    public function deleted($model): void { $this->flush(); }

    private function flush(): void
    {
        foreach (['ru', 'uz', 'en'] as $locale) {
            Cache::forget("page:home:$locale");
            Cache::forget("page:about:$locale");
            Cache::forget("page:residency:$locale");
            Cache::forget("page:programs:$locale");
            Cache::forget("page:partners:$locale");
        }
    }
}

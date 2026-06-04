<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

/**
 * Универсальная справочная таблица категорий: news / partners / events.
 * Используется вместо хардкоженных const CATEGORIES в моделях.
 */
class Category extends Model
{
    use HasTranslations, HasSorting, SoftDeletes;

    public const TYPE_NEWS     = 'news';
    public const TYPE_PARTNERS = 'partners';
    public const TYPE_EVENTS   = 'events';

    public const TYPES = [
        self::TYPE_NEWS     => 'Новости',
        self::TYPE_PARTNERS => 'Партнёры',
        self::TYPE_EVENTS   => 'Мероприятия',
    ];

    protected $fillable = ['type', 'slug', 'name', 'sort', 'is_published'];
    public array $translatable = ['name'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeOfType($q, string $type) { return $q->where('type', $type); }

    /**
     * Карта slug → локализованное имя для указанного типа.
     * Кэшируется на 1 час.
     */
    public static function map(string $type, ?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        return Cache::remember("categories:$type:$locale", 3600, function () use ($type, $locale) {
            return self::published()
                ->ofType($type)
                ->orderBy('sort')
                ->orderBy('id')
                ->get()
                ->mapWithKeys(fn ($c) => [
                    $c->slug => $c->getTranslation('name', $locale, false) ?: $c->getTranslation('name', 'ru', false) ?: $c->slug,
                ])
                ->toArray();
        });
    }

    public static function flushCache(): void
    {
        foreach (['news', 'partners', 'events'] as $type) {
            foreach (['ru', 'uz', 'en'] as $locale) {
                Cache::forget("categories:$type:$locale");
            }
        }
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::flushCache());
        static::deleted(fn () => self::flushCache());
    }
}

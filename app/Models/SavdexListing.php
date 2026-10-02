<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Объявление с savdex.uz, сохранённое локально парсером.
 * Не редактируемая вручную модель — обновляется из SavdexParser.
 * Админ может только скрыть неподходящее (is_hidden) или добавить
 * в избранное (is_featured) для показа на главной.
 */
class SavdexListing extends Model
{
    protected $fillable = [
        'external_id', 'slug', 'title', 'summary',
        'listing_type', 'price', 'city', 'country',
        'image_url', 'source_url', 'tags',
        'published_at', 'expires_at', 'fetched_at',
        'is_hidden', 'is_featured', 'is_published',
    ];

    protected $casts = [
        'tags'          => 'array',
        'published_at'  => 'date',
        'expires_at'    => 'date',
        'fetched_at'    => 'datetime',
        'is_hidden'     => 'boolean',
        'is_featured'   => 'boolean',
        'is_published'  => 'boolean',
    ];

    public const TYPES = [
        'demand' => 'Запрос',
        'offer'  => 'Предложение',
        'tender' => 'Тендер',
    ];

    public static function allTypes(?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        return [
            'ru' => self::TYPES,
            'uz' => ['demand' => 'Soʻrov', 'offer' => 'Taklif', 'tender' => 'Tender'],
            'en' => ['demand' => 'Request', 'offer' => 'Offer', 'tender' => 'Tender'],
        ][$locale] ?? self::TYPES;
    }

    public function scopeVisible($q)
    {
        return $q->where('is_published', true)->where('is_hidden', false);
    }

    public function scopeFresh($q, int $daysAgo = 60)
    {
        return $q->where(function ($w) use ($daysAgo) {
            $w->whereNull('expires_at')
              ->orWhere('expires_at', '>=', now()->subDays($daysAgo));
        });
    }

    public function scopeFeatured($q) { return $q->where('is_featured', true); }
}

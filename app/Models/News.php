<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'slug', 'category', 'published_at', 'is_featured', 'is_published',
        'title', 'preview', 'content', 'image_alt', 'cover_image', 'sort',
        // SEO per-record
        'seo_title', 'seo_description', 'seo_image',
        // Галерея и CTA
        'gallery_images', 'cta_text', 'cta_url', 'cta_event_id',
    ];

    public array $translatable = [
        'title', 'preview', 'content', 'image_alt',
        'seo_title', 'seo_description', 'cta_text',
    ];

    protected $casts = [
        'published_at'   => 'date',
        'is_featured'    => 'boolean',
        'is_published'   => 'boolean',
        'gallery_images' => 'array',
    ];

    /** Fallback: используется только если БД-категорий нет (пустая Category-таблица). */
    public const CATEGORIES = [
        'residency'  => 'Резидентство',
        'export'     => 'Экспорт',
        'edujob'     => 'EduJob',
        'regulation' => 'Регулирование',
        'programs'   => 'Программы',
    ];

    /**
     * Полная карта slug → название для текущей локали.
     * Сначала пробует БД (Category), если пусто — отдаёт хардкод-fallback.
     */
    public static function allCategories(?string $locale = null): array
    {
        $fromDb = Category::map(Category::TYPE_NEWS, $locale);
        return !empty($fromDb) ? $fromDb : self::CATEGORIES;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true)->where('published_at', '<=', now()); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }

    /** Связанное мероприятие для CTA-кнопки (опционально) */
    public function ctaEvent()
    {
        return $this->belongsTo(Event::class, 'cta_event_id');
    }

    /** Резолвит финальный URL для CTA-кнопки: либо ручной URL, либо ссылка на мероприятие */
    public function getCtaResolvedUrlAttribute(): ?string
    {
        if (!empty($this->cta_url)) {
            return $this->cta_url;
        }
        if ($this->cta_event_id && $this->ctaEvent && $this->ctaEvent->slug) {
            return route('events.show', $this->ctaEvent->slug);
        }
        return null;
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Event extends Model implements HasMedia
{
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'slug', 'category', 'event_date', 'end_date', 'start_time', 'end_time',
        'city', 'location', 'is_featured', 'is_published',
        'title', 'preview', 'description', 'image_alt', 'cover_image', 'expected_attendees', 'sort',
        'seo_title', 'seo_description', 'seo_image',
    ];

    public array $translatable = [
        'city', 'location', 'title', 'preview', 'description', 'image_alt',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'event_date'   => 'date',
        'end_date'     => 'date',
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
    ];

    /** Fallback на случай пустой БД-таблицы Category. */
    public const CATEGORIES = [
        'exhibition' => 'Выставка',
        'forum'      => 'Форум',
        'workshop'   => 'Мастер-класс',
        'conference' => 'Конференция',
        'meeting'    => 'Встреча',
    ];

    public static function allCategories(?string $locale = null): array
    {
        $fromDb = Category::map(Category::TYPE_EVENTS, $locale);
        return !empty($fromDb) ? $fromDb : self::CATEGORIES;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function registrations() { return $this->hasMany(EventRegistration::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeUpcoming($q)  { return $q->where('event_date', '>=', now()->toDateString()); }
}

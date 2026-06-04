<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Partner extends Model implements HasMedia
{
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes;

    public string $autoSlugFrom = 'name';

    protected $fillable = [
        'slug', 'category', 'name', 'description', 'logo_text', 'logo_image', 'website_url',
        'registry_id', 'is_published', 'show_on_home', 'sort',
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'is_published' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    /** Fallback на случай пустой БД-таблицы Category. */
    public const CATEGORIES = [
        'manufacturer' => 'Производитель',
        'designer'     => 'Дизайн-студия',
        'supplier'     => 'Поставщик',
        'logistics'    => 'Логистика и розница',
        'other'        => 'Другое',
    ];

    public static function allCategories(?string $locale = null): array
    {
        $fromDb = Category::map(Category::TYPE_PARTNERS, $locale);
        return !empty($fromDb) ? $fromDb : self::CATEGORIES;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeOnHome($q)    { return $q->where('show_on_home', true); }
}

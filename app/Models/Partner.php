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
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes, \App\Models\Concerns\LogsChanges;

    public string $autoSlugFrom = 'name';

    protected $fillable = [
        'slug', 'category', 'region', 'founded_year',
        'name', 'description', 'about',
        'logo_text', 'logo_image', 'website_url',
        'gallery_images', 'socials', 'contact_email', 'contact_phone',
        'seo_title', 'seo_description', 'seo_image',
        'registry_id', 'is_published', 'show_on_home', 'sort',
        'views_count_total', 'views_count_30d', 'last_viewed_at',
    ];

    public array $translatable = ['name', 'description', 'about', 'seo_title', 'seo_description'];

    protected $casts = [
        'is_published'      => 'boolean',
        'show_on_home'      => 'boolean',
        'gallery_images'    => 'array',
        'socials'           => 'array',
        'last_viewed_at'    => 'datetime',
    ];

    /** Fallback на случай пустой БД-таблицы Category. */
    public const CATEGORIES = [
        'manufacturer' => 'Производитель',
        'designer'     => 'Дизайн-студия',
        'supplier'     => 'Поставщик',
        'logistics'    => 'Логистика и розница',
        'other'        => 'Другое',
    ];

    /** Регионы Узбекистана — 12 областей + Ташкент. */
    public const REGIONS = [
        'tashkent_city'   => 'Ташкент',
        'tashkent_region' => 'Ташкентская обл.',
        'andijan'         => 'Андижанская обл.',
        'bukhara'         => 'Бухарская обл.',
        'fergana'         => 'Ферганская обл.',
        'jizzakh'         => 'Джизакская обл.',
        'kashkadarya'     => 'Кашкадарьинская обл.',
        'khorezm'         => 'Хорезмская обл.',
        'namangan'        => 'Наманганская обл.',
        'navoi'           => 'Навоийская обл.',
        'samarkand'       => 'Самаркандская обл.',
        'sirdarya'        => 'Сырдарьинская обл.',
        'surkhandarya'    => 'Сурхандарьинская обл.',
        'karakalpakstan'  => 'Каракалпакстан',
    ];

    public static function allCategories(?string $locale = null): array
    {
        $fromDb = Category::map(Category::TYPE_PARTNERS, $locale);
        return !empty($fromDb) ? $fromDb : self::CATEGORIES;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function views()
    {
        return $this->hasMany(PartnerView::class);
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeOnHome($q)    { return $q->where('show_on_home', true); }

    /** Топ-N партнёров за последние N дней. */
    public function scopePopular($q, int $limit = 10)
    {
        return $q->where('views_count_30d', '>', 0)->orderByDesc('views_count_30d')->limit($limit);
    }
}

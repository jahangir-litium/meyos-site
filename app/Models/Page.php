<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'slug', 'view', 'title', 'seo_title', 'seo_description', 'seo_keywords',
        'hero_tag', 'hero_h1', 'hero_lead', 'hero_image', 'is_published',
    ];

    public array $translatable = [
        'title', 'seo_title', 'seo_description', 'seo_keywords',
        'hero_tag', 'hero_h1', 'hero_lead',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero')->singleFile();
        $this->addMediaCollection('og')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

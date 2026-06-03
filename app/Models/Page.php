<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'view', 'title', 'seo_title', 'seo_description', 'seo_keywords',
        'hero_tag', 'hero_h1', 'hero_lead', 'is_published',
    ];

    public array $translatable = [
        'title', 'seo_title', 'seo_description', 'seo_keywords',
        'hero_tag', 'hero_h1', 'hero_lead',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
}

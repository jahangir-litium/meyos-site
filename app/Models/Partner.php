<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Partner extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia;

    protected $fillable = [
        'slug', 'category', 'name', 'description', 'logo_text', 'website_url',
        'registry_id', 'is_published', 'show_on_home', 'sort',
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'is_published' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    public const CATEGORIES = [
        'production' => 'Производство',
        'design'     => 'Дизайн',
        'materials'  => 'Материалы',
        'logistics'  => 'Логистика',
        'gov'        => 'Государственный',
        'finance'    => 'Финансы',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeOnHome($q)    { return $q->where('show_on_home', true); }
}

<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\Translatable\HasTranslations;

class News extends Model implements HasMedia
{
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'slug', 'category', 'published_at', 'is_featured', 'is_published',
        'title', 'preview', 'content', 'image_alt', 'cover_image', 'sort',
    ];

    public array $translatable = ['title', 'preview', 'content', 'image_alt'];

    protected $casts = [
        'published_at' => 'date',
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
    ];

    public const CATEGORIES = [
        'residency'  => 'Резидентство',
        'export'     => 'Экспорт',
        'edujob'     => 'EduJob',
        'regulation' => 'Регулирование',
        'programs'   => 'Программы',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true)->where('published_at', '<=', now()); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
}

<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Program extends Model implements HasMedia
{
    use HasTranslations, HasSorting, AutoSlug, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'slug', 'icon', 'color', 'chip', 'title', 'description',
        'hero_h1', 'hero_lead', 'short_summary', 'cover_image',
        'is_flagship', 'is_published', 'sort',
    ];

    public array $translatable = [
        'chip', 'title', 'description', 'hero_h1', 'hero_lead', 'short_summary',
    ];

    protected $casts = ['is_flagship' => 'boolean', 'is_published' => 'boolean'];

    public function blocks(): HasMany
    {
        return $this->hasMany(ProgramBlock::class)->where('is_published', true)->orderBy('sort');
    }

    public function advantages(): HasMany
    {
        return $this->hasMany(ProgramAdvantage::class)->where('is_published', true)->orderBy('sort');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

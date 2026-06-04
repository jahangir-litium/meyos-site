<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class BusinessCase extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia, SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'tag', 'title', 'description',
        'metric1_value', 'metric1_label',
        'metric2_value', 'metric2_label',
        'metric3_value', 'metric3_label', 'cover_image',
        'is_published', 'sort',
    ];

    public array $translatable = [
        'tag', 'title', 'description',
        'metric1_value', 'metric1_label',
        'metric2_value', 'metric2_label',
        'metric3_value', 'metric3_label',
    ];

    protected $casts = ['is_published' => 'boolean'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

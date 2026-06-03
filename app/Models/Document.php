<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Document extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia;

    protected $fillable = ['title', 'file_path', 'file_name', 'is_published', 'sort'];
    public array $translatable = ['title'];
    protected $casts = ['is_published' => 'boolean'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('file')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

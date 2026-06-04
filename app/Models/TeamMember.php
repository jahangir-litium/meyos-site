<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class TeamMember extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia, SoftDeletes;

    protected $fillable = ['name', 'role', 'initials', 'photo_image', 'is_published', 'sort'];
    public array $translatable = ['name', 'role'];
    protected $casts = ['is_published' => 'boolean'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

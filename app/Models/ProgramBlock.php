<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class ProgramBlock extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia;

    protected $fillable = [
        'program_id', 'type', 'icon', 'title', 'description', 'meta',
        'is_published', 'sort',
    ];

    public array $translatable = ['title', 'description', 'meta'];

    protected $casts = ['is_published' => 'boolean'];

    public const TYPES = [
        'feature' => 'Карточка-фича',
        'module'  => 'Учебный модуль',
        'metric'  => 'Метрика результата',
        'cta'     => 'Призыв к действию',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Event extends Model implements HasMedia
{
    use HasTranslations, HasSorting, InteractsWithMedia;

    protected $fillable = [
        'slug', 'category', 'event_date', 'end_date', 'start_time', 'end_time',
        'city', 'location', 'is_featured', 'is_published',
        'title', 'preview', 'description', 'image_alt', 'expected_attendees', 'sort',
    ];

    public array $translatable = ['city', 'location', 'title', 'preview', 'description', 'image_alt'];

    protected $casts = [
        'event_date'   => 'date',
        'end_date'     => 'date',
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
    ];

    public const CATEGORIES = [
        'forum'              => 'Форум',
        'export'             => 'Экспортная миссия',
        'edujob'             => 'EduJob',
        'round-table'        => 'Круглый стол',
        'exhibition'         => 'Выставка',
        'business-breakfast' => 'Деловой завтрак',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
    }

    public function registrations() { return $this->hasMany(EventRegistration::class); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeUpcoming($q)  { return $q->where('event_date', '>=', now()->toDateString()); }
}

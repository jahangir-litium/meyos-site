<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations, HasSorting;

    protected $fillable = ['question', 'answer', 'page_slug', 'is_published', 'sort'];
    public array $translatable = ['question', 'answer'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeForPage($q, string $slug) { return $q->where('page_slug', $slug); }
}

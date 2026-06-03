<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PainSolutionRow extends Model
{
    use HasTranslations, HasSorting;

    protected $fillable = [
        'pain_title', 'pain_description',
        'solution_title', 'solution_description',
        'is_published', 'sort',
    ];

    public array $translatable = ['pain_title', 'pain_description', 'solution_title', 'solution_description'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
}

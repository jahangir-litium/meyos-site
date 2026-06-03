<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TaxRow extends Model
{
    use HasTranslations, HasSorting;

    protected $fillable = ['parameter', 'standard_rate', 'resident_rate', 'savings', 'is_published', 'sort'];
    public array $translatable = ['parameter', 'standard_rate', 'resident_rate', 'savings'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
}

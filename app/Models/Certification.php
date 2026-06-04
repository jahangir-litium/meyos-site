<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Certification extends Model
{
    use HasTranslations, HasSorting, SoftDeletes;

    protected $fillable = ['icon', 'title', 'description', 'is_published', 'sort'];
    public array $translatable = ['title', 'description'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
}

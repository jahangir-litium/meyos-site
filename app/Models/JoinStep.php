<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class JoinStep extends Model
{
    use HasTranslations, HasSorting;

    protected $fillable = ['title', 'description', 'is_published', 'sort'];
    public array $translatable = ['title', 'description'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }
}

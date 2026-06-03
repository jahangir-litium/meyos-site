<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProgramAdvantage extends Model
{
    use HasTranslations, HasSorting;

    protected $fillable = ['program_id', 'icon', 'title', 'description', 'is_published', 'sort'];
    public array $translatable = ['title', 'description'];
    protected $casts = ['is_published' => 'boolean'];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}

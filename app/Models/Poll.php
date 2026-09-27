<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Poll extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug', 'question', 'options', 'is_active', 'show_on_home', 'is_anonymous',
        'show_results_after_vote', 'starts_at', 'ends_at', 'total_votes',
    ];

    public array $translatable = ['question'];

    protected $casts = [
        'options'                 => 'array',
        'is_active'               => 'boolean',
        'show_on_home'            => 'boolean',
        'is_anonymous'            => 'boolean',
        'show_results_after_vote' => 'boolean',
        'starts_at'               => 'datetime',
        'ends_at'                 => 'datetime',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    /** Возвращает [option_index => count] с добитыми нулями. */
    public function getVoteCounts(): array
    {
        $counts = $this->votes()->selectRaw('option_index, COUNT(*) as c')
            ->groupBy('option_index')
            ->pluck('c', 'option_index')
            ->all();
        $result = [];
        foreach (($this->options ?? []) as $i => $opt) {
            $result[$i] = (int) ($counts[$i] ?? 0);
        }
        return $result;
    }

    public function hasEnded(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
}

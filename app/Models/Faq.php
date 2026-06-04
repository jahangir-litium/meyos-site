<?php

namespace App\Models;

use App\Models\Concerns\HasSorting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations, HasSorting, SoftDeletes;

    protected $fillable = ['question', 'answer', 'page_slug', 'is_published', 'sort'];
    public array $translatable = ['question', 'answer'];
    protected $casts = ['is_published' => 'boolean'];

    public function scopePublished($q) { return $q->where('is_published', true); }

    /**
     * ВАЖНО: метод НЕ называется forPage() — он бы конфликтовал с встроенным
     * Eloquent\Builder::forPage($page, $perPage), который вызывается из paginate()
     * → возникал бы where page_slug = '1' и таблица в админке оставалась пустой.
     */
    public function scopeForPageSlug($q, string $slug) { return $q->where('page_slug', $slug); }
}

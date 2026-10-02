<?php

namespace App\Models;

use App\Models\Concerns\AutoSlug;
use App\Models\Concerns\HasSorting;
use App\Models\Concerns\LogsChanges;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class LegalAct extends Model
{
    use HasTranslations, HasSorting, AutoSlug, SoftDeletes, LogsChanges;

    protected $fillable = [
        'slug', 'act_number', 'act_date', 'status', 'category',
        'title', 'summary', 'content',
        'source_url', 'pdf_path',
        'is_published', 'is_featured', 'sort',
        'seo_title', 'seo_description',
    ];

    public array $translatable = ['title', 'summary', 'content', 'seo_title', 'seo_description'];

    protected $casts = [
        'act_date'      => 'date',
        'is_published'  => 'boolean',
        'is_featured'   => 'boolean',
    ];

    /** Регулируемые области — для фильтра на фронте и в админке. */
    public const CATEGORIES = [
        'tariffs'       => 'Пошлины и импорт',
        'taxes'         => 'Налоги и льготы',
        'certification' => 'Сертификация',
        'export'        => 'Экспорт',
        'hr'            => 'Кадры и обучение',
        'clusters'      => 'Мебельные кластеры',
        'other'         => 'Другое',
    ];

    public const STATUSES = [
        'active'   => 'Действует',
        'draft'    => 'Законопроект',
        'repealed' => 'Утратил силу',
    ];

    public static function allCategories(?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $map = [
            'ru' => self::CATEGORIES,
            'uz' => [
                'tariffs'       => 'Bojxona va import',
                'taxes'         => 'Soliqlar va imtiyozlar',
                'certification' => 'Sertifikatsiya',
                'export'        => 'Eksport',
                'hr'            => 'Kadrlar va taʼlim',
                'clusters'      => 'Mebel klasterlari',
                'other'         => 'Boshqa',
            ],
            'en' => [
                'tariffs'       => 'Tariffs and imports',
                'taxes'         => 'Taxes and benefits',
                'certification' => 'Certification',
                'export'        => 'Exports',
                'hr'            => 'Workforce and training',
                'clusters'      => 'Furniture clusters',
                'other'         => 'Other',
            ],
        ];
        return $map[$locale] ?? $map['ru'];
    }

    public static function allStatuses(?string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $map = [
            'ru' => self::STATUSES,
            'uz' => ['active' => 'Amalda', 'draft' => 'Qonun loyihasi', 'repealed' => 'Kuchini yoʻqotgan'],
            'en' => ['active' => 'In force', 'draft' => 'Draft bill', 'repealed' => 'Repealed'],
        ];
        return $map[$locale] ?? $map['ru'];
    }

    public function scopePublished($q)  { return $q->where('is_published', true); }
    public function scopeFeatured($q)   { return $q->where('is_featured',  true); }
    public function scopeActive($q)     { return $q->where('status', 'active'); }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path ? asset('storage/' . ltrim($this->pdf_path, '/')) : null;
    }
}

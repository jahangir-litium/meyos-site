<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

/**
 * Заполняет пустые SEO-поля (seo_title, seo_description) во всех
 * новостях и партнёрах на 3 языках. Ручные значения не перезаписываются
 * — если админ уже написал свой seo_title/seo_description для локали,
 * оставляем как есть.
 *
 * Целевые длины:
 *   seo_title       — 45–60 символов
 *   seo_description — 140–160 символов
 *
 * Идея:
 *  - title / name — базовый заголовок; если короткий, добавляем «— MEYOS»;
 *    если длинный, обрезаем аккуратно по слову
 *  - preview / description — база для описания; вырезаем HTML, обрезаем
 *    до 155 символов на границе слова, дополняем «…» если урезали
 */
class SeoFieldsSeeder extends Seeder
{
    private const LOCALES = ['ru', 'uz', 'en'];

    /** Суффиксы под каждый язык для seo_title */
    private const SUFFIX = [
        'ru' => ' — MEYOS',
        'uz' => ' — MEYOS',
        'en' => ' — MEYOS',
    ];

    /** «Тема» бренда в описании (добавляем в конце, если помещается) */
    private const BRAND_HINT = [
        'ru' => 'MEYOS — ассоциация мебельщиков Узбекистана.',
        'uz' => 'MEYOS — Oʻzbekiston mebel uyushmasi.',
        'en' => 'MEYOS — Uzbekistan Furniture Association.',
    ];

    public function run(): void
    {
        $this->seedNews();
        $this->seedPartners();
    }

    private function seedNews(): void
    {
        $count = 0;
        foreach (News::all() as $news) {
            $changed = false;
            foreach (self::LOCALES as $loc) {
                // seo_title
                $existing = $news->getTranslation('seo_title', $loc, false);
                if (empty($existing)) {
                    $title = $news->getTranslation('title', $loc, false)
                          ?: $news->getTranslation('title', 'ru', false);
                    $seo = $this->buildTitle((string) $title, $loc);
                    $news->setTranslation('seo_title', $loc, $seo);
                    $changed = true;
                }
                // seo_description
                $existing = $news->getTranslation('seo_description', $loc, false);
                if (empty($existing)) {
                    $preview = $news->getTranslation('preview', $loc, false)
                            ?: $news->getTranslation('preview', 'ru', false)
                            ?: strip_tags((string) $news->getTranslation('content', $loc, false));
                    $seo = $this->buildDescription((string) $preview, $loc);
                    $news->setTranslation('seo_description', $loc, $seo);
                    $changed = true;
                }
            }
            if ($changed) {
                $news->saveQuietly();
                $count++;
            }
        }
        $this->command->info("News SEO обновлено: {$count} записей");
    }

    private function seedPartners(): void
    {
        $count = 0;
        foreach (Partner::all() as $partner) {
            $changed = false;
            foreach (self::LOCALES as $loc) {
                // seo_title
                $existing = $partner->getTranslation('seo_title', $loc, false);
                if (empty($existing)) {
                    $name = $partner->getTranslation('name', $loc, false)
                         ?: $partner->getTranslation('name', 'ru', false);
                    // Для партнёров добавляем «резидент MEYOS», а не просто «MEYOS»
                    $suffix = [
                        'ru' => ' · Резидент MEYOS',
                        'uz' => ' · MEYOS rezidenti',
                        'en' => ' · MEYOS resident',
                    ][$loc];
                    $seo = $this->buildTitleWithSuffix((string) $name, $suffix);
                    $partner->setTranslation('seo_title', $loc, $seo);
                    $changed = true;
                }
                // seo_description
                $existing = $partner->getTranslation('seo_description', $loc, false);
                if (empty($existing)) {
                    $desc = $partner->getTranslation('description', $loc, false)
                         ?: $partner->getTranslation('description', 'ru', false)
                         ?: strip_tags((string) $partner->getTranslation('about', $loc, false));
                    $seo = $this->buildDescription((string) $desc, $loc);
                    $partner->setTranslation('seo_description', $loc, $seo);
                    $changed = true;
                }
            }
            if ($changed) {
                $partner->saveQuietly();
                $count++;
            }
        }
        $this->command->info("Partner SEO обновлено: {$count} записей");
    }

    /** «Заголовок — MEYOS», обрезаем аккуратно до 60 символов. */
    private function buildTitle(string $title, string $locale): string
    {
        return $this->buildTitleWithSuffix($title, self::SUFFIX[$locale] ?? ' — MEYOS');
    }

    private function buildTitleWithSuffix(string $title, string $suffix): string
    {
        $title = trim(preg_replace('/\s+/u', ' ', strip_tags($title)));
        $max = 60;
        $available = $max - mb_strlen($suffix);
        if (mb_strlen($title) <= $available) {
            return $title . $suffix;
        }
        // обрезаем до границы слова
        $cut = mb_substr($title, 0, $available - 1);
        $sp  = mb_strrpos($cut, ' ');
        if ($sp !== false && $sp > $available - 15) {
            $cut = mb_substr($cut, 0, $sp);
        }
        return rtrim($cut, " .,;:—-") . '…' . $suffix;
    }

    /**
     * Описание: чистим HTML, склеиваем пробелы, обрезаем до 155 символов
     * по границе предложения/слова. Добавляем brand-хвост если места
     * достаточно.
     */
    private function buildDescription(string $raw, string $locale): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags($raw)));
        $max  = 160;

        if (mb_strlen($text) <= $max) {
            $brand = self::BRAND_HINT[$locale] ?? '';
            if ($brand && mb_strlen($text) + 1 + mb_strlen($brand) <= $max) {
                return rtrim($text, '. ') . '. ' . $brand;
            }
            return $text;
        }

        // Обрезаем до 157 и ищем конец слова
        $cut = mb_substr($text, 0, 157);
        $sp  = mb_strrpos($cut, ' ');
        if ($sp !== false && $sp > 120) {
            $cut = mb_substr($cut, 0, $sp);
        }
        return rtrim($cut, " .,;:—-") . '…';
    }
}

<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

/**
 * Единый стандарт для всех image-загрузок в Filament-формах.
 *
 * Решает три проблемы, из-за которых картинки «не загружались»:
 *   1. Filament без явных disk('public') / directory(...) / visibility('public')
 *      кидает файлы в неправильное место — Livewire их теряет при сохранении.
 *   2. Без maxSize кидаются 500-е на больших файлах.
 *   3. Без понятного helperText менеджеры не знают что грузить.
 *
 * Использование:
 *   ImageUpload::cover('cover_image', 'Обложка', 'news')
 *   ImageUpload::avatar('photo_image', 'Фото', 'team')
 *   ImageUpload::logo('logo_image', 'Логотип', 'partners')
 *   ImageUpload::gallery('gallery_images', 'Галерея', 'news/gallery')
 */
class ImageUpload
{
    /** Обложка 16:9 — для новостей, событий, программ, кейсов. */
    public static function cover(string $name, string $label, string $directory, int $maxKb = 4096): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxKb)
            ->imagePreviewHeight('150')
            ->openable()
            ->downloadable()
            ->helperText('Рекомендуется 1200×675 (16:9). До ' . round($maxKb / 1024) . ' МБ. Поддерживаются JPG, PNG, WebP.')
            ->columnSpanFull();
    }

    /** Аватарка/фото человека — для команды. */
    public static function avatar(string $name, string $label, string $directory, int $maxKb = 2048): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxKb)
            ->imagePreviewHeight('120')
            ->openable()
            ->helperText('Квадратное фото, рекомендуется 400×400. До ' . round($maxKb / 1024) . ' МБ.');
    }

    /** Логотип партнёра или бренд — прозрачный фон, любой размер. */
    public static function logo(string $name, string $label, string $directory, int $maxKb = 2048): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxKb)
            ->imagePreviewHeight('80')
            ->openable()
            ->downloadable()
            ->helperText('PNG/SVG с прозрачным фоном, до ' . round($maxKb / 1024) . ' МБ.')
            ->columnSpanFull();
    }

    /** Галерея — multiple + reorderable. */
    public static function gallery(string $name, string $label, string $directory, int $maxFiles = 15, int $maxKb = 4096): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->multiple()
            ->reorderable()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxFiles($maxFiles)
            ->maxSize($maxKb)
            ->imagePreviewHeight('100')
            ->openable()
            ->downloadable()
            ->panelLayout('grid')
            ->helperText("Перетягивайте для сортировки. До $maxFiles фото, каждое до " . round($maxKb / 1024) . ' МБ.')
            ->columnSpanFull();
    }

    /** Hero / OG-картинка для соцсетей, 1200×630. */
    public static function og(string $name, string $label, string $directory, int $maxKb = 2048): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize($maxKb)
            ->imagePreviewHeight('100')
            ->openable()
            ->helperText('1200×630 для шеринга в Facebook/Telegram/WhatsApp. До ' . round($maxKb / 1024) . ' МБ. Если пусто — берётся обложка.');
    }
}

<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;

/**
 * Единый стандарт для всех image-загрузок в Filament-формах.
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
            ->openable()
            ->downloadable()
            ->helperText('JPG/PNG/WebP, до ' . round($maxKb / 1024) . ' МБ. Рекомендуется 1200×675 (16:9).')
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
            ->openable()
            ->helperText('JPG/PNG/WebP, до ' . round($maxKb / 1024) . ' МБ. Рекомендуется квадрат 400×400.');
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
            ->openable()
            ->downloadable()
            ->panelLayout('grid')
            ->helperText("До $maxFiles фото, каждое до " . round($maxKb / 1024) . ' МБ. Перетягивайте для сортировки.')
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
            ->openable()
            ->helperText('JPG/PNG/WebP 1200×630 для шеринга в соцсетях, до ' . round($maxKb / 1024) . ' МБ. Если пусто — берётся обложка.');
    }
}

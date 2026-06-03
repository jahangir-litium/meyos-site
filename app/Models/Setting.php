<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    protected $casts = ['value' => 'array'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row?->value ?? $default;
        });
    }

    /** URL логотипа (если загружен) или null. */
    public static function logoUrl(): ?string
    {
        $p = static::get('logo_path');
        return $p ? asset('storage/' . ltrim($p, '/')) : null;
    }

    /** URL favicon (если загружен) или null. */
    public static function faviconUrl(): ?string
    {
        $p = static::get('favicon_path');
        return $p ? asset('storage/' . ltrim($p, '/')) : null;
    }

    public static function put(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
        Cache::forget("setting.$key");
    }

    protected static function booted(): void
    {
        static::saved(fn ($m) => Cache::forget("setting.{$m->key}"));
        static::deleted(fn ($m) => Cache::forget("setting.{$m->key}"));
    }
}

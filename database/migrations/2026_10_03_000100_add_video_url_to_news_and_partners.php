<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Поле video_url для встраивания YouTube/Instagram на страницы новости и партнёра.
 * Админ вставляет публичную ссылку (watch?v=..., youtu.be/..., instagram.com/p/..., reel/...).
 * На фронте VideoEmbed::html($url) превращает её в <iframe> / <blockquote>.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['news', 'partners'] as $table) {
            if (!Schema::hasColumn($table, 'video_url')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('video_url', 500)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['news', 'partners'] as $table) {
            if (Schema::hasColumn($table, 'video_url')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropColumn('video_url');
                });
            }
        }
    }
};

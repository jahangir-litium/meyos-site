<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Мы храним настройки как ключ-значение в table `settings`.
 * Добавлять колонку не нужно — просто гарантируем что запись logo_dark_path существует.
 */
return new class extends Migration {
    public function up(): void
    {
        if (!DB::table('settings')->where('key', 'logo_dark_path')->exists()) {
            DB::table('settings')->insert([
                'key'        => 'logo_dark_path',
                'value'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'logo_dark_path')->delete();
    }
};

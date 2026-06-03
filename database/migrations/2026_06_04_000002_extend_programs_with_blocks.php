<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /* ===== Расширяем programs (hero + краткое описание для карточки) ===== */
        Schema::table('programs', function (Blueprint $b) {
            // Новый главный hero-заголовок для страницы программы
            $b->json('hero_h1')->nullable()->after('description');
            $b->json('hero_lead')->nullable()->after('hero_h1');
            // Краткая карточка (Что включает программа)
            $b->json('short_summary')->nullable()->after('hero_lead');
            // Цвет акцента (опционально)
            $b->string('color', 20)->nullable()->after('icon');
        });

        /* ===== Блоки внутри программы (до 4 на программу, можно и больше) ===== */
        Schema::create('program_blocks', function (Blueprint $b) {
            $b->id();
            $b->foreignId('program_id')->constrained()->cascadeOnDelete();
            $b->string('type', 30)->default('feature')->index();
            // feature  — карточка преимущества
            // module   — образовательный модуль (с длительностью)
            // metric   — метрика результата (число + подпись)
            // cta      — призыв к действию
            $b->string('icon', 50)->nullable();
            $b->json('title');
            $b->json('description')->nullable();
            $b->json('meta')->nullable();          // длительность, цена, лейбл — произвольное поле для текста
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ===== Преимущества программы (отдельно от блоков) ===== */
        Schema::create('program_advantages', function (Blueprint $b) {
            $b->id();
            $b->foreignId('program_id')->constrained()->cascadeOnDelete();
            $b->string('icon', 50)->nullable();
            $b->json('title');
            $b->json('description')->nullable();
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_advantages');
        Schema::dropIfExists('program_blocks');
        Schema::table('programs', function (Blueprint $b) {
            $b->dropColumn(['hero_h1', 'hero_lead', 'short_summary', 'color']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('legal_acts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('act_number')->nullable()->comment('ПП-193, Указ 6002 и т.п.');
            $table->date('act_date')->nullable();
            $table->string('status', 20)->default('active')->index()->comment('active | draft | repealed');
            $table->string('category', 40)->index()->comment('tariffs | taxes | certification | export | hr | clusters | other');
            $table->json('title')->comment('translatable: ru/uz/en');
            $table->json('summary')->nullable()->comment('translatable 1-2 абзаца');
            $table->json('content')->nullable()->comment('translatable HTML, полный текст');
            $table->string('source_url')->nullable()->comment('lex.uz / nrm.uz / norma.uz');
            $table->string('pdf_path')->nullable()->comment('локальный PDF для скачивания');
            $table->boolean('is_published')->default(true)->index();
            $table->boolean('is_featured')->default(false)->index()->comment('показывать на главной');
            $table->integer('sort')->default(0);
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_acts');
    }
};

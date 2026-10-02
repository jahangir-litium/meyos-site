<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('savdex_listings', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 50)->unique()->comment('ID объявления с SAVDEX (из URL)');
            $table->string('slug')->comment('полный slug из URL, нужен для построения ссылки обратно');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->string('listing_type', 20)->default('demand')->index()->comment('demand | offer | tender');
            $table->string('price')->nullable()->comment('«Договорная», «от 100 000 UZS» и т.п. — строка как на SAVDEX');
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('image_url')->nullable();
            $table->string('source_url');
            $table->json('tags')->nullable();
            $table->date('published_at')->nullable()->index();
            $table->date('expires_at')->nullable()->index();
            $table->timestamp('fetched_at')->index();
            $table->boolean('is_hidden')->default(false)->index()->comment('админ скрыл неподходящее');
            $table->boolean('is_featured')->default(false)->index()->comment('показать на главной');
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savdex_listings');
    }
};

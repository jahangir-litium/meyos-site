<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $b) {
            $b->id();
            $b->string('path', 255)->index();
            $b->string('page_slug', 100)->nullable()->index();
            $b->string('locale', 5)->nullable();
            $b->string('referrer', 500)->nullable();
            $b->string('ip_hash', 64)->index();           // SHA-256 от IP (без хранения сырого IP)
            $b->string('user_agent', 500)->nullable();
            $b->string('device', 20)->nullable();         // mobile / tablet / desktop
            $b->boolean('is_bot')->default(false);
            $b->timestamp('created_at')->useCurrent()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};

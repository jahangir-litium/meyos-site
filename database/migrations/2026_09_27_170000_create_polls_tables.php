<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('polls', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 150)->unique();
            $t->json('question');           // translatable: {ru, uz, en}
            $t->json('options');            // [{ru,uz,en}, ...] массив вариантов
            $t->boolean('is_active')->default(true);
            $t->boolean('is_anonymous')->default(true);
            $t->boolean('show_results_after_vote')->default(true);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('ends_at')->nullable()->index();
            $t->unsignedInteger('total_votes')->default(0);  // денормализованный счётчик
            $t->timestamps();
        });

        Schema::create('poll_votes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('option_index');  // 0..N-1
            $t->string('ip_hash', 64)->index();
            $t->string('session_hash', 64)->nullable();
            $t->string('user_agent_family', 60)->nullable();
            $t->timestamp('voted_at')->useCurrent();

            $t->unique(['poll_id', 'ip_hash']);  // 1 голос с IP
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poll_votes');
        Schema::dropIfExists('polls');
    }
};

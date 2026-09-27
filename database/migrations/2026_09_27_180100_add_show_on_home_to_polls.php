<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $t) {
            $t->boolean('show_on_home')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('polls', function (Blueprint $t) {
            $t->dropColumn('show_on_home');
        });
    }
};

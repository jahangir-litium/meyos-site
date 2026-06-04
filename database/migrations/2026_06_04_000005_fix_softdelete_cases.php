<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Таблица BusinessCase называется 'cases' (а не 'business_cases')
        if (Schema::hasTable('cases') && !Schema::hasColumn('cases', 'deleted_at')) {
            Schema::table('cases', fn (Blueprint $b) => $b->softDeletes());
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cases', 'deleted_at')) {
            Schema::table('cases', fn (Blueprint $b) => $b->dropSoftDeletes());
        }
    }
};

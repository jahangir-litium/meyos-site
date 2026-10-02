<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Добавляет флаг is_spam на 3 таблицы заявок с формы.
 * Админу показываются только is_spam=false, автоматически помеченные боты скрыты.
 */
return new class extends Migration
{
    private array $tables = ['membership_applications', 'contact_messages', 'event_registrations'];

    public function up(): void
    {
        foreach ($this->tables as $t) {
            if (!Schema::hasColumn($t, 'is_spam')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->boolean('is_spam')->default(false)->index();
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $t) {
            if (Schema::hasColumn($t, 'is_spam')) {
                Schema::table($t, function (Blueprint $table) {
                    $table->dropColumn('is_spam');
                });
            }
        }
    }
};

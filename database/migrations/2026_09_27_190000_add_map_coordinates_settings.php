<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach (['office_lat', 'office_lng', 'office_zoom'] as $key) {
            if (!DB::table('settings')->where('key', $key)->exists()) {
                DB::table('settings')->insert([
                    'key'        => $key,
                    'value'      => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', ['office_lat', 'office_lng', 'office_zoom'])->delete();
    }
};

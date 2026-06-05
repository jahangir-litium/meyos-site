<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Удаляет дубли категорий партнёров — оставляет только slug-и, которые
     * реально используются партнёрами (production/design/materials/logistics/gov/finance),
     * убирает новые но неиспользуемые (manufacturer/designer/supplier/other).
     */
    public function up(): void
    {
        DB::table('categories')
            ->where('type', 'partners')
            ->whereIn('slug', ['manufacturer', 'designer', 'supplier', 'other'])
            ->delete();
    }

    public function down(): void
    {
        // Откат: добавляем slug-и обратно
        $names = [
            'manufacturer' => 'Производитель',
            'designer'     => 'Дизайн-студия',
            'supplier'     => 'Поставщик',
            'other'        => 'Другое',
        ];
        foreach ($names as $slug => $ru) {
            DB::table('categories')->updateOrInsert(
                ['type' => 'partners', 'slug' => $slug],
                ['name' => json_encode(['ru' => $ru]), 'sort' => 0, 'is_published' => 1, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
};

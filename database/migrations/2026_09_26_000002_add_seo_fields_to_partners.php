<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $t) {
            $t->json('seo_title')->nullable()->after('gallery_images');       // translatable
            $t->json('seo_description')->nullable()->after('seo_title');      // translatable
            $t->string('seo_image')->nullable()->after('seo_description');    // path в storage/public
        });
    }

    public function down(): void
    {
        Schema::table('partners', function (Blueprint $t) {
            $t->dropColumn(['seo_title', 'seo_description', 'seo_image']);
        });
    }
};

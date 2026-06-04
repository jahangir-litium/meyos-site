<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ============ Динамические категории ============
        Schema::create('categories', function (Blueprint $b) {
            $b->id();
            $b->string('type', 50)->index();          // news | partners | events
            $b->string('slug', 100);
            $b->json('name');                          // {ru,uz,en}
            $b->unsignedInteger('sort')->default(0);
            $b->boolean('is_published')->default(true);
            $b->timestamps();
            $b->softDeletes();
            $b->unique(['type', 'slug']);
        });

        // ============ SEO + галерея + CTA для News ============
        Schema::table('news', function (Blueprint $b) {
            if (!Schema::hasColumn('news', 'seo_title'))       $b->json('seo_title')->nullable()->after('content');
            if (!Schema::hasColumn('news', 'seo_description')) $b->json('seo_description')->nullable()->after('seo_title');
            if (!Schema::hasColumn('news', 'seo_image'))       $b->string('seo_image')->nullable()->after('seo_description');
            if (!Schema::hasColumn('news', 'gallery_images'))  $b->json('gallery_images')->nullable()->after('cover_image');
            if (!Schema::hasColumn('news', 'cta_text'))        $b->json('cta_text')->nullable()->after('gallery_images');
            if (!Schema::hasColumn('news', 'cta_url'))         $b->string('cta_url', 500)->nullable()->after('cta_text');
            if (!Schema::hasColumn('news', 'cta_event_id'))    $b->unsignedBigInteger('cta_event_id')->nullable()->after('cta_url');
        });

        // ============ SEO для Events ============
        Schema::table('events', function (Blueprint $b) {
            if (!Schema::hasColumn('events', 'seo_title'))       $b->json('seo_title')->nullable();
            if (!Schema::hasColumn('events', 'seo_description')) $b->json('seo_description')->nullable();
            if (!Schema::hasColumn('events', 'seo_image'))       $b->string('seo_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');

        Schema::table('news', function (Blueprint $b) {
            foreach (['seo_title','seo_description','seo_image','gallery_images','cta_text','cta_url','cta_event_id'] as $col) {
                if (Schema::hasColumn('news', $col)) $b->dropColumn($col);
            }
        });

        Schema::table('events', function (Blueprint $b) {
            foreach (['seo_title','seo_description','seo_image'] as $col) {
                if (Schema::hasColumn('events', $col)) $b->dropColumn($col);
            }
        });
    }
};

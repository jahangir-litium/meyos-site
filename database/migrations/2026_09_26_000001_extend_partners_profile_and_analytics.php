<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Расширение partners для страницы-профиля + счётчики просмотров.
     * Отдельная таблица partner_views — для аналитики.
     */
    public function up(): void
    {
        Schema::table('partners', function (Blueprint $t) {
            // ---- Профиль ----
            $t->string('region', 40)->nullable()->after('category')->index();
            $t->unsignedSmallInteger('founded_year')->nullable()->after('region');
            $t->json('about')->nullable()->after('description');       // translatable long
            $t->json('gallery_images')->nullable()->after('about');    // ["path1","path2"]
            $t->json('socials')->nullable()->after('gallery_images');  // {telegram, instagram, facebook}
            $t->string('contact_email', 120)->nullable()->after('socials');
            $t->string('contact_phone', 50)->nullable()->after('contact_email');

            // ---- Аналитика (денормализация) ----
            $t->unsignedInteger('views_count_total')->default(0)->after('sort');
            $t->unsignedInteger('views_count_30d')->default(0)->after('views_count_total');
            $t->timestamp('last_viewed_at')->nullable()->after('views_count_30d');
        });

        Schema::create('partner_views', function (Blueprint $t) {
            $t->id();
            $t->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $t->string('ip_hash', 64)->index();
            $t->string('session_hash', 64)->nullable();
            $t->string('ua_family', 60)->nullable();
            $t->string('referer_host', 120)->nullable();
            $t->string('locale', 5)->nullable();
            $t->timestamp('viewed_at')->useCurrent()->index();

            $t->index(['partner_id', 'viewed_at']);
            $t->index(['partner_id', 'ip_hash', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_views');
        Schema::table('partners', function (Blueprint $t) {
            $t->dropIndex(['region']);
            $t->dropColumn([
                'region', 'founded_year', 'about', 'gallery_images', 'socials',
                'contact_email', 'contact_phone',
                'views_count_total', 'views_count_30d', 'last_viewed_at',
            ]);
        });
    }
};

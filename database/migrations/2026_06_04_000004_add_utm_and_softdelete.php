<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ============ UTM-метки в page_views ============
        Schema::table('page_views', function (Blueprint $b) {
            $b->string('utm_source', 100)->nullable()->after('referrer');
            $b->string('utm_medium', 100)->nullable()->after('utm_source');
            $b->string('utm_campaign', 100)->nullable()->after('utm_medium');
            $b->string('utm_content', 100)->nullable()->after('utm_campaign');
            $b->string('utm_term', 100)->nullable()->after('utm_content');
        });

        // ============ UTM-метки в заявках ============
        Schema::table('membership_applications', function (Blueprint $b) {
            $b->string('utm_source', 100)->nullable()->after('source_page');
            $b->string('utm_medium', 100)->nullable();
            $b->string('utm_campaign', 100)->nullable();
            $b->softDeletes();
        });

        Schema::table('contact_messages', function (Blueprint $b) {
            $b->softDeletes();
        });

        Schema::table('event_registrations', function (Blueprint $b) {
            $b->softDeletes();
        });

        // ============ Soft-delete для контентных моделей ============
        foreach ([
            'news', 'programs', 'events', 'partners', 'team_members',
            'documents', 'business_cases', 'certifications', 'faqs',
        ] as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, fn (Blueprint $b) => $b->softDeletes());
            }
        }
    }

    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $b) {
            $b->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term']);
        });

        Schema::table('membership_applications', function (Blueprint $b) {
            $b->dropColumn(['utm_source', 'utm_medium', 'utm_campaign']);
            $b->dropSoftDeletes();
        });

        Schema::table('contact_messages', fn (Blueprint $b) => $b->dropSoftDeletes());
        Schema::table('event_registrations', fn (Blueprint $b) => $b->dropSoftDeletes());

        foreach ([
            'news', 'programs', 'events', 'partners', 'team_members',
            'documents', 'business_cases', 'certifications', 'faqs',
        ] as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, fn (Blueprint $b) => $b->dropSoftDeletes());
            }
        }
    }
};

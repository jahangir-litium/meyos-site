<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pages',          fn (Blueprint $t) => $t->string('hero_image', 500)->nullable()->after('hero_lead'));
        Schema::table('news',           fn (Blueprint $t) => $t->string('cover_image', 500)->nullable()->after('image_alt'));
        Schema::table('events',         fn (Blueprint $t) => $t->string('cover_image', 500)->nullable()->after('image_alt'));
        Schema::table('partners',       fn (Blueprint $t) => $t->string('logo_image', 500)->nullable()->after('logo_text'));
        Schema::table('programs',       fn (Blueprint $t) => $t->string('cover_image', 500)->nullable()->after('short_summary'));
        Schema::table('program_blocks', fn (Blueprint $t) => $t->string('image', 500)->nullable()->after('icon'));
        Schema::table('cases',          fn (Blueprint $t) => $t->string('cover_image', 500)->nullable()->after('metric3_label'));
        Schema::table('team_members',   fn (Blueprint $t) => $t->string('photo_image', 500)->nullable()->after('initials'));
    }

    public function down(): void
    {
        Schema::table('pages',          fn (Blueprint $t) => $t->dropColumn('hero_image'));
        Schema::table('news',           fn (Blueprint $t) => $t->dropColumn('cover_image'));
        Schema::table('events',         fn (Blueprint $t) => $t->dropColumn('cover_image'));
        Schema::table('partners',       fn (Blueprint $t) => $t->dropColumn('logo_image'));
        Schema::table('programs',       fn (Blueprint $t) => $t->dropColumn('cover_image'));
        Schema::table('program_blocks', fn (Blueprint $t) => $t->dropColumn('image'));
        Schema::table('cases',          fn (Blueprint $t) => $t->dropColumn('cover_image'));
        Schema::table('team_members',   fn (Blueprint $t) => $t->dropColumn('photo_image'));
    }
};

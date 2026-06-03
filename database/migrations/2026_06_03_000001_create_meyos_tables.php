<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /* ========== Глобальные настройки (key/value) ========== */
        Schema::create('settings', function (Blueprint $b) {
            $b->id();
            $b->string('key')->unique();
            $b->json('value')->nullable();
            $b->string('group')->default('general');
            $b->timestamps();
        });

        /* ========== Страницы (8 шт) ========== */
        Schema::create('pages', function (Blueprint $b) {
            $b->id();
            $b->string('slug')->unique();
            $b->string('view')->nullable();        // Имя blade-шаблона
            $b->json('title');                     // translatable
            $b->json('seo_title')->nullable();
            $b->json('seo_description')->nullable();
            $b->json('seo_keywords')->nullable();
            $b->json('hero_tag')->nullable();
            $b->json('hero_h1')->nullable();
            $b->json('hero_lead')->nullable();
            $b->boolean('is_published')->default(true);
            $b->timestamps();
        });

        /* ========== Новости ========== */
        Schema::create('news', function (Blueprint $b) {
            $b->id();
            $b->string('slug')->unique();
            $b->string('category', 50)->index();   // residency / export / edujob / regulation / programs
            $b->date('published_at')->index();
            $b->boolean('is_featured')->default(false);
            $b->boolean('is_published')->default(true);
            $b->json('title');
            $b->json('preview')->nullable();
            $b->json('content');
            $b->json('image_alt')->nullable();
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Мероприятия ========== */
        Schema::create('events', function (Blueprint $b) {
            $b->id();
            $b->string('slug')->unique();
            $b->string('category', 50)->index();   // forum / export / edujob / round-table / exhibition / business-breakfast
            $b->date('event_date')->index();
            $b->date('end_date')->nullable();
            $b->time('start_time')->nullable();
            $b->time('end_time')->nullable();
            $b->json('city')->nullable();
            $b->json('location')->nullable();
            $b->boolean('is_featured')->default(false);
            $b->boolean('is_published')->default(true);
            $b->json('title');
            $b->json('preview')->nullable();
            $b->json('description')->nullable();
            $b->json('image_alt')->nullable();
            $b->unsignedInteger('expected_attendees')->nullable();
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Партнёры / резиденты ========== */
        Schema::create('partners', function (Blueprint $b) {
            $b->id();
            $b->string('slug')->unique();
            $b->string('category', 50)->index();   // production / design / materials / logistics / gov / finance
            $b->json('name');
            $b->json('description')->nullable();
            $b->string('logo_text', 30)->nullable(); // запасной текст для логотипа
            $b->string('website_url')->nullable();
            $b->string('registry_id', 30)->nullable();
            $b->boolean('is_published')->default(true);
            $b->boolean('show_on_home')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Команда (совет директоров) ========== */
        Schema::create('team_members', function (Blueprint $b) {
            $b->id();
            $b->json('name');
            $b->json('role');
            $b->string('initials', 5)->nullable();
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== История ассоциации (timeline) ========== */
        Schema::create('timeline_items', function (Blueprint $b) {
            $b->id();
            $b->string('year', 10);
            $b->json('title');
            $b->json('description');
            $b->boolean('is_highlight')->default(false);
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Бизнес-кейсы ========== */
        Schema::create('cases', function (Blueprint $b) {
            $b->id();
            $b->json('tag');                       // "Кейс 01 · Производство"
            $b->json('title');
            $b->json('description');
            $b->json('metric1_value')->nullable();
            $b->json('metric1_label')->nullable();
            $b->json('metric2_value')->nullable();
            $b->json('metric2_label')->nullable();
            $b->json('metric3_value')->nullable();
            $b->json('metric3_label')->nullable();
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Программы ассоциации ========== */
        Schema::create('programs', function (Blueprint $b) {
            $b->id();
            $b->string('slug')->unique();
            $b->string('icon', 50)->nullable();    // Material symbol name
            $b->json('chip')->nullable();          // "EduJob · Флагман"
            $b->json('title');
            $b->json('description');
            $b->boolean('is_flagship')->default(false);
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Преимущества (6 карточек) ========== */
        Schema::create('benefits', function (Blueprint $b) {
            $b->id();
            $b->string('icon', 50)->nullable();
            $b->json('title');
            $b->json('description');
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Льготы — строки таблицы ========== */
        Schema::create('tax_rows', function (Blueprint $b) {
            $b->id();
            $b->json('parameter');                 // Параметр
            $b->json('standard_rate');             // 15%
            $b->json('resident_rate');             // 7,5%
            $b->json('savings');                   // −50%
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Шаги вступления (4 шт) ========== */
        Schema::create('join_steps', function (Blueprint $b) {
            $b->id();
            $b->json('title');
            $b->json('description');
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Проблема → Решение ========== */
        Schema::create('pain_solution_rows', function (Blueprint $b) {
            $b->id();
            $b->json('pain_title');
            $b->json('pain_description');
            $b->json('solution_title');
            $b->json('solution_description');
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== FAQ ========== */
        Schema::create('faqs', function (Blueprint $b) {
            $b->id();
            $b->json('question');
            $b->json('answer');
            $b->string('page_slug', 50)->default('home')->index();
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Документы (правовая база) ========== */
        Schema::create('documents', function (Blueprint $b) {
            $b->id();
            $b->json('title');
            $b->string('file_path')->nullable();
            $b->string('file_name')->nullable();
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Сертификаты / соглашения ========== */
        Schema::create('certifications', function (Blueprint $b) {
            $b->id();
            $b->string('icon', 50)->nullable();
            $b->json('title');
            $b->json('description');
            $b->boolean('is_published')->default(true);
            $b->unsignedInteger('sort')->default(0);
            $b->timestamps();
        });

        /* ========== Заявки на вступление ========== */
        Schema::create('membership_applications', function (Blueprint $b) {
            $b->id();
            $b->string('status', 30)->default('new')->index(); // new / in_progress / approved / rejected
            $b->string('company');
            $b->string('name');
            $b->string('email');
            $b->string('phone');
            $b->string('category', 100)->nullable();
            $b->string('volume', 100)->nullable();
            $b->text('message')->nullable();
            $b->string('source_page', 100)->nullable();
            $b->timestamps();
        });

        /* ========== Регистрации на мероприятия ========== */
        Schema::create('event_registrations', function (Blueprint $b) {
            $b->id();
            $b->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $b->string('event_name')->nullable();
            $b->string('company');
            $b->string('name');
            $b->string('email');
            $b->string('phone');
            $b->unsignedInteger('attendees_count')->default(1);
            $b->string('status', 30)->default('new')->index();
            $b->timestamps();
        });

        /* ========== Сообщения из формы контактов ========== */
        Schema::create('contact_messages', function (Blueprint $b) {
            $b->id();
            $b->string('name');
            $b->string('company')->nullable();
            $b->string('email');
            $b->string('phone')->nullable();
            $b->string('topic', 100)->nullable();
            $b->text('message');
            $b->string('status', 30)->default('new')->index();
            $b->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('membership_applications');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('pain_solution_rows');
        Schema::dropIfExists('join_steps');
        Schema::dropIfExists('tax_rows');
        Schema::dropIfExists('benefits');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('cases');
        Schema::dropIfExists('timeline_items');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('events');
        Schema::dropIfExists('news');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('settings');
    }
};

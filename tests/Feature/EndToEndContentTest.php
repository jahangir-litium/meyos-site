<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\News;
use App\Models\Partner;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Тест: вношу данные в БД (как сделал бы менеджер через админку),
 * затем проверяю что они отображаются на фронте.
 */
class EndToEndContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_partner_with_website_url_renders_as_link(): void
    {
        // Менеджер вводит ссылку в админке
        $p = Partner::first();
        $p->update(['website_url' => 'https://example.com']);

        $r = $this->get('/partners');
        $r->assertStatus(200);
        $r->assertSee('href="https://example.com"', false);
        $r->assertSee('target="_blank"', false);
    }

    public function test_partner_without_url_is_not_link(): void
    {
        // Гарантируем что партнёр без URL не превращается в <a>
        Partner::query()->update(['website_url' => null]);

        $r = $this->get('/partners');
        $r->assertStatus(200);
        // Карточка существует, но не как <a>
        $r->assertSee('data-partner-card', false);
        $r->assertDontSee('target="_blank"', false);
    }

    public function test_partner_logo_uploaded_renders_image(): void
    {
        Storage::fake('public');

        $p = Partner::first();
        $file = UploadedFile::fake()->image('logo.png', 200, 60);
        $path = $file->storeAs('partners', 'qa-logo.png', 'public');

        $p->update(['logo_image' => $path]);

        $r = $this->get('/partners');
        $r->assertStatus(200);
        $r->assertSee('storage/partners/qa-logo.png', false);
    }

    public function test_news_cover_image_renders(): void
    {
        Storage::fake('public');

        $news = News::first();
        $file = UploadedFile::fake()->image('cover.jpg', 1200, 675);
        $path = $file->storeAs('news', 'qa-cover.jpg', 'public');

        $news->update(['cover_image' => $path, 'is_featured' => true]);

        $r = $this->get('/news');
        $r->assertStatus(200);
        $r->assertSee('storage/news/qa-cover.jpg', false);
    }

    public function test_documents_show_on_residency_page(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('charter.pdf', 100);
        $path = $file->storeAs('documents', 'qa-charter.pdf', 'public');

        Document::create([
            'title'     => ['ru' => 'Устав Тестовый'],
            'file_path' => $path,
            'file_name' => 'charter.pdf',
        ]);

        $r = $this->get('/residency');
        $r->assertStatus(200);
        $r->assertSee('Устав Тестовый');
        $r->assertSee('storage/documents/qa-charter.pdf', false);
    }

    public function test_logo_renders_in_header_when_uploaded(): void
    {
        Storage::fake('public');

        $logo = UploadedFile::fake()->image('logo.png', 200, 60);
        $path = $logo->storeAs('branding', 'qa-logo.png', 'public');

        Setting::put('logo_path', $path);

        $r = $this->get('/');
        $r->assertStatus(200);
        $r->assertSee('storage/branding/qa-logo.png', false);
    }

    public function test_news_featured_is_compact(): void
    {
        // Проверяем что на /news featured-блок имеет grid 2-column раскладку
        News::query()->update(['is_featured' => false]);
        News::first()->update(['is_featured' => true, 'is_published' => true]);

        $r = $this->get('/news');
        $r->assertStatus(200);
        // featured-grid класс должен присутствовать (новая компактная вёрстка)
        $r->assertSee('featured-grid', false);
    }
}

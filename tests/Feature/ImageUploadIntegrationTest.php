<?php

namespace Tests\Feature;

use App\Models\BusinessCase;
use App\Models\Document;
use App\Models\Event;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Program;
use App\Models\Setting;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Сквозной тест: симулируем загрузку картинки через FileUpload во ВСЕХ местах админки,
 * сохраняем модель, проверяем что:
 *   1. файл реально лёг в storage/app/public/<directory>/
 *   2. путь сохранился в БД-колонке
 *   3. фронтенд-страница рендерит storage/<path> в <img>
 */
class ImageUploadIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    /** Helper — имитация того, что Filament положил файл по пути. */
    private function fakeUploadedTo(string $directory, string $name = 'qa.jpg'): string
    {
        $file = UploadedFile::fake()->image($name, 800, 450);
        return $file->storeAs($directory, $name, 'public');
    }

    public function test_news_cover_uploads_and_renders(): void
    {
        $path = $this->fakeUploadedTo('news', 'qa-news-cover.jpg');
        $news = News::first();
        $news->update(['cover_image' => $path, 'is_featured' => true]);

        Storage::disk('public')->assertExists($path);
        $this->assertSame($path, $news->fresh()->cover_image);

        $this->get('/news')->assertSee("storage/$path", false);
    }

    public function test_news_gallery_uploads_and_renders(): void
    {
        $paths = [
            $this->fakeUploadedTo('news/gallery', 'g1.jpg'),
            $this->fakeUploadedTo('news/gallery', 'g2.jpg'),
            $this->fakeUploadedTo('news/gallery', 'g3.jpg'),
        ];

        $news = News::create([
            'slug' => 'qa-gallery-test', 'category' => 'export',
            'title' => ['ru' => 'С галереей'], 'preview' => ['ru' => 'p'], 'content' => ['ru' => 'c'],
            'gallery_images' => $paths,
            'published_at' => now()->subDay(), 'is_published' => true,
        ]);

        foreach ($paths as $p) Storage::disk('public')->assertExists($p);
        $r = $this->get('/news/' . $news->slug);
        foreach ($paths as $p) $r->assertSee("storage/$p", false);
    }

    public function test_news_seo_image_uploads(): void
    {
        $path = $this->fakeUploadedTo('news/og', 'qa-og.jpg');
        $news = News::create([
            'slug' => 'qa-og-test', 'category' => 'export',
            'title' => ['ru' => 'OG-тест'], 'preview' => ['ru' => 'p'], 'content' => ['ru' => 'c'],
            'seo_image' => $path,
            'published_at' => now()->subDay(), 'is_published' => true,
        ]);

        Storage::disk('public')->assertExists($path);
        $r = $this->get('/news/' . $news->slug);
        $r->assertSee("storage/$path", false);  // в og:image
    }

    public function test_event_cover_uploads_and_renders(): void
    {
        $path = $this->fakeUploadedTo('events', 'qa-event.jpg');
        $event = Event::create([
            'slug' => 'qa-event', 'category' => 'exhibition',
            'event_date' => now()->addDays(7),
            'title' => ['ru' => 'Тест'], 'description' => ['ru' => 'd'],
            'cover_image' => $path,
            'is_published' => true,
        ]);
        Storage::disk('public')->assertExists($path);
        $this->get('/events/' . $event->slug)->assertSee("storage/$path", false);
    }

    public function test_program_cover_uploads(): void
    {
        $path = $this->fakeUploadedTo('programs', 'qa-prog.jpg');
        $p = Program::first();
        $p->update(['cover_image' => $path]);
        Storage::disk('public')->assertExists($path);
        $this->assertSame($path, $p->fresh()->cover_image);
    }

    public function test_partner_logo_uploads_and_renders(): void
    {
        $path = $this->fakeUploadedTo('partners', 'qa-logo.png');
        $partner = Partner::first();
        $partner->update(['logo_image' => $path]);
        Storage::disk('public')->assertExists($path);
        $this->get('/partners')->assertSee("storage/$path", false);
    }

    public function test_team_photo_uploads(): void
    {
        $path = $this->fakeUploadedTo('team', 'qa-photo.jpg');
        $member = TeamMember::first();
        if (!$member) {
            $member = TeamMember::create([
                'name' => ['ru' => 'Тест'], 'role' => ['ru' => 'Должность'],
                'photo_image' => $path, 'is_published' => true,
            ]);
        } else {
            $member->update(['photo_image' => $path]);
        }
        Storage::disk('public')->assertExists($path);
        $this->assertSame($path, $member->fresh()->photo_image);
    }

    public function test_business_case_cover_uploads(): void
    {
        $path = $this->fakeUploadedTo('cases', 'qa-case.jpg');
        $case = BusinessCase::first();
        if (!$case) {
            $case = BusinessCase::create([
                'tag' => ['ru' => 'Кейс 01'], 'title' => ['ru' => 'Тест'], 'description' => ['ru' => 'd'],
                'cover_image' => $path, 'is_published' => true,
            ]);
        } else {
            $case->update(['cover_image' => $path]);
        }
        Storage::disk('public')->assertExists($path);
        $this->assertSame($path, $case->fresh()->cover_image);
    }

    public function test_page_hero_uploads(): void
    {
        $path = $this->fakeUploadedTo('pages', 'qa-hero.jpg');
        $page = Page::where('slug', 'about')->first();
        if ($page) {
            $page->update(['hero_image' => $path]);
            Storage::disk('public')->assertExists($path);
            $this->assertSame($path, $page->fresh()->hero_image);
        } else {
            $this->markTestSkipped('Page about not seeded');
        }
    }

    public function test_settings_logo_uploads_and_renders_in_header(): void
    {
        $path = $this->fakeUploadedTo('branding', 'qa-site-logo.png');
        Setting::put('logo_path', $path);
        Storage::disk('public')->assertExists($path);
        $this->get('/')->assertSee("storage/$path", false);
    }

    public function test_document_file_uploads(): void
    {
        $file = UploadedFile::fake()->create('charter.pdf', 100, 'application/pdf');
        $path = $file->storeAs('documents', 'qa-charter.pdf', 'public');

        $doc = Document::create([
            'title' => ['ru' => 'Устав QA'],
            'file_path' => $path,
            'file_name' => 'charter.pdf',
            'is_published' => true,
        ]);

        Storage::disk('public')->assertExists($path);
        $r = $this->get('/residency');
        $r->assertStatus(200);
        $r->assertSee("storage/$path", false);
        $r->assertSee('Устав QA');
    }
}

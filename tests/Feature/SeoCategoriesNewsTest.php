<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeoCategoriesNewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_categories_seeder_populates_three_types(): void
    {
        $this->assertGreaterThan(0, Category::ofType('news')->count());
        $this->assertGreaterThan(0, Category::ofType('partners')->count());
        $this->assertGreaterThan(0, Category::ofType('events')->count());
    }

    public function test_news_all_categories_from_db(): void
    {
        Category::flushCache();
        $cats = News::allCategories('ru');
        $this->assertArrayHasKey('residency', $cats);
        $this->assertSame('Резидентство', $cats['residency']);
    }

    public function test_news_all_categories_localised(): void
    {
        Category::flushCache();
        $catsUz = News::allCategories('uz');
        $this->assertArrayHasKey('residency', $catsUz);
        $this->assertSame('Rezidentlik', $catsUz['residency']);
    }

    public function test_per_record_seo_overrides_default(): void
    {
        $news = News::create([
            'slug'         => 'qa-seo-test',
            'category'     => 'export',
            'title'        => ['ru' => 'Обычный заголовок'],
            'preview'      => ['ru' => 'Обычное превью'],
            'content'      => ['ru' => 'Содержание'],
            'seo_title'    => ['ru' => 'КАСТОМ SEO TITLE'],
            'seo_description' => ['ru' => 'КАСТОМ SEO DESCRIPTION'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news/' . $news->slug);
        $r->assertStatus(200);
        $r->assertSee('КАСТОМ SEO TITLE');
        $r->assertSee('КАСТОМ SEO DESCRIPTION');
    }

    public function test_news_gallery_renders_slider(): void
    {
        Storage::fake('public');
        $img = UploadedFile::fake()->image('a.jpg')->storeAs('news/gallery', 'g1.jpg', 'public');

        $news = News::create([
            'slug'         => 'qa-gallery',
            'category'     => 'export',
            'title'        => ['ru' => 'С галереей'],
            'preview'      => ['ru' => 'p'],
            'content'      => ['ru' => 'c'],
            'gallery_images' => ['news/gallery/g1.jpg'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news/' . $news->slug);
        $r->assertStatus(200);
        $r->assertSee('id="news-gallery"', false);
        $r->assertSee('storage/news/gallery/g1.jpg', false);
    }

    public function test_news_cta_button_to_event(): void
    {
        $event = Event::first();
        if (!$event) {
            $event = Event::create([
                'slug' => 'qa-evt',
                'category' => 'exhibition',
                'event_date' => now()->addDays(10),
                'title' => ['ru' => 'Тест-событие'],
                'is_published' => true,
            ]);
        }

        $news = News::create([
            'slug'         => 'qa-cta',
            'category'     => 'export',
            'title'        => ['ru' => 'С CTA'],
            'preview'      => ['ru' => 'p'],
            'content'      => ['ru' => 'c'],
            'cta_event_id' => $event->id,
            'cta_text'     => ['ru' => 'Записаться на мероприятие'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news/' . $news->slug);
        $r->assertStatus(200);
        $r->assertSee('Записаться на мероприятие');
        $r->assertSee(route('events.show', $event->slug), false);
    }

    public function test_news_cta_button_to_external_url(): void
    {
        $news = News::create([
            'slug'         => 'qa-cta-ext',
            'category'     => 'export',
            'title'        => ['ru' => 'CTA URL'],
            'preview'      => ['ru' => 'p'],
            'content'      => ['ru' => 'c'],
            'cta_url'      => 'https://example.com/lp',
            'cta_text'     => ['ru' => 'Перейти на лендинг'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news/' . $news->slug);
        $r->assertStatus(200);
        $r->assertSee('https://example.com/lp', false);
        $r->assertSee('target="_blank"', false);
    }

    public function test_event_schema_org_renders(): void
    {
        $event = Event::create([
            'slug' => 'qa-event-schema',
            'category' => 'exhibition',
            'event_date' => now()->addDays(20),
            'title' => ['ru' => 'Schema-тест'],
            'description' => ['ru' => 'desc'],
            'is_published' => true,
        ]);

        $r = $this->get('/events/' . $event->slug);
        $r->assertStatus(200);
        $r->assertSee('"@type":"Event"', false);
        $r->assertSee('"@type":"BreadcrumbList"', false);
    }

    public function test_flash_error_renders_on_validation_fail(): void
    {
        // Пустая форма — все обязательные поля falls
        $r = $this->post('/submit/membership', []);
        $r->assertStatus(302); // редирект назад
        $r->assertSessionHasErrors(['company', 'name', 'email', 'phone']);
    }

    public function test_success_message_returned(): void
    {
        // Тест-окружение использует APP_LOCALE=en — проверяем что вообще отдаётся success-сообщение
        $r = $this->post('/submit/membership', [
            'company' => 'QA', 'name' => 'X', 'email' => 'a@b.uz', 'phone' => '+998',
        ]);
        $r->assertStatus(302);
        $r->assertSessionHas('success');
        $this->assertStringContainsString('within one business day', session('success'));
    }

    public function test_faqs_render_on_home(): void
    {
        // Локаль en — берём английский фрагмент FAQ из сидера
        $r = $this->get('/');
        $r->assertStatus(200);
        $r->assertSee('membership', false); // встречается в FAQ-вопросах на EN
    }
}

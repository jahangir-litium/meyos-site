<?php

namespace Tests\Feature;

use App\Models\MembershipApplication;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_sitemap_xml_returns_valid_xml(): void
    {
        // Главный sitemap теперь sitemap-index, ссылающийся на под-карты
        $r = $this->get('/sitemap.xml');
        $r->assertStatus(200);
        $r->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $r->assertSee('<sitemapindex', false);
        $r->assertSee('sitemap-pages.xml', false);
        $r->assertSee('sitemap-news.xml', false);

        // Под-карта pages содержит реальные URL
        $r2 = $this->get('/sitemap-pages.xml');
        $r2->assertStatus(200);
        $r2->assertSee('<urlset', false);
        $r2->assertSee('<loc>'.url('/').'</loc>', false);
        // hreflang
        $r2->assertSee('hreflang="ru"', false);
        $r2->assertSee('hreflang="uz"', false);
        $r2->assertSee('hreflang="en"', false);
    }

    public function test_robots_txt_disallows_admin(): void
    {
        $r = $this->get('/robots.txt');
        $r->assertStatus(200);
        $r->assertSee('Disallow: /admin');
        $r->assertSee('Sitemap:');
    }

    public function test_news_search_filters_results(): void
    {
        News::query()->update(['is_published' => false]);
        News::create([
            'slug'         => 'qa-uniqkey',
            'category'     => 'export',
            'title'        => ['ru' => 'УникальныйЗаголовокQA'],
            'preview'      => ['ru' => 'preview'],
            'content'      => ['ru' => 'content'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news?q=УникальныйЗаголовокQA');
        $r->assertStatus(200);
        $r->assertSee('УникальныйЗаголовокQA');

        $r2 = $this->get('/news?q=Несуществующий123XYZ');
        $r2->assertStatus(200);
        $r2->assertSee('Несуществующий123XYZ'); // эхо запроса в no-results
    }

    public function test_news_show_has_schema_org_and_og(): void
    {
        $news = News::create([
            'slug'         => 'qa-show-test',
            'category'     => 'export',
            'title'        => ['ru' => 'SEO тест'],
            'preview'      => ['ru' => 'preview'],
            'content'      => ['ru' => 'content'],
            'published_at' => now()->subDay(),
            'is_published' => true,
        ]);

        $r = $this->get('/news/' . $news->slug);
        $r->assertStatus(200);
        // OG
        $r->assertSee('og:type', false);
        $r->assertSee('article', false);
        // Schema.org
        $r->assertSee('"@type":"NewsArticle"', false);
        $r->assertSee('"@type":"BreadcrumbList"', false);
        // Breadcrumbs UI
        $r->assertSee('aria-label="Breadcrumb"', false);
    }

    public function test_utm_session_persists_in_application(): void
    {
        // UTM первого касания
        $this->withSession(['utm' => [
            'utm_source'   => 'instagram',
            'utm_medium'   => 'cpc',
            'utm_campaign' => 'launch-2026',
        ]])->post('/submit/membership', [
            'company' => 'QA Ltd', 'name' => 'QA', 'email' => 'qa@x.uz',
            'phone' => '+998 999', 'message' => 'test',
        ]);

        $app = MembershipApplication::latest()->first();
        $this->assertSame('instagram', $app->utm_source);
        $this->assertSame('cpc', $app->utm_medium);
        $this->assertSame('launch-2026', $app->utm_campaign);
    }

    public function test_fab_ask_button_present_on_pages(): void
    {
        $r = $this->get('/');
        $r->assertStatus(200);
        $r->assertSee('id="fab-ask-btn"', false);
        $r->assertSee('id="fab-modal"', false);
    }

    public function test_softdelete_news_hides_from_frontend(): void
    {
        $news = News::first();
        $slug = $news->slug;
        $news->delete(); // soft delete

        $r = $this->get('/news/' . $slug);
        $r->assertStatus(404);
    }
}

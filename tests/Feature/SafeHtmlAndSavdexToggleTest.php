<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Support\SafeHtml;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Покрывает:
 *  - SafeHtml::clean() — XSS защита rich-editor контента
 *  - savdex_enabled toggle — скрытие /listings + блока на главной + пункта в header
 */
class SafeHtmlAndSavdexToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /* ========== SafeHtml ========== */

    public function test_safehtml_strips_script_tag(): void
    {
        $dirty = '<p>hello</p><script>alert("xss")</script>';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsString('alert', $clean);
        $this->assertStringContainsString('<p>hello</p>', $clean);
    }

    public function test_safehtml_strips_onclick_attribute(): void
    {
        $dirty = '<a href="https://example.com" onclick="alert(1)">link</a>';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringContainsString('href="https://example.com"', $clean);
    }

    public function test_safehtml_blocks_javascript_href(): void
    {
        $dirty = '<a href="javascript:alert(1)">click</a>';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringNotContainsString('javascript:', $clean);
    }

    public function test_safehtml_blocks_data_url_in_iframe(): void
    {
        $dirty = '<iframe src="data:text/html,<script>bad</script>"></iframe>';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringNotContainsString('<iframe', $clean);
    }

    public function test_safehtml_allows_safe_images(): void
    {
        $dirty = '<img src="https://example.com/pic.png" alt="pic">';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringContainsString('<img', $clean);
        $this->assertStringContainsString('alt="pic"', $clean);
    }

    public function test_safehtml_allows_data_image(): void
    {
        $dirty = '<img src="data:image/png;base64,iVBOR" alt="base64">';
        $clean = SafeHtml::clean($dirty);
        $this->assertStringContainsString('data:image/png', $clean);
    }

    public function test_safehtml_returns_empty_for_null(): void
    {
        $this->assertSame('', SafeHtml::clean(null));
        $this->assertSame('', SafeHtml::clean(''));
    }

    /* ========== Savdex toggle ========== */

    public function test_listings_page_404_when_savdex_disabled(): void
    {
        Setting::put('savdex_enabled', false);
        $this->get('/listings')->assertStatus(404);
    }

    public function test_listings_page_200_when_savdex_enabled(): void
    {
        Setting::put('savdex_enabled', true);
        $this->get('/listings')->assertStatus(200);
    }

    public function test_header_hides_listings_link_when_savdex_disabled(): void
    {
        Setting::put('savdex_enabled', false);
        $r = $this->get('/');
        $r->assertStatus(200);
        $r->assertDontSee('/listings');
    }

    public function test_header_shows_listings_link_when_savdex_enabled(): void
    {
        Setting::put('savdex_enabled', true);
        $r = $this->get('/');
        $r->assertStatus(200);
        $r->assertSee('/listings');
    }

    public function test_sitemap_pages_includes_listings_only_when_enabled(): void
    {
        Setting::put('savdex_enabled', true);
        $r1 = $this->get('/sitemap-pages.xml');
        $r1->assertStatus(200);
        $r1->assertSee('/listings', false);

        // Переключаем и чистим кэш sitemap
        Setting::put('savdex_enabled', false);
        \Illuminate\Support\Facades\Cache::forget('sitemap-pages.xml');

        $r2 = $this->get('/sitemap-pages.xml');
        $r2->assertStatus(200);
        $r2->assertDontSee('/listings', false);
    }

    public function test_sitemap_legislation_exists(): void
    {
        $r = $this->get('/sitemap-legislation.xml');
        $r->assertStatus(200);
        $r->assertSee('pp-193-podderzhka-mebeli-2025', false);
    }
}

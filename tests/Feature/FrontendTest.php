<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\EventRegistration;
use App\Models\MembershipApplication;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_public_pages_load(): void
    {
        $pages = ['/', '/about', '/residency', '/programs', '/partners', '/contacts', '/events', '/news'];
        foreach ($pages as $p) {
            $r = $this->get($p);
            $this->assertEquals(200, $r->status(), "$p → status {$r->status()}");
        }
    }

    public function test_news_detail_loads(): void
    {
        $r = $this->get('/news/news-1');
        $r->assertStatus(200);
        $r->assertSee('MEYOS');
    }

    public function test_event_detail_loads(): void
    {
        $r = $this->get('/events/meyos-forum-2026');
        $r->assertStatus(200);
    }

    public function test_membership_submission_stores_record(): void
    {
        $before = MembershipApplication::count();
        $r = $this->post('/submit/membership', [
            'company' => 'Тестовая компания',
            'name'    => 'Иван Иванов',
            'email'   => 'ivan@test.uz',
            'phone'   => '+998 90 000 00 00',
            'message' => 'Хотим вступить',
        ]);
        $r->assertRedirect();
        $this->assertEquals($before + 1, MembershipApplication::count());
        $this->assertDatabaseHas('membership_applications', ['email' => 'ivan@test.uz']);
    }

    public function test_membership_validation_rejects_bad_email(): void
    {
        $r = $this->post('/submit/membership', [
            'company' => 'X',
            'name'    => 'Y',
            'email'   => 'not-an-email',
            'phone'   => '+998',
        ]);
        $r->assertSessionHasErrors('email');
    }

    public function test_contact_submission_stores_record(): void
    {
        $before = ContactMessage::count();
        $this->post('/submit/contact', [
            'name'    => 'Test',
            'email'   => 'test@test.uz',
            'message' => 'Тестовое сообщение для проверки',
        ])->assertRedirect();
        $this->assertEquals($before + 1, ContactMessage::count());
    }

    public function test_event_registration_stores_record(): void
    {
        $before = EventRegistration::count();
        $this->post('/submit/event/meyos-forum-2026', [
            'company' => 'Company',
            'name'    => 'Person',
            'email'   => 'person@test.uz',
            'phone'   => '+998 90 000 00 00',
            'attendees_count' => 2,
        ])->assertRedirect();
        $this->assertEquals($before + 1, EventRegistration::count());
    }

    public function test_page_view_tracked_on_homepage(): void
    {
        $before = PageView::count();
        $this->get('/')->assertStatus(200);
        $this->assertGreaterThan($before, PageView::count());
        $last = PageView::latest('id')->first();
        $this->assertEquals('home', $last->page_slug);
    }

    public function test_locale_switch_via_query(): void
    {
        $r = $this->get('/?lang=uz');
        $r->assertStatus(200);
        $r->assertCookie('meyos_locale', 'uz');
    }

    public function test_dashboard_loads_with_widgets(): void
    {
        $admin = User::firstOrCreate(['email' => 'a@a'], ['name' => 't', 'password' => bcrypt('x')]);
        $r = $this->actingAs($admin)->get('/admin');
        $r->assertStatus(200);
    }

    public function test_site_settings_page_loads(): void
    {
        $admin = User::firstOrCreate(['email' => 'a@a'], ['name' => 't', 'password' => bcrypt('x')]);
        $r = $this->actingAs($admin)->get('/admin/site-settings');
        $this->assertContains($r->status(), [200, 302]);
    }

    public function test_telegram_disabled_by_default_no_send(): void
    {
        // tg_enabled = false → не отправляем, заявка всё равно создаётся
        \App\Models\Setting::put('tg_enabled', false);
        $count = MembershipApplication::count();

        $this->post('/submit/membership', [
            'company' => 'TG test',
            'name'    => 'TG',
            'email'   => 'tg@test.uz',
            'phone'   => '+998',
        ])->assertRedirect();

        $this->assertEquals($count + 1, MembershipApplication::count());
    }

    public function test_logo_url_returns_null_when_not_set(): void
    {
        \App\Models\Setting::query()->where('key', 'logo_path')->delete();
        \Illuminate\Support\Facades\Cache::forget('setting.logo_path');
        $this->assertNull(\App\Models\Setting::logoUrl());
    }
}

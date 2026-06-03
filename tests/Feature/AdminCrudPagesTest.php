<?php

namespace Tests\Feature;

use App\Models\BusinessCase;
use App\Models\Event;
use App\Models\News;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Program;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Реальное мануальное тестирование — открываем каждую create/edit
 * страницу в админке как авторизованный пользователь и проверяем 200.
 * Это ловит все BadMethodCallException и аналоги, которые не видны
 * на List-странице (форма рендерится только в create/edit).
 */
class AdminCrudPagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::firstOrCreate(['email' => 'admin@meyos.uz'], [
            'name' => 'Admin', 'password' => bcrypt('test'),
        ]);
    }

    public function test_all_create_pages_load(): void
    {
        $routes = [
            '/admin/news/create',
            '/admin/events/create',
            '/admin/partners/create',
            '/admin/programs/create',
            '/admin/team-members/create',
            '/admin/benefits/create',
            '/admin/business-cases/create',
            '/admin/timeline-items/create',
            '/admin/faqs/create',
            '/admin/tax-rows/create',
            '/admin/join-steps/create',
            '/admin/pain-solution-rows/create',
            '/admin/certifications/create',
            '/admin/documents/create',
            '/admin/membership-applications/create',
            '/admin/event-registrations/create',
            '/admin/contact-messages/create',
        ];
        foreach ($routes as $r) {
            $resp = $this->actingAs($this->admin)->get($r);
            $this->assertEquals(200, $resp->status(), "$r → status {$resp->status()}");
        }
    }

    public function test_edit_news(): void
    {
        $id = News::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/news/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_edit_event(): void
    {
        $id = Event::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/events/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_edit_partner(): void
    {
        $id = Partner::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/partners/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_edit_program(): void
    {
        $id = Program::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/programs/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_edit_team_member(): void
    {
        $id = TeamMember::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/team-members/$id/edit");
        $this->assertEquals(200, $r->status(), 'TeamMember edit — критичный кейс');
    }

    public function test_edit_business_case(): void
    {
        $id = BusinessCase::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/business-cases/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_edit_page(): void
    {
        $id = Page::first()->id;
        $r = $this->actingAs($this->admin)->get("/admin/pages/$id/edit");
        $this->assertEquals(200, $r->status());
    }

    public function test_list_pages_load(): void
    {
        $routes = [
            '/admin', '/admin/site-settings',
            '/admin/news', '/admin/events', '/admin/partners', '/admin/programs',
            '/admin/team-members', '/admin/benefits', '/admin/business-cases',
            '/admin/timeline-items', '/admin/faqs', '/admin/tax-rows',
            '/admin/join-steps', '/admin/pain-solution-rows', '/admin/certifications',
            '/admin/documents', '/admin/pages',
            '/admin/membership-applications', '/admin/event-registrations',
            '/admin/contact-messages',
        ];
        foreach ($routes as $r) {
            $resp = $this->actingAs($this->admin)->get($r);
            $this->assertEquals(200, $resp->status(), "$r → status {$resp->status()}");
        }
    }
}

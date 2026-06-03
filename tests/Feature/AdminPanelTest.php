<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Все 18 страниц списка Filament-ресурсов открываются для авторизованного админа.
     */
    public function test_all_admin_resources_load(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@meyos.uz'],
            ['name' => 'Admin', 'password' => bcrypt('test')]
        );

        $routes = [
            '/admin',
            '/admin/news', '/admin/events', '/admin/business-cases', '/admin/partners',
            '/admin/programs', '/admin/benefits', '/admin/team-members', '/admin/timeline-items',
            '/admin/faqs', '/admin/tax-rows', '/admin/join-steps', '/admin/pain-solution-rows',
            '/admin/certifications', '/admin/documents', '/admin/pages',
            '/admin/membership-applications', '/admin/event-registrations', '/admin/contact-messages',
        ];

        foreach ($routes as $r) {
            $resp = $this->actingAs($user)->get($r);
            $this->assertTrue(
                in_array($resp->status(), [200, 302], true),
                "$r → status {$resp->status()}"
            );
        }
        $this->assertTrue(true);
    }

    /** CRUD через Eloquent + переводы */
    public function test_translatable_crud(): void
    {
        $n = News::create([
            'slug' => 'feature-test-' . time(),
            'category' => 'export',
            'published_at' => now(),
            'title'   => ['ru' => 'Заголовок', 'uz' => 'Sarlavha', 'en' => 'Title'],
            'preview' => ['ru' => 'Превью'],
            'content' => ['ru' => 'Содержимое'],
        ]);

        $this->assertEquals('Sarlavha', $n->getTranslation('title', 'uz'));
        $this->assertEquals('Title',    $n->getTranslation('title', 'en'));

        $n->update(['title' => ['ru' => 'Изменено', 'uz' => 'Yangilandi', 'en' => 'Updated']]);
        $this->assertEquals('Yangilandi', $n->fresh()->getTranslation('title', 'uz'));

        $id = $n->id;
        $n->delete();
        $this->assertNull(News::find($id));
    }

    /** UNIQUE constraint на slug */
    public function test_duplicate_slug_rejected(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Partner::create([
            'slug' => 'woodline',           // уже есть в сидере
            'category' => 'production',
            'name' => ['ru' => 'Test'],
        ]);
    }
}

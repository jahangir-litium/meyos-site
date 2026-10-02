<?php

namespace Tests\Feature;

use App\Models\LegalAct;
use App\Models\MembershipApplication;
use App\Models\SavdexListing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Покрывает: legislation (index/show), listings (index), honeypot/time-trap
 * SubmissionController, разнесённый throttle по формам.
 */
class LegislationAndFormsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_legislation_index_renders_published_acts(): void
    {
        $r = $this->get('/legislation');
        $r->assertStatus(200);
        $r->assertSee('ПП-193', false);
        $r->assertSee('data-legal-card', false);  // карточка для JS-фильтра
        $r->assertSee('data-legal-filter', false);
    }

    public function test_legislation_show_returns_act_page(): void
    {
        $act = LegalAct::query()->published()->first();
        $this->assertNotNull($act, 'Должен быть хотя бы один опубликованный акт из сидера');

        $r = $this->get('/legislation/' . $act->slug);
        $r->assertStatus(200);
        $r->assertSee($act->getTranslation('title', app()->getLocale(), false));
    }

    public function test_legislation_show_404_for_unknown_slug(): void
    {
        $this->get('/legislation/nonexistent-act-xyz')->assertStatus(404);
    }

    public function test_listings_index_loads(): void
    {
        $r = $this->get('/listings');
        $r->assertStatus(200);
    }

    public function test_membership_honeypot_blocks_bot_silently(): void
    {
        // Бот заполнил скрытое поле `website` → silent drop (успешный ответ, но запись НЕ создана)
        $this->post('/submit/membership', [
            'website' => 'http://evil.com',  // honeypot попался
            'form_ts' => time() - 10,
            'company' => 'Bot Ltd', 'name' => 'Bot', 'email' => 'bot@x.uz',
            'phone' => '+998 1', 'message' => 'spam',
        ])->assertStatus(302);

        $this->assertSame(0, MembershipApplication::where('email', 'bot@x.uz')->count());
    }

    public function test_membership_time_trap_blocks_too_fast_submit(): void
    {
        // Отправка быстрее 3 секунд после загрузки формы → silent drop
        $this->post('/submit/membership', [
            'form_ts' => time() - 1,  // прошла 1 секунда — бот
            'company' => 'Fast Ltd', 'name' => 'Fast', 'email' => 'fast@x.uz',
            'phone' => '+998 1', 'message' => 'spam',
        ])->assertStatus(302);

        $this->assertSame(0, MembershipApplication::where('email', 'fast@x.uz')->count());
    }

    public function test_membership_valid_submission_creates_record(): void
    {
        $this->post('/submit/membership', [
            'form_ts' => time() - 10,  // человек: прошло больше 3 сек
            'website' => '',           // honeypot пуст
            'company' => 'Real Co', 'name' => 'Jane', 'email' => 'jane@real.uz',
            'phone' => '+998 90 000 00 00', 'message' => 'хочу вступить',
        ])->assertStatus(302);

        $this->assertDatabaseHas('membership_applications', ['email' => 'jane@real.uz']);
    }

    public function test_contact_and_membership_throttle_are_independent(): void
    {
        // 3 отправки membership — все пройдут, потом 429
        for ($i = 0; $i < 3; $i++) {
            $this->post('/submit/membership', [
                'form_ts' => time() - 10,
                'company' => "A$i", 'name' => "A$i", 'email' => "a$i@x.uz",
                'phone' => '+998 1', 'message' => 'ok',
            ])->assertStatus(302);
        }
        // 4-я membership должна упереться в лимит
        $this->post('/submit/membership', [
            'form_ts' => time() - 10,
            'company' => 'A4', 'name' => 'A4', 'email' => 'a4@x.uz',
            'phone' => '+998 1', 'message' => 'ok',
        ])->assertStatus(429);

        // А отправка через contact — должна работать: отдельный throttle
        $this->post('/submit/contact', [
            'form_ts' => time() - 10,
            'name' => 'Bob', 'email' => 'bob@x.uz', 'message' => 'ask',
        ])->assertStatus(302);
    }

    public function test_savdex_listing_scope_visible_hides_is_hidden(): void
    {
        SavdexListing::create([
            'external_id' => 'test-visible',
            'slug' => 'visible-item',
            'title' => 'Visible item',
            'listing_type' => 'offer',
            'source_url' => 'https://savdex.uz/listing/visible-item-1',
            'fetched_at' => now(),
            'is_published' => true,
            'is_hidden' => false,
        ]);
        SavdexListing::create([
            'external_id' => 'test-hidden',
            'slug' => 'hidden-item',
            'title' => 'Hidden item',
            'listing_type' => 'offer',
            'source_url' => 'https://savdex.uz/listing/hidden-item-2',
            'fetched_at' => now(),
            'is_published' => true,
            'is_hidden' => true,
        ]);

        $visibleCount = SavdexListing::visible()->count();
        $allCount     = SavdexListing::count();
        $this->assertSame($allCount - 1, $visibleCount, 'is_hidden должен исключать из scopeVisible');
    }
}

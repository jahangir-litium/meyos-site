<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\Partner;
use App\Support\Slugifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UxImprovementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_slugifier_handles_russian(): void
    {
        $this->assertEquals('novost-pro-eksport', Slugifier::make('Новость про экспорт'));
        $this->assertEquals('mebelnaya-fabrika', Slugifier::make('Мебельная фабрика'));
        $this->assertEquals('edujob-flagman', Slugifier::make('EduJob — флагман'));
    }

    public function test_slugifier_handles_uzbek(): void
    {
        // ў → oʻ
        $s = Slugifier::make('Toʻrtinchi tartibli');
        $this->assertNotEmpty($s);
        $this->assertStringContainsString('tartibli', $s);
    }

    public function test_slugifier_empty(): void
    {
        $this->assertEquals('', Slugifier::make(null));
        $this->assertEquals('', Slugifier::make(''));
    }

    public function test_news_auto_generates_slug_from_russian_title(): void
    {
        $news = News::create([
            'category'     => 'export',
            'published_at' => now(),
            'title'        => ['ru' => 'Тестовая новость про экспорт'],
            'content'      => ['ru' => '...'],
            // slug не задаём!
        ]);

        $this->assertNotEmpty($news->slug, 'Slug должен быть автогенерирован');
        $this->assertStringContainsString('testovaya', $news->slug);
        $this->assertStringNotContainsString('Тестовая', $news->slug, 'Кириллица не должна попадать в slug');
    }

    public function test_partner_auto_generates_slug_from_name(): void
    {
        $p = Partner::create([
            'category' => 'production',
            'name'     => ['ru' => 'Мебельная фабрика Тест'],
        ]);
        $this->assertNotEmpty($p->slug);
        $this->assertStringContainsString('mebelnaya', $p->slug);
    }

    public function test_auto_slug_handles_duplicates(): void
    {
        // Уже есть partner со slug = 'mebel-pro' в сидере
        $existing = Partner::where('slug', 'mebel-pro')->first();
        $this->assertNotNull($existing);

        // Создаём ещё одного с таким же названием
        $p2 = Partner::create([
            'category' => 'production',
            'name'     => ['ru' => 'Mebel Pro'],
        ]);

        $this->assertNotEquals('mebel-pro', $p2->slug, 'Slug должен отличаться от существующего');
        $this->assertStringStartsWith('mebel-pro', $p2->slug, 'Префикс совпадает, но добавлен суффикс');
    }

    public function test_icon_picker_has_options(): void
    {
        $picker = \App\Filament\Support\IconPicker::make('icon');
        $this->assertInstanceOf(\Filament\Forms\Components\Select::class, $picker);
        // Проверим что есть базовые материал-иконки
        $this->assertArrayHasKey('Бизнес и работа', \App\Filament\Support\IconPicker::ICONS);
        $this->assertArrayHasKey('school', \App\Filament\Support\IconPicker::ICONS['Образование']);
    }
}

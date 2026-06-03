<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tests\TestCase;

class UploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_media_library_attach_image_to_news(): void
    {
        Storage::fake('public');

        $news = News::first();
        $file = UploadedFile::fake()->image('cover.jpg', 600, 400);

        $news->addMedia($file)->toMediaCollection('cover');
        $news->refresh();

        $this->assertEquals(1, $news->getMedia('cover')->count(), 'Файл прикреплён к коллекции');
        $this->assertNotEmpty($news->getFirstMediaUrl('cover'), 'Получен публичный URL');
    }

    public function test_partner_logo_upload_persists_and_returns_url(): void
    {
        Storage::fake('public');

        $partner = Partner::first();
        $file = UploadedFile::fake()->image('logo.png', 200, 60);

        $partner->addMedia($file)->toMediaCollection('logo');
        $partner->refresh();

        $url = $partner->getFirstMediaUrl('logo');
        $this->assertNotEmpty($url);
        $this->assertEquals(1, $partner->media->count());
    }

    public function test_filament_temporary_upload_size_allowed(): void
    {
        Storage::fake('public');

        // Файл 3 МБ — раньше падал из-за upload_max_filesize=2M в php.ini
        $file = UploadedFile::fake()->image('big.jpg', 1200, 800)->size(3072);

        $this->assertGreaterThan(2 * 1024 * 1024, $file->getSize(), 'Файл больше 2 МБ');

        $news = News::first();
        $news->addMedia($file)->toMediaCollection('cover');

        $this->assertEquals(1, $news->fresh()->getMedia('cover')->count());
    }

    public function test_filament_uploads_work_without_queue_worker(): void
    {
        // queue.default = sync → конверсии медиа должны выполняться сразу
        $this->assertEquals('sync', config('queue.default'));
        $this->assertFalse(config('media-library.queue_conversions_by_default'));
    }
}

<?php

namespace Tests\Unit;

use App\Support\VideoEmbed;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VideoEmbedTest extends TestCase
{
    #[DataProvider('youTubeUrls')]
    public function test_youtube_variants_produce_embed(string $url, string $expectId): void
    {
        $html = VideoEmbed::html($url);
        $this->assertStringContainsString('youtube-nocookie.com/embed/' . $expectId, $html);
        $this->assertStringContainsString('<iframe', $html);
        $this->assertStringContainsString('allowfullscreen', $html);
    }

    public static function youTubeUrls(): array
    {
        return [
            'watch v='   => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'short link' => ['https://youtu.be/dQw4w9WgXcQ',               'dQw4w9WgXcQ'],
            'embed path' => ['https://www.youtube.com/embed/dQw4w9WgXcQ',  'dQw4w9WgXcQ'],
            'shorts'     => ['https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
            'with query' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=20s', 'dQw4w9WgXcQ'],
        ];
    }

    public function test_instagram_no_longer_supported(): void
    {
        // Instagram embed удалён (не работает надёжно на фронте).
        $html = VideoEmbed::html('https://www.instagram.com/reel/C1xYz_abcde/');
        $this->assertSame('', $html);
    }

    public function test_unknown_url_returns_empty_string(): void
    {
        $this->assertSame('', VideoEmbed::html('https://vimeo.com/12345'));
        $this->assertSame('', VideoEmbed::html('https://example.com'));
        $this->assertSame('', VideoEmbed::html(''));
        $this->assertSame('', VideoEmbed::html(null));
    }

    public function test_is_valid(): void
    {
        $this->assertTrue(VideoEmbed::isValid('https://www.youtube.com/watch?v=abcdefghijk'));
        $this->assertFalse(VideoEmbed::isValid('https://www.instagram.com/reel/C1abcdef/'));
        $this->assertFalse(VideoEmbed::isValid('https://vimeo.com/12345'));
        $this->assertFalse(VideoEmbed::isValid(''));
        $this->assertFalse(VideoEmbed::isValid(null));
    }

    public function test_blocks_javascript_in_url(): void
    {
        // Злой URL содержит javascript: — не должен попасть в вывод
        $html = VideoEmbed::html('javascript:alert(1)');
        $this->assertSame('', $html);
    }

    public function test_provider_detection(): void
    {
        $this->assertSame('youtube', VideoEmbed::provider('https://youtu.be/abc12345678'));
        $this->assertNull(VideoEmbed::provider('https://instagram.com/p/abc12345'));
        $this->assertNull(VideoEmbed::provider('https://vimeo.com/x'));
    }
}

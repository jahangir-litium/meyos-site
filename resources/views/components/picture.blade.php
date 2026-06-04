{{--
  Универсальный <picture> с WebP source + lazy + decoding=async.
  Использование:
    <x-picture src="{{ $news->cover_image }}" alt="..." class="..." ratio="16/9" />

  src — путь относительно storage (например 'news/cover.jpg')
  Автоматически подменяет .webp если он существует (генерируется через php artisan meyos:webp)
--}}
@props([
    'src',                                                  // путь в storage/, например 'news/cover.jpg'
    'alt' => '',
    'class' => '',
    'ratio' => null,                                        // например '16/9' или '4/3'
    'sizes' => '(max-width:640px) 100vw, (max-width:1200px) 50vw, 33vw',
    'loading' => 'lazy',
    'fetchpriority' => null,                                // 'high' для hero-картинок
])
@php
    $original = asset('storage/' . $src);
    $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $src);
    $webpAbs  = storage_path('app/public/' . $webpPath);
    $webp     = file_exists($webpAbs) ? asset('storage/' . $webpPath) : null;
    $style    = $ratio ? "aspect-ratio:$ratio;" : '';
@endphp
<picture {{ $attributes->merge(['class' => $class]) }} style="{{ $style }}">
    @if($webp)
        <source srcset="{{ $webp }}" type="image/webp">
    @endif
    <img src="{{ $original }}"
         alt="{{ $alt }}"
         loading="{{ $loading }}"
         decoding="async"
         @if($fetchpriority) fetchpriority="{{ $fetchpriority }}" @endif
         sizes="{{ $sizes }}"
         class="lazy-img"
         style="width:100%; height:100%; object-fit:cover;">
</picture>

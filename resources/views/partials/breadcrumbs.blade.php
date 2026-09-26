@php
    /**
     * $items — массив ['label' => 'Название', 'url' => 'https://...' | null]
     * Последний элемент выводится как текущая страница без ссылки.
     */
    $cur = app()->getLocale();
    $homeLabel = ['ru' => 'Главная', 'uz' => 'Bosh sahifa', 'en' => 'Home'][$cur] ?? 'Главная';
    $all = array_merge([['label' => $homeLabel, 'url' => url('/')]], $items ?? []);
@endphp

<nav aria-label="Breadcrumb" class="breadcrumbs">
  <ol>
    @foreach ($all as $i => $item)
      @if ($i === count($all) - 1)
        <li aria-current="page">{{ $item['label'] }}</li>
      @else
        <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
        <li aria-hidden="true" class="breadcrumbs__sep">›</li>
      @endif
    @endforeach
  </ol>
</nav>

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type'    => 'BreadcrumbList',
    'itemListElement' => collect($all)->values()->map(fn ($it, $idx) => [
        '@type'    => 'ListItem',
        'position' => $idx + 1,
        'name'     => $it['label'],
        'item'     => $it['url'] ?? url()->current(),
    ])->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

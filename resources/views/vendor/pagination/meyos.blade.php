@if ($paginator->hasPages())
  <nav class="pagination" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
      <span class="pagination__btn is-disabled" aria-disabled="true">‹</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}" class="pagination__btn" rel="prev">‹</a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
      @if (is_string($element))
        <span class="pagination__gap">{{ $element }}</span>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span class="pagination__btn is-active" aria-current="page">{{ $page }}</span>
          @else
            <a href="{{ $url }}" class="pagination__btn">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}" class="pagination__btn" rel="next">›</a>
    @else
      <span class="pagination__btn is-disabled" aria-disabled="true">›</span>
    @endif
  </nav>
@endif

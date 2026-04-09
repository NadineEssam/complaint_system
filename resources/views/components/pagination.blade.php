@if ($paginator->hasPages())
<nav>
  <ul class="pagination">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
      <li class="page-item disabled">
        <span class="page-link">&laquo;</span>
      </li>
    @else
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->appends(request()->query())->previousPageUrl() }}">&laquo;</a>
      </li>
    @endif

    {{-- Page Numbers --}}
    @php
      $start = max($paginator->currentPage() - 2, 1);
      $end = min($paginator->currentPage() + 2, $paginator->lastPage());
    @endphp

    @if ($start > 1)
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
      </li>
      @if ($start > 2)
        <li class="page-item disabled"><span class="page-link">...</span></li>
      @endif
    @endif

    @for ($i = $start; $i <= $end; $i++)
      <li class="page-item {{ $i == $paginator->currentPage() ? 'active' : '' }}">
        <a class="page-link" href="{{ $paginator->appends(request()->query())->url($i) }}">{{ $i }}</a>
      </li>
    @endfor

    @if ($end < $paginator->lastPage())
      @if ($end < $paginator->lastPage() - 1)
        <li class="page-item disabled"><span class="page-link">...</span></li>
      @endif
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
          {{ $paginator->lastPage() }}
        </a>
      </li>
    @endif

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <li class="page-item">
        <a class="page-link" href="{{ $paginator->appends(request()->query())->nextPageUrl() }}">&raquo;</a>
      </li>
    @else
      <li class="page-item disabled">
        <span class="page-link">&raquo;</span>
      </li>
    @endif

  </ul>
</nav>
@endif
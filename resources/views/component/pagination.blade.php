<div class="d-flex justify-content-between align-items-center flex-wrap">
    {{-- عرض عدد النتائج --}}
    <div class="text-muted mb-2">
        Show <strong>{{ $items->firstItem() ?? 0 }}</strong> To <strong>{{ $items->lastItem() ?? 0 }}</strong> 
        From <strong>{{ $items->total() }}</strong> Result
    </div>

    {{-- الـ Pagination --}}
    @if ($items->hasPages())
        <nav aria-label="Page navigation">
            <ul class="pagination mb-2">
                {{-- Previous Button --}}
                <li class="page-item {{ $items->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link px-5" href="{{ $items->previousPageUrl() }}">
                        Previous
                    </a>
                </li>

                {{-- First Page --}}
                @if ($items->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $items->url(1) }}">1</a>
                    </li>
                    @if ($items->currentPage() > 4)
                        <li class="page-item disabled">
                            <a class="page-link">...</a>
                        </li>
                    @endif
                @endif

                {{-- Page Numbers --}}
                @for ($i = max(1, $items->currentPage() - 2); $i <= min($items->lastPage(), $items->currentPage() + 2); $i++)
                    <li class="page-item {{ $i == $items->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $items->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Last Page --}}
                @if ($items->currentPage() < $items->lastPage() - 2)
                    @if ($items->currentPage() < $items->lastPage() - 3)
                        <li class="page-item disabled">
                            <a class="page-link">...</a>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $items->url($items->lastPage()) }}">{{ $items->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Button --}}
                <li class="page-item {{ !$items->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link px-5" href="{{ $items->nextPageUrl() }}">
                        Next
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>
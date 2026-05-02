@if ($paginator->hasPages())
    <div class="flex items-center justify-between w-full">
        <p class="text-default-500 text-sm">
            Showing <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b> of <b>{{ $paginator->total() }}</b> Results
        </p>
        <nav aria-label="Pagination" class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm border bg-transparent border-default-200 text-default-400 cursor-not-allowed" disabled>
                    <i class="size-4 me-1" data-lucide="chevron-left"></i> Prev
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                    <i class="size-4 me-1" data-lucide="chevron-left"></i> Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 text-default-500">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="btn size-7.5 bg-primary text-white" type="button">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn size-7.5 bg-transparent border border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-primary/10 hover:text-primary hover:border-primary/10">
                    Next <i class="size-4 ms-1" data-lucide="chevron-right"></i>
                </a>
            @else
                <button class="btn btn-sm border bg-transparent border-default-200 text-default-400 cursor-not-allowed" disabled>
                    Next <i class="size-4 ms-1" data-lucide="chevron-right"></i>
                </button>
            @endif
        </nav>
    </div>
@endif

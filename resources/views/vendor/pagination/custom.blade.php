<nav aria-label="Pagination" class="flex justify-center">
    <ul class="flex flex-wrap items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <li>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5eae6] bg-[#f8faf6] text-[#a7b0aa] shadow-sm cursor-not-allowed">«</span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#dfe7e1] bg-white text-[#1d5547] shadow-sm transition hover:border-[#1d5547] hover:bg-[#edf5dd]">
                    «
                </a>
            </li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li>
                    <span class="inline-flex h-10 items-center justify-center rounded-lg border border-[#dfe7e1] bg-[#f8faf6] px-3 text-sm font-medium text-[#718078]">
                        {{ $element }}
                    </span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-[#1d5547] text-sm font-bold text-white shadow-sm">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#dfe7e1] bg-white text-sm font-medium text-[#18342d] shadow-sm transition hover:border-[#1d5547] hover:text-[#1d5547]">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#dfe7e1] bg-white text-[#1d5547] shadow-sm transition hover:border-[#1d5547] hover:bg-[#edf5dd]">
                    »
                </a>
            </li>
        @else
            <li>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-[#e5eae6] bg-[#f8faf6] text-[#a7b0aa] shadow-sm cursor-not-allowed">
                    »
                </span>
            </li>
        @endif
    </ul>
</nav>

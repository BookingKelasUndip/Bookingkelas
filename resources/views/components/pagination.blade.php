@php($accent = $accent ?? '#1d3c8a')

@if ($paginator->hasPages())
    <nav aria-label="Navigasi halaman" class="flex flex-wrap items-center gap-2">
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-300 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="flex h-9 w-9 items-center justify-center text-xs font-semibold text-slate-400">…</span>
            @else
                @foreach ($element as $page => $url)
                    @if ($page === $paginator->currentPage())
                        <span
                            aria-current="page"
                            style="background-color: {{ $accent }}; border-color: {{ $accent }};"
                            class="flex h-9 w-9 items-center justify-center rounded-xl border text-xs font-bold text-white shadow-xs"
                        >{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span aria-disabled="true" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-300 cursor-not-allowed">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </nav>

    <p class="mt-3 text-[11px] text-slate-400">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} booking.
    </p>
@endif

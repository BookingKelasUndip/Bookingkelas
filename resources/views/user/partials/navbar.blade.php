<!-- Top Navbar -->
<header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-100">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
        <!-- Brand -->
        <a href="{{ route('beranda') }}" class="flex items-center gap-3 shrink-0">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f8fafd] p-1.5 border border-slate-100">
                <img
                    src="{{ asset('images/Logo_Universitas_Diponegoro.png') }}"
                    alt="Logo Universitas Diponegoro"
                    class="h-full w-full object-contain"
                >
            </div>
            <div class="leading-tight">
                <p class="text-[10px] font-semibold tracking-[0.14em] text-slate-400 uppercase">
                    Universitas Diponegoro
                </p>
                <p class="text-sm font-bold text-slate-900">
                    Kelas Tambahan Undip
                </p>
            </div>
        </a>

        <!-- Center Navigation -->
        <nav class="hidden md:flex items-center gap-1 rounded-xl bg-[#f4f7fb] p-1 border border-slate-100">
            <!-- Beranda -->
            <a href="{{ route('beranda') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs transition {{ request()->routeIs('beranda') ? 'bg-[#1d3c8a] text-white font-semibold shadow-xs' : 'font-medium text-slate-600 hover:bg-white hover:text-slate-900' }}">
                <svg class="h-3.5 w-3.5 {{ request()->routeIs('beranda') ? '' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('beranda') ? '2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
            </a>

            <!-- Booking Kelas -->
            <a href="{{ route('bookingkelas') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs transition {{ request()->routeIs('bookingkelas') ? 'bg-[#1d3c8a] text-white font-semibold shadow-xs' : 'font-medium text-slate-600 hover:bg-white hover:text-slate-900' }}">
                <svg class="h-3.5 w-3.5 {{ request()->routeIs('bookingkelas') ? '' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('bookingkelas') ? '2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Booking Kelas</span>
            </a>

            <!-- Riwayat Booking -->
            <a href="{{ route('riwayatbooking') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 text-xs transition {{ request()->routeIs('riwayatbooking') ? 'bg-[#1d3c8a] text-white font-semibold shadow-xs' : 'font-medium text-slate-600 hover:bg-white hover:text-slate-900' }}">
                <svg class="h-3.5 w-3.5 {{ request()->routeIs('riwayatbooking') ? '' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('riwayatbooking') ? '2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Riwayat Booking</span>
            </a>
        </nav>

        <!-- Right User & Logout -->
        <div class="flex items-center gap-3 shrink-0">
            <div class="hidden sm:block rounded-xl border border-slate-100 bg-[#f8fafd] px-3.5 py-1.5 text-right leading-snug">
                <p class="text-xs font-semibold text-slate-800">{{ Auth::user()?->name ?? 'Tamu' }}</p>
                <p class="text-[11px] font-mono text-slate-400">{{ Auth::user()?->email ?? 'Belum masuk' }}</p>
            </div>

            @if (Auth::check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 cursor-pointer"
                >
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs font-semibold text-[#1d3c8a] transition hover:bg-[#eef3fc]">
                    <span>Masuk</span>
                </a>
            @endif
        </div>
    </div>
</header>

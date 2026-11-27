<!-- Left Sidebar -->
<aside class="hidden lg:flex w-64 shrink-0 flex-col justify-between bg-white border-r border-slate-100 px-4 py-5">
    <div>
        <!-- Brand Header -->
        <a href="#" class="flex items-center gap-3 px-2 pb-6">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#f8fafd] p-1.5 border border-slate-100">
                <img
                    src="{{ asset('images/Logo_Universitas_Diponegoro.png') }}"
                    alt="Logo Universitas Diponegoro"
                    class="h-full w-full object-contain"
                >
            </div>
            <div class="leading-tight">
                <p class="text-sm font-bold text-slate-900">Kelas Tambahan</p>
                <p class="text-[10px] font-semibold tracking-[0.08em] text-slate-400 uppercase">
                    UNDIP • Portal Admin
                </p>
            </div>
        </a>

        <!-- Main Navigation -->
        <p class="px-3 mb-2 text-[10px] font-bold tracking-[0.14em] text-slate-400 uppercase">
            Navigasi Utama
        </p>

        <nav class="space-y-1">
            <!-- Kelola Booking -->
            <a href="{{ route('admin.kelolabooking') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs transition {{ request()->routeIs('admin.kelolabooking') ? 'bg-[#102863] text-white font-semibold shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.kelolabooking') ? '' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('admin.kelolabooking') ? '2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Kelola Booking</span>
            </a>

            <!-- Jadwal Harian -->
            <a href="{{ route('admin.jadwalharian') }}" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-xs transition {{ request()->routeIs('admin.jadwalharian') ? 'bg-[#102863] text-white font-semibold shadow-xs' : 'font-medium text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="h-4 w-4 shrink-0 {{ request()->routeIs('admin.jadwalharian') ? '' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ request()->routeIs('admin.jadwalharian') ? '2' : '1.8' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Jadwal Harian</span>
            </a>
        </nav>
    </div>

    <!-- Admin Portal UNDIP Card -->
    <div class="mt-8 rounded-2xl border border-[#e3eaf8] bg-[#f3f6fc] p-4">
        <div class="flex items-center gap-2 text-[11px] font-bold tracking-wider text-[#163683] uppercase">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Admin Portal UNDIP</span>
        </div>

        <p class="mt-2 text-[11px] leading-relaxed text-slate-500">
            Perpustakaan Undip, UndipPress, Rektorat Dan Kearsipan
        </p>

        <div class="mt-3 space-y-1.5 text-[11px] text-slate-600">
            <div class="flex items-center gap-2">
                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <span>+62 (24) 7460020</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="truncate">baak@live.undip.ac.id</span>
            </div>
        </div>
    </div>
</aside>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Permohonan Ikut Kelas - Portal Admin UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fc] text-slate-800 antialiased">

    <div class="flex min-h-screen">

        @include('admin.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Bar -->
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between gap-4 bg-white border-b border-slate-100 px-4 sm:px-8">
                <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-700">
                    <svg class="h-4 w-4 text-[#163683]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                    <span class="truncate">Portal Admin Kelas Tambahan Universitas Diponegoro</span>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <span class="text-xs font-semibold text-slate-700">{{ Auth::user()?->name ?? 'Admin Demo' }}</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-[#102863] text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    @include('admin.partials.logout-button')
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 px-4 py-8 sm:px-8">
                <div class="mx-auto max-w-6xl">

                    <!-- Page Header -->
                    <div class="mb-6">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="font-serif text-3xl font-bold tracking-tight text-slate-900">
                                Kelola Permohonan Ikut Kelas
                            </h1>
                            <span class="rounded-full bg-[#eef3fc] px-3 py-1 text-[11px] font-semibold text-[#1d3c8a] border border-[#dce6f8]">
                                Semester Genap 2023/2024
                            </span>
                        </div>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500">
                            Verifikasi dan kelola pendaftaran mahasiswa yang mengajukan untuk bergabung ke sesi kelas tambahan terbuka di lingkungan Universitas Diponegoro.
                        </p>
                    </div>

                    <!-- Ketentuan Verifikasi Pendaftaran Kelas Banner -->
                    <section class="mb-6 rounded-2xl border border-[#dce6f8] bg-[#f3f6fc] px-5 py-4 flex items-start gap-3.5">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white text-[#163683] border border-[#e3eaf8]">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-xs font-bold text-[#13295e]">
                                Ketentuan Verifikasi Pendaftaran Kelas
                            </h2>
                            <p class="mt-0.5 text-xs leading-relaxed text-[#2b447e]">
                                Mahasiswa yang disetujui akan otomatis mendapatkan e-tiket digital pada akun SSO dan kode presensi QR code untuk akses masuk ke ruangan laboratorium/diskusi. Pengajuan ditolak akan mengembalikan alokasi kuota secara real-time.
                            </p>
                        </div>
                    </section>

                    <!-- Filter Tabs & Search Controls Card -->
                    <section class="rounded-2xl bg-white p-4 sm:p-5 shadow-[0_6px_20px_-8px_rgba(15,23,42,0.05)] border border-slate-100">
                        <!-- Status Tabs -->
                        <div class="flex flex-wrap items-center gap-2 pb-4">
                            <button
                                type="button"
                                class="rounded-xl bg-[#102863] px-4 py-2 text-xs font-semibold text-white shadow-xs cursor-pointer"
                            >
                                Semua
                            </button>
                            <button
                                type="button"
                                class="rounded-xl px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer"
                            >
                                Menunggu Verifikasi
                            </button>
                            <button
                                type="button"
                                class="rounded-xl px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer"
                            >
                                Disetujui
                            </button>
                            <button
                                type="button"
                                class="rounded-xl px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 cursor-pointer"
                            >
                                Ditolak / Kuota Penuh
                            </button>
                        </div>

                        <!-- Search, Class Filter, Sort & Refresh Row -->
                        <div class="flex flex-col gap-3 md:flex-row md:items-center">
                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                    </svg>
                                </span>
                                <input
                                    type="search"
                                    name="search"
                                    placeholder="Cari nama mahasiswa, NIM, atau kelas tambahan..."
                                    class="w-full rounded-xl border border-slate-200/70 bg-[#f6f8fc] pl-10 pr-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#102863]/10"
                                >
                            </div>

                            <!-- Class Dropdown -->
                            <div class="relative w-full md:w-64 shrink-0">
                                <select
                                    name="class_filter"
                                    class="w-full appearance-none rounded-xl border border-slate-200/70 bg-[#f6f8fc] pl-4 pr-9 py-2.5 text-xs font-semibold text-slate-700 transition focus:border-[#102863] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#102863]/10 cursor-pointer"
                                >
                                    <option value="">Semua Kelas Tambahan</option>
                                    <option value="struktur-data">Struktur Data &amp; Algoritma</option>
                                    <option value="basis-data">Basis Data Lanjut</option>
                                    <option value="kalkulus">Kalkulus II</option>
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-slate-500">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </div>

                            <!-- Sort & Refresh Group -->
                            <div class="flex items-center gap-2 shrink-0">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200/70 bg-[#f6f8fc] px-3.5 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 cursor-pointer"
                                >
                                    <span>Terbaru Masuk</span>
                                    <svg class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                    </svg>
                                </button>

                                <button
                                    type="button"
                                    aria-label="Muat ulang data"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/70 bg-[#f6f8fc] text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 cursor-pointer"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Empty State Card -->
                    <section class="mt-7 flex items-center justify-center py-2">
                        <div class="rounded-2xl bg-white px-7 py-5 text-xs font-bold text-slate-900 shadow-[0_6px_20px_-8px_rgba(15,23,42,0.06)] border border-slate-100">
                            Belum Ada Permohonan
                        </div>
                    </section>

                    <!-- Pagination Bar -->
                    <section class="mt-6 rounded-2xl bg-white p-3.5 shadow-[0_6px_20px_-8px_rgba(15,23,42,0.05)] border border-slate-100">
                        <nav aria-label="Navigasi halaman" class="flex items-center gap-2">
                            <button
                                type="button"
                                aria-label="Halaman sebelumnya"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#f6f8fc] text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 cursor-pointer"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <button
                                type="button"
                                aria-current="page"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#102863] text-xs font-bold text-white shadow-xs cursor-pointer"
                            >
                                1
                            </button>

                            <button
                                type="button"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eef3fc] text-xs font-semibold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                            >
                                2
                            </button>

                            <button
                                type="button"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eef3fc] text-xs font-semibold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                            >
                                3
                            </button>

                            <button
                                type="button"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eef3fc] text-xs font-semibold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                            >
                                4
                            </button>

                            <button
                                type="button"
                                aria-label="Halaman berikutnya"
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#eef3fc] text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </nav>
                    </section>


                </div>
            </main>

        </div>

    </div>

</body>
</html>

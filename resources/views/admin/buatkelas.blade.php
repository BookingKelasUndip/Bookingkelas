<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kelas Tambahan - Portal Admin UNDIP</title>
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
                    <div class="mb-7">
                        <h1 class="font-serif text-3xl font-bold tracking-tight text-slate-900">
                            Buat Kelas Tambahan
                        </h1>
                        <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                            Buka sesi kelas baru bimbingan perkuliahan, responsi, dan asistensi praktikum untuk mahasiswa Universitas Diponegoro.
                        </p>
                    </div>

                    <form action="#" method="POST">
                        @csrf

                        <!-- Two-Column Form Grid -->
                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">

                            <!-- LEFT COLUMN (5 Cols): Section 1 & Section 2 -->
                            <div class="space-y-6 lg:col-span-5">

                                <x-calendar id="admin-buatkelas-calendar" />

                                <!-- SECTION 2: Pengaturan Jam & Durasi -->
                                <section class="rounded-2xl bg-white p-5 sm:p-6 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                                    <h2 class="mb-4 flex items-center gap-2 text-xs font-bold text-slate-900">
                                        <svg class="h-4 w-4 text-[#163683]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>2. Pengaturan Jam &amp; Durasi</span>
                                    </h2>

                                    <!-- Sesi Standar Kampus Button -->
                                    <button
                                        type="button"
                                        class="mb-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-800 shadow-2xs transition hover:bg-slate-50 cursor-pointer"
                                    >
                                        <svg class="h-3.5 w-3.5 text-[#163683]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Sesi Standar Kampus</span>
                                    </button>

                                    <!-- Jam Mulai & Durasi Sesi -->
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <label for="start_time" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                Jam Mulai (WIB) <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                                <input
                                                    type="text"
                                                    id="start_time"
                                                    name="start_time"
                                                    value="13:30 WIB"
                                                    class="w-full rounded-xl bg-[#eef3fc] pl-9 pr-3 py-2.5 text-xs font-bold text-slate-800 border border-transparent focus:border-[#163683] focus:bg-white focus:outline-none"
                                                >
                                            </div>
                                        </div>

                                        <div>
                                            <label for="duration" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                Durasi Sesi <span class="text-rose-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-500">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                                <input
                                                    type="text"
                                                    id="duration"
                                                    name="duration"
                                                    value="60 Menit"
                                                    class="w-full rounded-xl bg-[#eef3fc] pl-9 pr-3 py-2.5 text-xs font-bold text-slate-800 border border-transparent focus:border-[#163683] focus:bg-white focus:outline-none"
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Estimasi Waktu Selesai Box -->
                                    <div class="mt-4 rounded-xl bg-[#f3f6fc] p-3.5 border border-[#e3eaf8]">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-slate-500">Estimasi Waktu Selesai:</span>
                                            <strong class="font-bold text-[#102863]">14:30 WIB</strong>
                                        </div>

                                        <div class="mt-2.5 flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-[11px] font-semibold text-emerald-700 border border-slate-100">
                                            <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Jadwal Tersedia &amp; Tidak Bentrok dengan Kelas Reguler</span>
                                        </div>
                                    </div>

                                    <!-- Info Footnote -->
                                    <div class="mt-3.5 flex items-start gap-2 text-[11px] leading-relaxed text-slate-500">
                                        <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#163683]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p>Sesi kelas tambahan dijadwalkan di luar jam mata kuliah wajib fakultas.</p>
                                    </div>
                                </section>

                            </div>

                            <!-- RIGHT COLUMN (7 Cols): Section 3 Detail Kelas yang Ingin Dibuat -->
                            <div class="lg:col-span-7">
                                <section class="rounded-2xl bg-white p-5 sm:p-6 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                                    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
                                        <h2 class="flex items-center gap-2 text-xs font-bold text-slate-900">
                                            <svg class="h-4 w-4 text-[#163683]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span>3. Detail Kelas yang Ingin Dibuat</span>
                                        </h2>
                                        <span class="text-[11px] font-medium text-slate-400">
                                            Lengkapi data akademik
                                        </span>
                                    </div>

                                    <div class="space-y-4">
                                        <!-- Kelas -->
                                        <div>
                                            <label for="class_name" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                Kelas
                                            </label>
                                            <div class="relative">
                                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                </span>
                                                <input
                                                    type="text"
                                                    id="class_name"
                                                    name="class_name"
                                                    class="w-full rounded-xl bg-[#eef3fc] pl-10 pr-4 py-2.5 text-xs text-slate-800 border border-transparent transition focus:border-[#163683] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#163683]/10"
                                                >
                                            </div>
                                        </div>

                                        <!-- Dosen / Tutor Pendamping & Ruangan / Laboratorium -->
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label for="lecturer" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                    Dosen / Tutor Pendamping <span class="text-rose-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                                        </svg>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        id="lecturer"
                                                        name="lecturer"
                                                        class="w-full rounded-xl bg-[#eef3fc] pl-10 pr-4 py-2.5 text-xs text-slate-800 border border-transparent transition focus:border-[#163683] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#163683]/10"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <label for="room" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                    Ruangan / Laboratorium <span class="text-rose-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                    </span>
                                                    <input
                                                        type="text"
                                                        id="room"
                                                        name="room"
                                                        class="w-full rounded-xl bg-[#eef3fc] pl-10 pr-4 py-2.5 text-xs text-slate-800 border border-transparent transition focus:border-[#163683] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#163683]/10"
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Target Kuota Peserta -->
                                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl bg-[#eef3fc] px-4 py-3">
                                            <label for="quota" class="text-[11px] font-bold text-slate-800">
                                                Target Kuota Peserta <span class="text-rose-500">*</span>
                                            </label>

                                            <div class="flex items-center gap-2">
                                                <input
                                                    type="number"
                                                    id="quota"
                                                    name="quota"
                                                    value="25"
                                                    min="1"
                                                    class="w-16 rounded-lg bg-white px-2.5 py-1.5 text-center text-xs font-bold text-slate-900 shadow-2xs border border-slate-200/70 focus:border-[#163683] focus:outline-none"
                                                >
                                                <span class="text-[11px] font-medium text-slate-500">Mhs</span>
                                            </div>
                                        </div>

                                        <!-- Catatan / Topik Bimbingan -->
                                        <div>
                                            <label for="notes" class="mb-1.5 block text-[11px] font-bold text-slate-800">
                                                Catatan / Topik Bimbingan
                                                <span class="font-normal text-slate-400">(Rincian untuk mahasiswa)</span>
                                            </label>
                                            <textarea
                                                id="notes"
                                                name="notes"
                                                rows="4"
                                                class="w-full rounded-xl bg-[#eef3fc] p-3.5 text-xs leading-relaxed text-slate-800 border border-transparent transition focus:border-[#163683] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#163683]/10"
                                            ></textarea>
                                        </div>
                                    </div>
                                </section>
                            </div>

                        </div>

                        <!-- Bottom Release Summary Action Bar -->
                        <section class="mt-7 rounded-2xl bg-white p-4 sm:px-6 sm:py-4 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.06)] border border-slate-100">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                                <!-- Left Info -->
                                <div class="flex items-start sm:items-center gap-3.5">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#eef3fc] text-[#163683]">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </span>

                                    <div class="leading-snug">
                                        <p class="text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">
                                            Ringkasan Jadwal yang Akan Dirilis
                                        </p>
                                        <p class="mt-0.5 flex flex-wrap items-center gap-1.5 text-xs font-bold text-slate-900">
                                            <span>Kelas-</span>
                                            <span class="text-slate-300">•</span>
                                            <span id="release-summary-date" class="text-[#163683]">Kamis, 31 Okt (13:30 - 14:30 WIB)</span>
                                            <span class="text-slate-300">•</span>
                                            <span>Ruangan-</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Buttons -->
                                <div class="flex items-center justify-end gap-2.5 shrink-0">
                                    <a
                                        href="#"
                                        class="inline-flex items-center justify-center rounded-xl bg-[#eef3fc] px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-[#e1ebfc]"
                                    >
                                        Batal
                                    </a>

                                    <button
                                        type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#102863] px-5 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-[#0c1f4d] active:scale-[0.99] cursor-pointer"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Konfirmasi &amp; Buat Kelas</span>
                                    </button>
                                </div>

                            </div>
                        </section>

                    </form>

                </div>
            </main>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const summaryDate = document.getElementById('release-summary-date');
            if (!summaryDate) return;

            const timeRange = summaryDate.textContent.match(/\([^)]+\)/)?.[0] ?? '';

            document.addEventListener('calendar:date-selected', function (event) {
                if (event.detail.id !== 'admin-buatkelas-calendar') return;

                summaryDate.textContent = `${event.detail.shortLabel} ${timeRange}`.trim();
            });
        });
    </script>

</body>
</html>
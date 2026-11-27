<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Kelas Tambahan Undip</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fc] text-slate-800 antialiased flex flex-col">

    @include('user.partials.navbar')

    <!-- Main Content -->
    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">

        <!-- Hero Card -->
        <section class="relative overflow-hidden rounded-3xl bg-white p-6 sm:p-12 shadow-[0_12px_35px_-12px_rgba(15,23,42,0.06)] border border-slate-100">
            <!-- Subtle decorative radial glow -->
            <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-[#eef3fc]/70 blur-2xl"></div>

            <div class="relative max-w-3xl">
                <!-- Portal Pill -->
                <div class="mb-5 inline-flex flex-wrap items-center gap-2 rounded-full bg-[#f4f7fc] px-3.5 py-1.5 text-[11px] font-semibold tracking-wider text-[#1d3c8a] border border-[#e3eaf8]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#1d3c8a]"></span>
                    <span>PORTAL AKADEMIK MAHASISWA UNDIP</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-[#2c52b3]">Fakultas Teknik</span>
                </div>

                <!-- Headline -->
                <h1 class="font-serif text-3xl sm:text-5xl font-bold leading-[1.15] tracking-tight text-slate-900">
                    Selamat Datang,
                    <span class="italic font-bold text-[#1d3c8a]">{{ Auth::user()?->name ?? 'Mahasiswa' }}</span>
                    di Sistem Booking Kelas
                </h1>

                <!-- Description -->
                <p class="mt-4 max-w-2xl text-sm sm:text-base leading-relaxed text-slate-500">
                    Platform resmi Universitas Diponegoro untuk memfasilitasi mahasiswa dalam memesan sesi konsultasi mata kuliah, bimbingan materi, dan kelas penguatan bersama asisten dosen &amp; tutor akademik secara terstruktur.
                </p>

                <!-- Action Buttons & Semester Status -->
                <div class="mt-7 flex flex-wrap items-center gap-3.5">
                    <a
                        href="{{ route('bookingkelas') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-[#1d3c8a] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162f6e] active:scale-[0.99]"
                    >
                        <span>Mulai Booking Kelas</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>

                    <a
                        href="{{ route('riwayatbooking') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Lihat Riwayat Booking</span>
                    </a>

                    <div class="inline-flex items-center gap-2 px-2 py-1.5 text-xs font-medium text-slate-500">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Layanan Aktif Semester Genap 2026/2027</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bottom Grid: Alur Pemesanan (Left 8 cols) & Pengumuman & Jadwal (Right 4 cols) -->
        <section class="relative mt-8 grid grid-cols-1 gap-7 lg:grid-cols-12">
            <!-- Subtle decorative circle accent in middle -->
            <div class="pointer-events-none hidden lg:block absolute left-1/2 -top-4 -translate-x-1/2 h-20 w-20 rounded-full bg-[#e6ecf8]/80 -z-0"></div>

            <!-- Alur Pemesanan Kelas Tambahan -->
            <div class="relative z-10 lg:col-span-8">
                <div class="mb-4 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-[#1d3c8a]"></span>
                    <h2 class="text-sm font-bold text-slate-900">
                        Alur Pemesanan Kelas Tambahan
                    </h2>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <!-- Step 01 -->
                    <div class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                        <div>
                            <span class="inline-flex rounded-lg bg-[#eef3fc] px-2.5 py-1 text-xs font-bold text-[#1d3c8a]">
                                01
                            </span>

                            <h3 class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-900">
                                <svg class="h-4 w-4 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Pilih Tanggal &amp; Jam</span>
                            </h3>

                            <p class="mt-2.5 text-xs leading-relaxed text-slate-500">
                                Tentukan Tanggal Waktu, dan Durasi Sesuai Hari.
                            </p>
                        </div>

                        <div class="mt-5 border-t border-slate-100 pt-3 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Slot real-time</span>
                        </div>
                    </div>

                    <!-- Step 02 -->
                    <div class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                        <div>
                            <span class="inline-flex rounded-lg bg-[#eef3fc] px-2.5 py-1 text-xs font-bold text-[#1d3c8a]">
                                02
                            </span>

                            <h3 class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-900">
                                <svg class="h-4 w-4 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Tuliskan Kendala</span>
                            </h3>

                            <p class="mt-2.5 text-xs leading-relaxed text-slate-500">
                                Jabarkan topik bahasan, materi perkuliahan yang belum dipahami Atau Kendala Apapun Itu
                            </p>
                        </div>

                        <div class="mt-5 border-t border-slate-100 pt-3 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Target terarah</span>
                        </div>
                    </div>

                    <!-- Step 03 -->
                    <div class="flex flex-col justify-between rounded-2xl bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                        <div>
                            <span class="inline-flex rounded-lg bg-[#eef3fc] px-2.5 py-1 text-xs font-bold text-[#1d3c8a]">
                                03
                            </span>

                            <h3 class="mt-4 flex items-center gap-2 text-sm font-bold text-slate-900">
                                <svg class="h-4 w-4 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Konfirmasi Tutor</span>
                            </h3>

                            <p class="mt-2.5 text-xs leading-relaxed text-slate-500">
                            Admin memvalidasi pengajuan Dan Memberikan Tutor Yang Tepat.
                            </p>
                        </div>

                        <div class="mt-5 border-t border-slate-100 pt-3 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Verifikasi cepat</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pengumuman & Jadwal -->
            <div class="relative z-10 lg:col-span-4">
                <div class="mb-4 flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <h2 class="text-sm font-bold text-slate-900">
                        Pengumuman &amp; Jadwal
                    </h2>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <!-- Jam Operasional Box -->
                    <div class="rounded-xl bg-[#f5f8fd] p-4 border border-[#e7eef9]">
                        <div class="flex items-center gap-2 text-[11px] font-bold tracking-wider text-[#1d3c8a] uppercase">
                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Jam Operasional Ruang Bimbingan</span>
                        </div>

                        <div class="mt-2.5 space-y-1 text-xs text-slate-600">
                            <p>Senin – Jumat: 09.00 – 19.00 WIB</p>
                            <p>Sabtu: 09.00 – 14.00 WIB</p>
                        </div>
                    </div>

                    <!-- Fasilitas Terdaftar -->
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <p class="text-[10px] font-bold tracking-[0.14em] text-slate-400 uppercase">
                            Fasilitas Terdaftar
                        </p>
                        <div class="mt-2.5 flex items-start gap-2 text-xs font-medium text-slate-700">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Ruang Konsultasi Perpustakaan Undip Lantai 3</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="mt-10 border-t border-slate-100 bg-white">
        <div class="mx-auto flex max-w-6xl flex-col sm:flex-row items-center justify-between gap-3 px-4 py-4 sm:px-6 text-xs text-slate-400">
            <div class="flex items-center gap-2.5">
                <img
                    src="{{ asset('images/Logo_Universitas_Diponegoro.png') }}"
                    alt="Logo UNDIP"
                    class="h-6 w-6 object-contain"
                >
                <div class="leading-tight">
                    <p class="font-semibold text-slate-700">Layanan Akademik Kelas Tambahan Undip</p>
                    <p class="text-[11px] text-slate-400">Universitas Diponegoro</p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-[11px]">
                <span>•</span>
                <span>© 2026 Universitas Diponegoro.</span>
            </div>
        </div>
    </footer>

</body>
</html>
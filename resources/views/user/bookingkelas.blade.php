<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Sesi Kelas Tambahan - Kelas Tambahan Undip</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6f8fc] text-slate-800 antialiased flex flex-col">

    @include('user.partials.navbar')

    <!-- Main Content -->
    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6">

        <!-- Page Header -->
        <div class="mb-7">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-[#eef3fc] px-3.5 py-1 text-[11px] font-semibold tracking-wider text-[#1d3c8a] border border-[#dce6f8]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#1d3c8a]"></span>
                <span>PORTAL AKADEMIK MAHASISWA UNDIP</span>
            </div>

            <h1 class="font-serif text-3xl sm:text-[34px] font-bold tracking-tight text-slate-900">
                Pemesanan Sesi Kelas Tambahan
            </h1>
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                Pilih jadwal bimbingan dan uraikan kendala belajar Anda secara spesifik untuk persiapan materi bersama dosen pendamping atau asisten akademik.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                <p class="font-bold">Booking belum bisa dikirim:</p>
                <ul class="mt-1 list-disc space-y-0.5 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="booking-form" action="{{ route('bookingkelas.store') }}" method="POST" novalidate class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">
            @csrf

            <!-- LEFT COLUMN (7 Cols): Step 01 Calendar + Step 02 Jam -->
            <div class="space-y-6 lg:col-span-7">

                <!-- STEP 01: Pilih Tanggal Bimbingan -->
                <x-calendar id="booking-calendar" />

                <!-- Daftar slot yang sudah dibooking pada tanggal terpilih -->
                <section class="rounded-md bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex items-start gap-2.5">
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#eef3fc] text-[#1d3c8a]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xs font-bold text-slate-900">Bookingan pada Tanggal Terpilih</h2>
                                <p id="booked-slots-date" class="mt-0.5 text-[11px] text-slate-400">Pilih tanggal di kalender</p>
                            </div>
                        </div>
                        <span id="booked-slots-count" class="hidden rounded-full bg-[#eef3fc] px-2.5 py-1 text-[10px] font-semibold text-[#1d3c8a]"></span>
                    </div>

                    <div id="booked-slots-list" class="mt-4 space-y-2">
                        <p class="rounded-md border border-dashed border-slate-200 bg-slate-50/70 px-3 py-3 text-center text-[11px] text-slate-400">
                            Pilih tanggal di kalender untuk melihat jadwal yang sudah dibooking.
                        </p>
                    </div>
                </section>

                <!-- STEP 02: Pilih Sesi Jam Kelas Tambahan -->
                <section class="rounded-md bg-white p-6 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <!-- Step Header -->
                    <div class="mb-5 flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#1d3c8a] text-xs font-bold text-white">
                            02
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Pilih Sesi Jam Kelas Tambahan</h2>
                            <p class="mt-0.5 text-xs text-slate-400">
                                Atur jam mulai dan durasi bimbingan sesuai waktu luang Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Jam Mandiri Box -->
                    <div class="mt-4 rounded-md border border-slate-200/80 bg-[#fcfdff] p-4 sm:p-5">
                        <!-- Box Title & Badge -->
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#eef3fc] text-[#1d3c8a]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <h3 class="text-xs font-bold text-slate-900">Atur Jam Mandiri Sendiri (Fleksibel)</h3>
                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                        Tentukan jam mulai dan durasi bimbingan sesuai waktu luang Anda
                                    </p>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-semibold text-emerald-700 border border-emerald-100">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span>Jadwal Fleksibel Tersedia</span>
                            </span>
                        </div>

                        <!-- Time & Duration Inputs -->
                        <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                            <div>
                                <label for="start_time" class="mb-1.5 block text-[11px] font-semibold text-slate-700">
                                    Pilih Jam Mulai Bimbingan
                                </label>
                                <select
                                    id="start_time"
                                    name="start_time"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-800 focus:border-[#1d3c8a] focus:outline-none focus:ring-3 focus:ring-[#1d3c8a]/10"
                                >
                                    @foreach (range(540, 1110, 30) as $startMinutes)
                                        @php($startLabel = sprintf('%02d.%02d', intdiv($startMinutes, 60), $startMinutes % 60))
                                        <option value="{{ $startLabel }}" @selected($startLabel === '09.00')>{{ $startLabel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="duration" class="mb-1.5 block text-[11px] font-semibold text-slate-700">
                                    Pilihan Durasi Bimbingan
                                </label>
                                <select
                                    id="duration"
                                    name="duration"
                                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-medium text-slate-700 focus:border-[#1d3c8a] focus:outline-none focus:ring-3 focus:ring-[#1d3c8a]/10"
                                >
                                    <option value="30" selected>Sesi 1 (30 Menit)</option>
                                    <option value="60">Sesi 2 (60 Menit)</option>
                                    <option value="90">Sesi 3 (90 Menit)</option>
                                    <option value="120">Sesi 4 (120 Menit)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Calculated Estimate Box -->
                        <div class="mt-4 rounded-md border border-slate-200/70 bg-[#f6f8fc] p-3.5">
                            <div class="flex items-start gap-2.5">
                                <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white text-[#1d3c8a] border border-slate-200/70">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-medium text-slate-400">Estimasi Jam Bimbingan Terhitung:</p>
                                    <p id="booking-estimate" class="text-sm font-bold text-slate-900">09.00 – 09.30 WIB</p>
                                </div>
                            </div>
                            <p id="booking-time-error" class="mt-2 hidden text-[11px] font-semibold text-rose-600">
                                Bimbingan harus selesai maksimal 19.00 WIB. Sesuaikan jam mulai atau durasi.
                            </p>
                            <p id="booking-conflict-error" class="mt-2 hidden text-[11px] font-semibold text-rose-600">
                                Jadwal ini sudah dibooking, silakan pilih jam lain.
                            </p>

                            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-200/60 pt-3">
                                <div class="flex items-center gap-1.5 text-[11px] text-emerald-700">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Pengecekan Ruang &amp; Dosen Otomatis Realtime:</span>
                                    <strong class="font-bold">OK (Tersedia)</strong>
                                </div>

                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#1d3c8a] px-3 py-1.5 text-[11px] font-semibold text-white transition hover:bg-[#163175] cursor-pointer"
                                >
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Terapkan Jam</span>
                                </button>
                            </div>
                        </div>

                        <!-- Info Note -->
                        <div class="mt-3.5 flex items-start gap-2 rounded-md bg-[#f3f6fc] px-3.5 py-2.5 text-[11px] leading-relaxed text-[#2b447e] border border-[#e3eaf8]">
                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>
                                Jadwal mandiri fleksibel akan otomatis disinkronkan ke kalender SIAP UNDIP dosen pembimbing. Rentang operasional kampus: Senin-Jumat <strong class="font-semibold">09.00 – 19.00 WIB</strong> Sabtu <strong class="font-semibold">09.00 – 14.00 WIB</strong>
                    </div>

                    <!-- Legend Footer -->
                    <div class="mt-4 flex flex-wrap items-center gap-4 border-t border-slate-100 pt-3.5 text-[11px] text-slate-500">
                        <span>Keterangan Status:</span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded border border-slate-300 bg-white"></span>
                            <span>Tersedia</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded bg-[#1d3c8a]"></span>
                            <span>Dipilih (Aktif)</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <span class="h-3 w-3 rounded bg-slate-100 border border-slate-200"></span>
                            <span>Penuh / Tidak Tersedia</span>
                        </span>
                    </div>
                </section>

            </div>

            <!-- RIGHT COLUMN (5 Cols): Step 03 Uraian Kendala + Ringkasan + Submit -->
            <div class="space-y-6 lg:col-span-5">
                <section class="rounded-md bg-white p-6 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <!-- Step Header -->
                    <div class="mb-5 flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#1d3c8a] text-xs font-bold text-white">
                            03
                        </span>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Uraian Kendala Belajar</h2>
                            <p class="mt-0.5 text-xs text-slate-400">
                                Sampaikan materi yang memerlukan pendalaman mandiri
                            </p>
                        </div>
                    </div>

                    <!-- Textarea Kendala -->
                    <div>
                        <label for="problem_description" class="mb-2 flex items-center gap-1.5 text-xs font-bold text-slate-800">
                            <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Keluhan / Kendala Belajar yang Dibutuhkan</span>
                            <span class="text-rose-500">*</span>
                        </label>

                        <textarea
                            id="problem_description"
                            name="problem_description"
                            rows="7"
                            maxlength="600"
                            placeholder="Tuliskan topik mata kuliah, bagian materi yang belum dipahami, atau soal yang ingin dibahas..."
                            class="w-full rounded-xl border border-slate-200 bg-white p-3.5 text-xs leading-relaxed text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-3 focus:ring-[#1d3c8a]/10"
                        ></textarea>

                        <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                            <span class="inline-flex items-center gap-1">
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Tulis secara spesifik tanpa singkatan</span>
                            </span>
                            <span id="problem-counter" class="font-mono">0 / 600 Karakter</span>
                        </div>
                    </div>

                    <!-- Ringkasan Jadwal Terpilih -->
                    <div class="mt-5 rounded-md border border-[#e3eaf8] bg-[#f4f7fc] p-4">
                        <p class="text-[10px] font-bold tracking-[0.14em] text-[#1d3c8a] uppercase">
                            Ringkasan Jadwal Terpilih
                        </p>

                        <dl class="mt-3 space-y-2.5 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <dt class="flex items-center gap-2 text-slate-500">
                                    <svg class="h-3.5 w-3.5 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Tanggal:</span>
                                </dt>
                                <dd id="booking-summary-date" class="font-bold text-slate-900">Jumat, 24 Oktober 2024</dd>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <dt class="flex items-center gap-2 text-slate-500">
                                    <svg class="h-3.5 w-3.5 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Sesi:</span>
                                </dt>
                                <dd id="booking-summary-session" class="font-bold text-slate-900">09.00 – 09.30 WIB</dd>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <dt class="flex items-center gap-2 text-slate-500">
                                    <svg class="h-3.5 w-3.5 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Lokasi:</span>
                                </dt>
                                <dd class="font-bold text-slate-900 text-right">Ruang Konsultasi Perpus Undip</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-5">
                        <button
                            id="booking-submit"
                            type="submit"
                            disabled
                            class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1d3c8a] px-5 py-3.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#163175] active:scale-[0.99] cursor-pointer disabled:cursor-not-allowed disabled:opacity-40 disabled:hover:bg-[#1d3c8a] disabled:active:scale-100"
                        >
                            <svg class="h-4 w-4 -rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Kirim Booking Kelas Sekarang</span>
                        </button>
                    </div>
                </section>
            </div>

        </form>

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

    <!-- Modal Konfirmasi Booking -->
    <div id="booking-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-md bg-white p-6 shadow-xl border border-slate-100">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#eef3fc] text-[#1d3c8a]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                    </svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Apakah Anda sudah yakin?</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500">
                        Pastikan tanggal, jam mulai, durasi, dan keluhan belajar sudah benar sebelum mengirim booking.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2.5">
                <button
                    id="booking-confirm-back"
                    type="button"
                    class="rounded-xl bg-[#eef3fc] px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                >
                    Kembali
                </button>
                <button
                    id="booking-confirm-yes"
                    type="button"
                    class="rounded-xl bg-[#1d3c8a] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#163175] cursor-pointer"
                >
                    Ya, Kirim Booking
                </button>
            </div>
        </div>
    </div>

    <script>
        window.bookingOccupiedSlots = @json($occupiedSlots ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP);
        window.bookingToday = @json(now()->toDateString());
        window.bookingNowMinutes = {{ ((int) now()->format('H')) * 60 + (int) now()->format('i') }};

        document.addEventListener('DOMContentLoaded', function () {
            const summaryDate = document.getElementById('booking-summary-date');
            const estimate = document.getElementById('booking-estimate');
            const summarySession = document.getElementById('booking-summary-session');
            const startTime = document.getElementById('start_time');
            const duration = document.getElementById('duration');
            const dateInput = document.getElementById('booking-calendar-input');
            const problemDescription = document.getElementById('problem_description');
            const problemCounter = document.getElementById('problem-counter');
            const form = document.getElementById('booking-form');
            const submitButton = document.getElementById('booking-submit');
            const modal = document.getElementById('booking-confirm-modal');
            const modalBack = document.getElementById('booking-confirm-back');
            const modalYes = document.getElementById('booking-confirm-yes');
            const minStart = 9 * 60;
            const maxEnd = 19 * 60;
            const saturdayEnd = 14 * 60;
            const timeError = document.getElementById('booking-time-error');
            const conflictError = document.getElementById('booking-conflict-error');
            const occupiedSlots = Array.isArray(window.bookingOccupiedSlots) ? window.bookingOccupiedSlots : [];
            const bookedSlotsDate = document.getElementById('booked-slots-date');
            const bookedSlotsCount = document.getElementById('booked-slots-count');
            const bookedSlotsList = document.getElementById('booked-slots-list');

            function getMaxEnd() {
                if (!dateInput || !dateInput.value) return maxEnd;
                const parts = dateInput.value.split('-').map(Number);
                if (parts.length !== 3) return maxEnd;
                const selectedDate = new Date(parts[0], parts[1] - 1, parts[2]);
                return selectedDate.getDay() === 6 ? saturdayEnd : maxEnd;
            }

            function isPastTodaySlot() {
                if (!dateInput || !startTime || dateInput.value !== window.bookingToday) return false;
                const start = parseTime(startTime.value);
                if (!start) return false;

                return (start.hours * 60 + start.minutes) < window.bookingNowMinutes;
            }

            function refreshStartTimeOptions() {
                if (!startTime || !dateInput) return;
                const isToday = dateInput.value === window.bookingToday;

                Array.from(startTime.options).forEach(function (option) {
                    const parsed = parseTime(option.value);
                    const isPast = Boolean(isToday && parsed && (parsed.hours * 60 + parsed.minutes) < window.bookingNowMinutes);
                    option.disabled = isPast;
                    option.classList.toggle('text-slate-300', isPast);
                });

                const selected = startTime.selectedOptions[0];
                if (selected && selected.disabled) {
                    const firstAvailable = Array.from(startTime.options).find(function (option) {
                        return !option.disabled;
                    });
                    if (firstAvailable) startTime.value = firstAvailable.value;
                }
            }

            function timeLimitMessage() {
                if (isPastTodaySlot()) return 'Jam mulai tidak boleh lebih awal dari waktu sekarang.';

                return getMaxEnd() === saturdayEnd
                    ? 'Booking hari Sabtu harus selesai maksimal 14.00 WIB. Sesuaikan jam mulai atau durasi.'
                    : 'Bimbingan harus selesai maksimal 19.00 WIB. Sesuaikan jam mulai atau durasi.';
            }

            function toggleTimeError(invalid) {
                if (!timeError) return;
                if (invalid) timeError.textContent = timeLimitMessage();
                timeError.classList.toggle('hidden', !invalid);
            }

            function formatSlotMinutes(minutes) {
                const total = Number(minutes) || 0;
                return `${String(Math.floor(total / 60) % 24).padStart(2, '0')}.${String(total % 60).padStart(2, '0')}`;
            }

            function renderBookedSlots(date, label) {
                if (!bookedSlotsList) return;
                if (bookedSlotsDate) bookedSlotsDate.textContent = date && label ? label : 'Pilih tanggal di kalender';
                bookedSlotsList.innerHTML = '';

                if (!date) {
                    const empty = document.createElement('p');
                    empty.className = 'rounded-md border border-dashed border-slate-200 bg-slate-50/70 px-3 py-3 text-center text-[11px] text-slate-400';
                    empty.textContent = 'Pilih tanggal di kalender untuk melihat jadwal yang sudah dibooking.';
                    bookedSlotsList.appendChild(empty);
                    if (bookedSlotsCount) bookedSlotsCount.classList.add('hidden');
                    return;
                }

                const slots = occupiedSlots
                    .filter(function (slot) { return slot.date === date; })
                    .sort(function (a, b) { return a.start - b.start; });

                if (!slots.length) {
                    const empty = document.createElement('p');
                    empty.className = 'rounded-md border border-dashed border-slate-200 bg-slate-50/70 px-3 py-3 text-center text-[11px] text-slate-400';
                    empty.textContent = 'Belum ada bookingan di tanggal ini.';
                    bookedSlotsList.appendChild(empty);
                    if (bookedSlotsCount) bookedSlotsCount.classList.add('hidden');
                    return;
                }

                slots.forEach(function (slot) {
                    const row = document.createElement('div');
                    row.className = 'flex flex-wrap items-center justify-between gap-2 rounded-md border border-slate-200/80 bg-[#f8fafd] px-3 py-2.5';
                    row.innerHTML = `
                        <div class="flex items-center gap-2.5">
                            <span class="h-2 w-2 rounded-full bg-[#1d3c8a]"></span>
                            <span class="text-xs font-semibold text-slate-800">${formatSlotMinutes(slot.start)} – ${formatSlotMinutes(slot.end)} WIB</span>
                        </div>
                        <span class="text-[11px] font-semibold text-[#1d3c8a]">sudah dibooking</span>
                    `;
                    bookedSlotsList.appendChild(row);
                });

                if (bookedSlotsCount) {
                    bookedSlotsCount.textContent = `${slots.length} slot terbooking`;
                    bookedSlotsCount.classList.remove('hidden');
                }
            }

            function hasConflict() {
                if (!dateInput || !dateInput.value || !startTime || !duration) return false;
                const start = parseTime(startTime.value);
                if (!start) return false;
                const startMinutes = start.hours * 60 + start.minutes;
                const endMinutes = startMinutes + (parseInt(duration.value, 10) || 0);

                return occupiedSlots.some(function (slot) {
                    return slot.date === dateInput.value
                        && startMinutes < slot.end
                        && endMinutes > slot.start;
                });
            }

            function isStartTimeValid() {
                if (!startTime || !duration) return false;
                const start = parseTime(startTime.value);
                if (!start) return false;
                const minutes = start.hours * 60 + start.minutes;
                const length = parseInt(duration.value, 10) || 0;
                return minutes >= minStart && minutes + length <= getMaxEnd() && !isPastTodaySlot();
            }

            function validateForm() {
                if (!submitButton) return false;
                toggleTimeError(Boolean(startTime && duration && !isStartTimeValid()));
                const complete = Boolean(dateInput && dateInput.value)
                    && isStartTimeValid()
                    && Boolean(duration && duration.value)
                    && Boolean(problemDescription && problemDescription.value.trim());
                const conflict = complete && hasConflict();

                if (conflictError) conflictError.classList.toggle('hidden', !conflict);
                submitButton.disabled = !complete || conflict;
                return complete && !conflict;
            }

            document.addEventListener('calendar:date-selected', function (event) {
                if (event.detail.id !== 'booking-calendar') return;
                if (summaryDate) summaryDate.textContent = event.detail.label;
                renderBookedSlots(event.detail.date, event.detail.label);
                refreshStartTimeOptions();
                updateEstimate();
                validateForm();
            });

            function parseTime(value) {
                const text = value.trim().toUpperCase();
                const meridiem = /(AM|PM)$/.test(text) ? text.slice(-2) : null;
                const clock = meridiem ? text.slice(0, -2).trim() : text;
                const match = clock.match(/^(\d{1,2})[.:](\d{2})$/);
                if (!match) return null;

                let hours = parseInt(match[1], 10);
                const minutes = parseInt(match[2], 10);
                if (hours > 23 || minutes > 59) return null;

                if (meridiem === 'AM' && hours === 12) hours = 0;
                if (meridiem === 'PM' && hours < 12) hours += 12;

                return { hours, minutes };
            }

            function formatTime(time) {
                return `${String(time.hours).padStart(2, '0')}.${String(time.minutes).padStart(2, '0')}`;
            }

            function updateEstimate() {
                const start = parseTime(startTime.value);
                if (!start) return false;

                const startMinutes = start.hours * 60 + start.minutes;
                const minutes = parseInt(duration.value, 10) || 0;
                if (startMinutes < minStart || startMinutes + minutes > getMaxEnd() || isPastTodaySlot()) {
                    toggleTimeError(true);
                    return false;
                }

                const total = startMinutes + minutes;
                const end = {
                    hours: Math.floor(total / 60) % 24,
                    minutes: total % 60
                };
                const range = `${formatTime(start)} – ${formatTime(end)} WIB`;

                estimate.textContent = range;
                if (summarySession) summarySession.textContent = range;
                toggleTimeError(false);
                return true;
            }

            renderBookedSlots('', '');

            if (startTime && duration && estimate && summarySession) {
                startTime.addEventListener('change', function () {
                    updateEstimate();
                    validateForm();
                });
                duration.addEventListener('change', function () {
                    updateEstimate();
                    validateForm();
                });

                // Hindari submit form tidak sengaja saat menekan Enter pada dropdown.
                [startTime, duration].forEach(function (field) {
                    field.addEventListener('keydown', function (event) {
                        if (event.key === 'Enter') event.preventDefault();
                    });
                });

                refreshStartTimeOptions();
                updateEstimate();
            }

            [dateInput, startTime, duration, problemDescription].forEach(function (field) {
                if (!field) return;
                field.addEventListener('input', validateForm);
                field.addEventListener('change', validateForm);
            });

            if (problemDescription && problemCounter) {
                problemDescription.addEventListener('input', function () {
                    problemCounter.textContent = `${problemDescription.value.length} / 600 Karakter`;
                });
            }

            function openModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            if (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    if (!validateForm()) return;
                    openModal();
                });
            }

            if (modalBack) modalBack.addEventListener('click', closeModal);
            if (modalYes) modalYes.addEventListener('click', function () {
                if (form) form.submit();
            });
            if (modal) modal.addEventListener('click', function (event) {
                if (event.target === modal) closeModal();
            });

            validateForm();
        });
    </script>

</body>
</html>

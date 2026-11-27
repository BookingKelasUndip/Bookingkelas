<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Harian &amp; Kalender Bimbingan - Portal Admin UNDIP</title>
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
                            Jadwal Harian &amp; Kalender Bimbingan
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Pantau agenda booking mandiri mahasiswa dan kelas tambahan terbuka per tanggal dengan mudah.
                        </p>
                    </div>

                    <!-- Ringkasan Hari Terpilih (compact, above calendar) -->
                    <section class="mb-6 rounded-md bg-white px-4 py-3 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                        <div class="flex flex-wrap items-center justify-between gap-x-4 gap-y-2">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-[#eef3fc] text-[#163683]">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div class="leading-tight">
                                    <p class="text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">
                                        Ringkasan Hari Terpilih
                                    </p>
                                    <h3 id="jadwalharian-summary-date" class="font-serif text-base font-bold text-slate-900 sm:text-lg">
                                        {{ $selectedDateLabel }}
                                    </h3>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span id="jadwalharian-booking-count" class="rounded-lg bg-[#f3f6fc] px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                    {{ $slots->where('date', $selectedDate)->count() }} Booking Mandiri
                                </span>
                            </div>
                        </div>
                    </section>

                    <!-- Main Grid: Calendar (Left 5 Cols) & Daily Agendas (Right 7 Cols) -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">

                        <!-- LEFT COLUMN (5 Cols): Kalender -->
                        <div class="lg:col-span-5">
                            <x-calendar id="admin-jadwal-calendar" :allow-past="true" />
                        </div>

                        <!-- RIGHT COLUMN (7 Cols): Sesi Booking Mandiri & Daftar Kelas Terbuka -->
                        <div class="space-y-6 lg:col-span-7">

                            <!-- Sesi Booking Mandiri Mahasiswa -->
                            <section>
                                <div class="mb-3 flex items-center gap-2">
                                    <span class="h-4 w-1 rounded-full bg-[#163683]"></span>
                                    <h2 class="text-sm font-bold text-slate-900">
                                        Sesi Booking Mandiri Mahasiswa
                                    </h2>
                                </div>

                                <div id="jadwalharian-booking-list" class="space-y-3">
                                    @foreach ($bookings as $booking)
                                        @php
                                            $scheduleDate = $booking->effectiveDate();
                                            $isRescheduled = $booking->status === 'ajukan_ulang';
                                            $displayStart = $isRescheduled ? $booking->reschedule_start_time : $booking->start_time;
                                            $displayEnd = $isRescheduled ? $booking->reschedule_end_time : $booking->end_time;
                                            $displaySession = $isRescheduled ? $booking->reschedule_session_label : $booking->session_label;
                                            $statusClass = [
                                                'menunggu' => 'text-amber-700 border-amber-300',
                                                'dikonfirmasi' => 'text-emerald-700 border-emerald-300',
                                                'ajukan_ulang' => 'text-blue-700 border-blue-300',
                                                'selesai' => 'text-slate-600 border-slate-300',
                                                'ditolak' => 'text-rose-700 border-rose-300',
                                            ][$booking->status] ?? 'text-slate-600 border-slate-300';
                                        @endphp

                                        <article
                                            data-booking-date="{{ $scheduleDate }}"
                                            class="{{ $scheduleDate === $selectedDate ? '' : 'hidden ' }}rounded-md bg-white px-4 py-3 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100"
                                        >
                                            <div class="flex flex-wrap items-start justify-between gap-2">
                                                <div class="leading-tight">
                                                    <p class="text-sm font-bold text-slate-900">
                                                        {{ $booking->user?->name ?? 'Mahasiswa' }}
                                                    </p>
                                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                                        Bookingan ke-{{ $booking->booking_number }}
                                                    </p>
                                                </div>
                                                <span class="rounded-md border px-2.5 py-1 text-[10px] font-bold {{ $statusClass }}">
                                                    {{ $booking->status_label }}
                                                </span>
                                            </div>

                                            <div class="mt-3 grid grid-cols-1 gap-2 border-t border-slate-100 pt-3 text-[11px] sm:grid-cols-3">
                                                <div>
                                                    <p class="text-slate-400">Tanggal</p>
                                                    <p class="mt-0.5 font-semibold text-slate-800">
                                                        {{ \Carbon\Carbon::parse($scheduleDate)->locale('id')->translatedFormat('l, j F Y') }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-slate-400">Jam</p>
                                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $displayStart }} – {{ $displayEnd }} WIB</p>
                                                </div>
                                                <div>
                                                    <p class="text-slate-400">Sesi</p>
                                                    <p class="mt-0.5 font-semibold text-slate-800">{{ $displaySession }}</p>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>

                                <div id="jadwalharian-empty" class="{{ $slots->where('date', $selectedDate)->isNotEmpty() ? 'hidden ' : '' }}rounded-md bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                                    <p class="text-xs font-bold text-slate-900">Belum ada Bookingan</p>
                                    <p class="mt-1.5 flex items-center gap-1.5 text-[11px] text-slate-400">
                                        <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span>Ruang Konsultasi Perpus Undip LT 3</span>
                                    </p>
                                </div>
                            </section>

                        </div>

                    </div>

                </div>
            </main>

        </div>

    </div>

    <script>
        window.jadwalHarianSlots = @json($slots, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP);

        document.addEventListener('DOMContentLoaded', function () {
            const cards = Array.from(document.querySelectorAll('[data-booking-date]'));
            const emptyState = document.getElementById('jadwalharian-empty');
            const summaryDate = document.getElementById('jadwalharian-summary-date');
            const countBadge = document.getElementById('jadwalharian-booking-count');

            function filterByDate(date, label) {
                let count = 0;

                cards.forEach(function (card) {
                    const visible = card.dataset.bookingDate === date;
                    card.classList.toggle('hidden', !visible);
                    if (visible) count += 1;
                });

                if (summaryDate && label) summaryDate.textContent = label;
                if (countBadge) countBadge.textContent = count + ' Booking Mandiri';
                if (emptyState) emptyState.classList.toggle('hidden', count > 0);
            }

            document.addEventListener('calendar:date-selected', function (event) {
                if (event.detail.id !== 'admin-jadwal-calendar') return;
                filterByDate(event.detail.date, event.detail.label);
            });
        });
    </script>

</body>
</html>

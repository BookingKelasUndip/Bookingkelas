<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Permohonan Booking Kelas - Portal Admin UNDIP</title>
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

                    <!-- Page Title -->
                    <div class="mb-6">
                        <h1 class="font-serif text-3xl font-bold tracking-tight text-slate-900">
                            Kelola Permohonan Booking Kelas
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Verifikasi dan kelola permohonan bimbingan kelas tambahan mahasiswa secara cepat dan terstruktur.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-700 border border-emerald-100">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-xs font-bold text-rose-700 border border-rose-100">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Filter Tabs & Search Card -->
                    <section class="rounded-none bg-white p-4 shadow-[0_6px_20px_-8px_rgba(15,23,42,0.05)] border border-slate-100">
                        <div class="flex flex-col gap-3.5 xl:flex-row xl:items-center xl:justify-between">

                            <!-- Filter Status -->
                            @php
                                $filters = [
                                    'all' => 'Semua',
                                    'menunggu' => 'Menunggu Konfirmasi',
                                    'dikonfirmasi' => 'Terkonfirmasi',
                                    'ajukan_ulang' => 'Ajukan Ulang',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                ];
                                $filterStyles = [
                                    'all' => 'text-[#1d3c8a] border-[#1d3c8a] hover:bg-[#f4f7fc]',
                                    'menunggu' => 'text-amber-700 border-amber-300 hover:bg-amber-50',
                                    'dikonfirmasi' => 'text-emerald-700 border-emerald-300 hover:bg-emerald-50',
                                    'ajukan_ulang' => 'text-blue-700 border-blue-300 hover:bg-blue-50',
                                    'selesai' => 'text-slate-600 border-slate-300 hover:bg-slate-50',
                                    'ditolak' => 'text-rose-700 border-rose-300 hover:bg-rose-50',
                                ];
                            @endphp
                            <div class="flex flex-wrap items-center gap-2">
                                @foreach ($filters as $value => $label)
                                    @php
                                        $isActive = $status === $value;
                                        $filterStyle = $filterStyles[$value] ?? 'text-slate-600 border-slate-300 hover:bg-slate-50';
                                    @endphp
                                    <a
                                        href="{{ route('admin.kelolabooking', ['status' => $value]) }}"
                                        @if ($isActive) aria-current="page" @endif
                                        class="inline-flex items-center rounded-md border bg-white px-3 py-1.5 text-xs transition cursor-pointer {{ $filterStyle }} {{ $isActive ? 'border-2 font-bold shadow-xs' : 'font-semibold' }}"
                                    >
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Search Input -->
                            <div class="relative w-full xl:w-72 shrink-0">
                                <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                                    </svg>
                                </span>
                                <input
                                    type="search"
                                    name="search"
                                    placeholder="Cari mahasiswa, NIM, atau mata kuliah..."
                                    class="w-full rounded-xl border border-slate-200/80 bg-[#f8fafd] pl-10 pr-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#102863]/10"
                                >
                            </div>

                        </div>
                    </section>

                    <!-- Daftar Booking Masuk -->
                    <section class="mt-8 space-y-4 border-b border-slate-200/70 pb-8">
                        @forelse ($bookings as $booking)
                            @php
                                $isRescheduled = $booking->status === 'ajukan_ulang' && $booking->reschedule_date;
                                $displayDate = $isRescheduled ? $booking->reschedule_date : $booking->date;
                                $displayStartTime = $isRescheduled ? $booking->reschedule_start_time : $booking->start_time;
                                $displayEndTime = $isRescheduled ? $booking->reschedule_end_time : $booking->end_time;
                                $displayDuration = $isRescheduled ? $booking->reschedule_session_label : $booking->session_label;
                                $displayDurationValue = $isRescheduled ? $booking->reschedule_duration : $booking->duration;
                                $statusAccent = match ($booking->status) {
                                    'dikonfirmasi' => 'bg-emerald-400',
                                    'ajukan_ulang' => 'bg-blue-400',
                                    'ditolak' => 'bg-rose-400',
                                    default => 'bg-amber-400',
                                };
                            @endphp
                            <article
                                class="group relative overflow-hidden rounded-none border border-slate-200/80 bg-white shadow-[0_10px_30px_-18px_rgba(15,23,42,0.28)] transition duration-200 hover:-translate-y-0.5 hover:border-[#c7d4ea] hover:shadow-[0_16px_34px_-18px_rgba(15,23,42,0.32)]"
                                data-booking-id="{{ $booking->id }}"
                                data-booking-number="{{ $booking->booking_number }}"
                                data-booking-date="{{ $displayDate->locale('id')->translatedFormat('l, d F Y') }}"
                                data-booking-date-raw="{{ $displayDate->format('Y-m-d') }}"
                                data-booking-time="{{ $displayStartTime }} – {{ $displayEndTime }} WIB"
                                data-booking-start="{{ $displayStartTime }}"
                                data-booking-tutor="{{ $booking->tutor_pendamping }}"
                                data-booking-duration="{{ $displayDuration }}"
                                data-booking-duration-value="{{ $displayDurationValue }}"
                                data-booking-problem="{{ $booking->problem_description }}"
                            >
                                <span class="absolute inset-y-0 left-0 w-1 {{ $statusAccent }}"></span>

                                <div class="py-5 pr-5 pl-6 sm:pr-6">
                                    <div class="flex flex-wrap items-start justify-between gap-4">
                                        <div class="flex min-w-0 items-start gap-3.5">
                                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#eef3fc] text-xs font-bold text-[#102863] ring-1 ring-[#dce6f8]">
                                                {{ \Illuminate\Support\Str::initials($booking->user?->name ?? 'Mahasiswa', true) }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="text-[10px] font-bold tracking-[0.14em] text-[#102863] uppercase">
                                                    Permohonan Bimbingan
                                                </p>
                                                <h2 class="mt-1 truncate text-sm font-bold text-slate-900">
                                                    {{ $booking->user?->name ?? 'Mahasiswa' }}
                                                </h2>
                                                <p class="mt-0.5 text-[11px] text-slate-400">
                                                    Bookingan ke-{{ $booking->booking_number }}
                                                    <span class="mx-1.5 text-slate-300">•</span>
                                                    {{ $displayDate->locale('id')->translatedFormat('d M Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        @php
                                            $adminBadge = match ($booking->status) {
                                                'dikonfirmasi' => 'text-emerald-700 border-emerald-300',
                                                'ajukan_ulang' => 'text-blue-700 border-blue-300',
                                                'ditolak' => 'text-rose-700 border-rose-300',
                                                'selesai' => 'text-slate-600 border-slate-300',
                                                default => 'text-amber-700 border-amber-300',
                                            };
                                        @endphp
                                        <div class="flex flex-col items-end gap-1.5">
                                            <span class="rounded-none border px-2.5 py-1 text-[11px] font-bold {{ $adminBadge }}">
                                                {{ $booking->status === 'menunggu' ? 'Menunggu Konfirmasi' : $booking->status_label }}
                                            </span>
                                            @if ($booking->status === 'menunggu')
                                                <span class="text-[10px] font-medium text-slate-400">Perlu ditindaklanjuti</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                        <div class="rounded-xl border border-slate-100 bg-[#f8fafd] px-3.5 py-3">
                                            <p class="flex items-center gap-1.5 text-[10px] font-bold tracking-[0.1em] text-slate-400 uppercase">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $isRescheduled ? 'Tanggal Baru' : 'Tanggal Bimbingan' }}
                                            </p>
                                            <p class="mt-1.5 text-xs font-bold text-slate-900">
                                                {{ $displayDate->locale('id')->translatedFormat('l, d F Y') }}
                                            </p>
                                        </div>
                                        <div class="rounded-xl border border-slate-100 bg-[#f8fafd] px-3.5 py-3">
                                            <p class="flex items-center gap-1.5 text-[10px] font-bold tracking-[0.1em] text-slate-400 uppercase">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $isRescheduled ? 'Jam Baru' : 'Jam Bimbingan' }}
                                            </p>
                                            <p class="mt-1.5 text-xs font-bold text-slate-900">{{ $displayStartTime }} – {{ $displayEndTime }} WIB</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-100 bg-[#f8fafd] px-3.5 py-3">
                                            <p class="flex items-center gap-1.5 text-[10px] font-bold tracking-[0.1em] text-slate-400 uppercase">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                                Durasi
                                            </p>
                                            <p class="mt-1.5 text-xs font-bold text-slate-900">{{ $displayDuration }}</p>
                                        </div>
                                    </div>

                                    @if ($isRescheduled)
                                        <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50/70 px-4 py-3.5">
                                            <p class="flex items-center gap-1.5 text-[10px] font-bold tracking-[0.12em] text-[#1d3c8a] uppercase">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Usulan Jadwal Ulang Terkirim
                                            </p>
                                            <p class="mt-1.5 text-xs leading-relaxed text-slate-600">
                                                Jadwal di atas sudah mengikuti jadwal ulang yang Anda ajukan dan menunggu persetujuan mahasiswa.
                                            </p>
                                            <p class="mt-1.5 text-[11px] text-slate-500">
                                                <span class="font-bold">Jadwal sebelumnya:</span>
                                                {{ $booking->date->locale('id')->translatedFormat('l, d F Y') }} ·
                                                {{ $booking->start_time }}–{{ $booking->end_time }} WIB ·
                                                {{ $booking->session_label }}
                                            </p>
                                            <p class="mt-1 text-[11px] text-slate-500">
                                                <span class="font-bold">Tutor:</span> {{ $booking->tutor_pendamping }}
                                                <span class="mx-1.5 text-slate-300">|</span>
                                                <span class="font-bold">Alasan:</span> {{ $booking->reschedule_alasan }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="mt-4 rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3.5">
                                        <p class="flex items-center gap-1.5 text-[10px] font-bold tracking-[0.1em] text-slate-400 uppercase">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Keluhan / Kendala yang Diajukan
                                        </p>
                                        <p class="mt-2 text-xs leading-relaxed text-slate-700">{{ $booking->problem_description }}</p>
                                    </div>

                                    @if ($booking->status === 'menunggu')
                                        <div class="mt-5 flex flex-wrap items-center justify-end gap-2.5 border-t border-slate-100 pt-4">
                                            <button
                                                type="button"
                                                class="js-jadwal-ulang inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:border-[#c7d4ea] hover:bg-[#f8fafd] active:scale-[0.99] cursor-pointer"
                                            >
                                                <svg class="h-4 w-4 text-[#102863]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>Jadwal Ulang</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="js-konfirmasi inline-flex items-center gap-1.5 rounded-xl bg-[#102863] px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#0c1f4d] active:scale-[0.99] cursor-pointer"
                                            >
                                                <span>Konfirmasi</span>
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <div class="rounded-none bg-white p-8 text-center shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                                <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#f3f6fc] text-[#102863]">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <p class="mt-4 text-sm font-bold text-slate-900">Belum ada booking</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Permohonan bimbingan mahasiswa akan muncul di sini setelah mereka mengirim booking.
                                </p>
                            </div>
                        @endforelse
                    </section>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $bookings->links('components.pagination') }}
                    </div>


                </div>
            </main>

        </div>

    </div>

    <!-- Modal Konfirmasi Booking -->
    <div id="konfirmasi-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-100">
            <form
                id="konfirmasi-form"
                method="POST"
                action="{{ route('admin.booking.konfirmasi', ['booking' => '__BOOKING_ID__']) }}"
                data-action-template="{{ route('admin.booking.konfirmasi', ['booking' => '__BOOKING_ID__']) }}"
            >
                @csrf
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold tracking-[0.12em] text-[#102863] uppercase">
                            Ringkasan Permohonan
                        </p>
                        <h2 class="mt-1 text-sm font-bold text-slate-900">
                            Bookingan ke-<span id="konfirmasi-number">1</span>
                        </h2>
                    </div>
                    <span class="rounded-none bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700 border border-amber-100">
                        Menunggu Konfirmasi
                    </span>
                </div>
            </div>

            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 gap-3 text-xs sm:grid-cols-3">
                    <div class="rounded-none bg-[#f8fafd] p-3.5 border border-slate-100">
                        <dt class="text-slate-400">Tanggal Bimbingan</dt>
                        <dd id="konfirmasi-date" class="mt-1 font-bold text-slate-900"></dd>
                    </div>
                    <div class="rounded-none bg-[#f8fafd] p-3.5 border border-slate-100">
                        <dt class="text-slate-400">Jam Bimbingan</dt>
                        <dd id="konfirmasi-time" class="mt-1 font-bold text-slate-900"></dd>
                    </div>
                    <div class="rounded-none bg-[#f8fafd] p-3.5 border border-slate-100">
                        <dt class="text-slate-400">Durasi</dt>
                        <dd id="konfirmasi-duration" class="mt-1 font-bold text-slate-900"></dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-none bg-[#f6f8fc] p-3.5 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Keluhan / Kendala yang Diajukan</p>
                    <p id="konfirmasi-problem" class="mt-1 text-xs leading-relaxed text-slate-700"></p>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-5">
                    <h3 class="text-sm font-bold text-slate-900">Penentuan Tutor Pendamping</h3>
                    <p class="mt-1 text-xs text-slate-500">Lengkapi data berikut untuk menyetujui permohonan ini.</p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="tutor_pendamping" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                <span>Tutor Pendamping</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="tutor_pendamping"
                                name="tutor_pendamping"
                                placeholder="Contoh: Dr. Sutanto, M.Kom"
                                required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                            >
                            <p class="mt-1.5 text-[11px] text-slate-400">Wajib diisi.</p>
                        </div>

                        <div>
                            <label for="catatan" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                                <span>Catatan</span>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">Opsional</span>
                            </label>
                            <textarea
                                id="catatan"
                                name="catatan"
                                rows="3"
                                placeholder="Tambahkan catatan untuk mahasiswa (jika perlu)"
                                class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-end gap-2.5">
                        <button
                            id="konfirmasi-back"
                            type="button"
                            class="rounded-xl bg-[#eef3fc] px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                        >
                            Kembali
                        </button>
                        <button
                            id="konfirmasi-approve"
                            type="submit"
                            class="rounded-xl bg-[#102863] px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#0c1f4d] active:scale-[0.99] cursor-pointer"
                        >
                            Setujui
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Modal Jadwal Ulang Booking -->
    <div id="jadwal-ulang-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-100">
            <form
                id="jadwal-ulang-form"
                method="POST"
                action="{{ route('admin.booking.reschedule', ['booking' => '__BOOKING_ID__']) }}"
                data-action-template="{{ route('admin.booking.reschedule', ['booking' => '__BOOKING_ID__']) }}"
            >
                @csrf
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.12em] text-[#102863] uppercase">
                                Ringkasan Permohonan
                            </p>
                            <h2 class="mt-1 text-sm font-bold text-slate-900">
                                Jadwal Ulang · Bookingan ke-<span id="jadwal-number">1</span>
                            </h2>
                        </div>
                        <span class="rounded-none bg-amber-50 px-3 py-1 text-[11px] font-bold text-amber-700 border border-amber-100">
                            Menunggu Konfirmasi
                        </span>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 gap-3 text-xs sm:grid-cols-3">
                        <div class="rounded-xl bg-[#f8fafd] p-3.5 border border-slate-100">
                            <dt class="text-slate-400">Tanggal Lama</dt>
                            <dd id="jadwal-date" class="mt-1 font-bold text-slate-900"></dd>
                        </div>
                        <div class="rounded-xl bg-[#f8fafd] p-3.5 border border-slate-100">
                            <dt class="text-slate-400">Jam Lama</dt>
                            <dd id="jadwal-time" class="mt-1 font-bold text-slate-900"></dd>
                        </div>
                        <div class="rounded-xl bg-[#f8fafd] p-3.5 border border-slate-100">
                            <dt class="text-slate-400">Durasi</dt>
                            <dd id="jadwal-duration" class="mt-1 font-bold text-slate-900"></dd>
                        </div>
                    </dl>

                    <div class="mt-4 rounded-xl bg-[#f6f8fc] p-3.5 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Keluhan / Kendala yang Diajukan</p>
                        <p id="jadwal-problem" class="mt-1 text-xs leading-relaxed text-slate-700"></p>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <h3 class="text-sm font-bold text-slate-900">Pengaturan Jadwal Baru</h3>
                        <p class="mt-1 text-xs text-slate-500">Tentukan tanggal dan jam pelaksanaan yang baru.</p>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="jadwal-tanggal-baru" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                    <span>Pilih Tanggal Baru</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="jadwal-tanggal-baru"
                                    name="tanggal_baru"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                                >
                            </div>

                            <div>
                                <label for="jadwal-jam-baru" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                    <span>Atur Jam Pelaksanaan Baru</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="jadwal-jam-baru"
                                    name="jam_baru"
                                    inputmode="numeric"
                                    placeholder="Contoh: 09.00"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                                >
                                <p class="mt-1.5 text-[11px] text-slate-400">Gunakan format 09.00–19.00 WIB.</p>
                                <p id="jadwal-jam-error" class="mt-1 hidden text-[11px] font-semibold text-rose-600">Jam harus antara 09.00 dan 19.00.</p>
                            </div>

                            <div>
                                <label for="jadwal-durasi" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                    <span>Durasi / Sesi Bimbingan</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <select
                                    id="jadwal-durasi"
                                    name="durasi"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                                >
                                    <option value="30">Sesi 1 (30 Menit)</option>
                                    <option value="60">Sesi 2 (60 Menit)</option>
                                    <option value="90">Sesi 3 (90 Menit)</option>
                                    <option value="120">Sesi 4 (120 Menit)</option>
                                </select>
                                <p class="mt-1.5 text-[11px] text-slate-400">Pilih durasi sesi untuk jadwal yang baru.</p>
                            </div>

                            <div>
                                <label for="jadwal-tutor" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                    <span>Tutor / Pembimbing</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="jadwal-tutor"
                                    name="tutor_pendamping"
                                    placeholder="Contoh: Dr. Sutanto, M.Kom"
                                    required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                                >
                                <p class="mt-1.5 text-[11px] text-slate-400">Wajib diisi.</p>
                            </div>

                            <div>
                                <label for="jadwal-alasan" class="mb-2 flex items-center gap-1 text-[13px] font-semibold text-slate-800">
                                    <span>Alasan Pengajuan Jadwal Ulang</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <textarea
                                    id="jadwal-alasan"
                                    name="alasan"
                                    rows="3"
                                    placeholder="Jelaskan alasan perubahan jadwal"
                                    required
                                    class="w-full resize-none rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm leading-relaxed text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                                ></textarea>
                                <p class="mt-1.5 text-[11px] text-slate-400">Wajib diisi.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center justify-end gap-2.5">
                            <button
                                id="jadwal-back"
                                type="button"
                                class="rounded-xl bg-[#eef3fc] px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-[#e1ebfc] cursor-pointer"
                            >
                                Kembali
                            </button>
                            <button
                                id="jadwal-submit"
                                type="submit"
                                class="rounded-xl bg-[#102863] px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-[#0c1f4d] active:scale-[0.99] cursor-pointer"
                            >
                                Ajukan Jadwal Ulang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmModal = document.getElementById('konfirmasi-modal');
            const confirmForm = document.getElementById('konfirmasi-form');
            const confirmBack = document.getElementById('konfirmasi-back');
            const confirmSummary = {
                number: document.getElementById('konfirmasi-number'),
                date: document.getElementById('konfirmasi-date'),
                time: document.getElementById('konfirmasi-time'),
                duration: document.getElementById('konfirmasi-duration'),
                problem: document.getElementById('konfirmasi-problem'),
            };

            const jadwalModal = document.getElementById('jadwal-ulang-modal');
            const jadwalForm = document.getElementById('jadwal-ulang-form');
            const jadwalBack = document.getElementById('jadwal-back');
            const jadwalTutor = document.getElementById('jadwal-tutor');
            const tanggalBaru = document.getElementById('jadwal-tanggal-baru');
            const jamBaru = document.getElementById('jadwal-jam-baru');
            const jamError = document.getElementById('jadwal-jam-error');
            const jadwalDurasi = document.getElementById('jadwal-durasi');
            const jadwalSummary = {
                number: document.getElementById('jadwal-number'),
                date: document.getElementById('jadwal-date'),
                time: document.getElementById('jadwal-time'),
                duration: document.getElementById('jadwal-duration'),
                problem: document.getElementById('jadwal-problem'),
            };

            function bookingData(button) {
                const card = button.closest('[data-booking-number]');
                return card ? card.dataset : {};
            }

            function show(element) {
                if (!element) return;
                element.classList.remove('hidden');
                element.classList.add('flex');
            }

            function hide(element) {
                if (!element) return;
                element.classList.add('hidden');
                element.classList.remove('flex');
            }

            function closeAll() {
                hide(confirmModal);
                hide(jadwalModal);
                if (confirmForm) confirmForm.reset();
                if (jadwalForm) jadwalForm.reset();
                if (jamError) jamError.classList.add('hidden');
            }

            function openConfirm(button) {
                const data = bookingData(button);
                if (confirmForm && confirmForm.dataset.actionTemplate) {
                    confirmForm.action = confirmForm.dataset.actionTemplate.replace('__BOOKING_ID__', data.bookingId ?? '');
                }
                confirmSummary.number.textContent = data.bookingNumber ?? '';
                confirmSummary.date.textContent = data.bookingDate ?? '';
                confirmSummary.time.textContent = data.bookingTime ?? '';
                confirmSummary.duration.textContent = data.bookingDuration ?? '';
                confirmSummary.problem.textContent = data.bookingProblem ?? '';
                show(confirmModal);
            }

            function parseJam(value) {
                const match = value.trim().match(/^(\d{1,2})[.:](\d{2})$/);
                if (!match) return null;
                const hours = parseInt(match[1], 10);
                const minutes = parseInt(match[2], 10);
                if (hours > 23 || minutes > 59) return null;
                return hours * 60 + minutes;
            }

            function validateJam() {
                if (!jamBaru || !jamError) return false;
                const minutes = parseJam(jamBaru.value);
                const valid = minutes !== null && minutes >= 540 && minutes <= 1140;
                jamError.classList.toggle('hidden', valid);
                return valid;
            }

            function openJadwal(button) {
                const data = bookingData(button);
                if (jadwalForm && jadwalForm.dataset.actionTemplate) {
                    jadwalForm.action = jadwalForm.dataset.actionTemplate.replace('__BOOKING_ID__', data.bookingId ?? '');
                }
                jadwalSummary.number.textContent = data.bookingNumber ?? '';
                jadwalSummary.date.textContent = data.bookingDate ?? '';
                jadwalSummary.time.textContent = data.bookingTime ?? '';
                jadwalSummary.duration.textContent = data.bookingDuration ?? '';
                jadwalSummary.problem.textContent = data.bookingProblem ?? '';
                if (tanggalBaru) tanggalBaru.value = data.bookingDateRaw ?? '';
                if (jamBaru) {
                    jamBaru.value = data.bookingStart ?? '';
                    validateJam();
                }
                if (jadwalDurasi) jadwalDurasi.value = data.bookingDurationValue ?? '60';
                if (jadwalTutor) jadwalTutor.value = data.bookingTutor ?? '';
                show(jadwalModal);
            }

            document.querySelectorAll('.js-konfirmasi').forEach(function (button) {
                button.addEventListener('click', function () {
                    openConfirm(button);
                });
            });

            document.querySelectorAll('.js-jadwal-ulang').forEach(function (button) {
                button.addEventListener('click', function () {
                    openJadwal(button);
                });
            });

            if (jamBaru) {
                jamBaru.addEventListener('input', validateJam);
                jamBaru.addEventListener('blur', function () {
                    const minutes = parseJam(jamBaru.value);
                    if (minutes !== null) {
                        jamBaru.value = String(Math.floor(minutes / 60)).padStart(2, '0') + '.' + String(minutes % 60).padStart(2, '0');
                    }
                    validateJam();
                });
            }

            if (confirmBack) confirmBack.addEventListener('click', closeAll);
            if (jadwalBack) jadwalBack.addEventListener('click', closeAll);
            if (jadwalForm) jadwalForm.addEventListener('submit', function (event) {
                if (!validateJam()) {
                    event.preventDefault();
                    if (jamBaru) jamBaru.focus();
                }
            });

            [confirmModal, jadwalModal].forEach(function (element) {
                if (!element) return;
                element.addEventListener('click', function (event) {
                    if (event.target === element) closeAll();
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeAll();
            });
        });
    </script>

</body>
</html>

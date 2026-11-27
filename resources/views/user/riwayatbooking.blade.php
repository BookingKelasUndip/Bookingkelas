<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking Mahasiswa - Kelas Tambahan Undip</title>
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
                <span>Semester Ganjil 2024/2025</span>
            </div>

            <h1 class="font-serif text-3xl sm:text-[34px] font-bold tracking-tight text-slate-900">
                Riwayat Booking Mahasiswa
            </h1>
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">
                Pantau status pengajuan jadwal bimbingan tambahan, verifikasi kehadiran asisten dosen, serta akses tiket presensi laboratorium Anda.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-xs font-bold text-emerald-700 border border-emerald-100">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Tabs & Search Bar Card -->
        <section class="rounded-none bg-white p-4 sm:p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <!-- Status Filter -->
                @php
                    $filters = [
                        'all' => 'Semua',
                        'menunggu' => 'Menunggu',
                        'dikonfirmasi' => 'Terkonfirmasi',
                        'ajukan_ulang' => 'Ajukan Ulang',
                        'ditolak' => 'Ditolak / Ajukan Ulang',
                        'selesai' => 'Selesai',
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
                            href="{{ route('riwayatbooking', ['status' => $value]) }}"
                            @if ($isActive) aria-current="page" @endif
                            class="inline-flex items-center rounded-md border bg-white px-3 py-2 text-xs transition cursor-pointer {{ $filterStyle }} {{ $isActive ? 'border-2 font-bold shadow-xs' : 'font-semibold' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Input -->
                <div class="relative w-full lg:w-72 shrink-0">
                    <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                    <input
                        type="search"
                        name="search"
                        placeholder="Cari mata kuliah atau dosen..."
                        class="w-full rounded-xl border border-slate-200/80 bg-[#f8fafd] pl-10 pr-4 py-2.5 text-xs text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:bg-white focus:outline-none focus:ring-3 focus:ring-[#1d3c8a]/10"
                    >
                </div>

            </div>
        </section>

        <!-- Area Daftar Riwayat / Konten -->
        <section class="mt-6 min-h-[120px] space-y-4">
            @forelse ($bookings as $booking)
                <article class="rounded-none bg-white p-5 shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-[10px] font-bold tracking-[0.14em] text-[#1d3c8a] uppercase">
                                Riwayat Bimbingan
                            </p>
                            <h2 class="mt-1 text-sm font-bold text-slate-900">
                                {{ $booking->user?->name ?? 'Mahasiswa' }}
                            </h2>
                            <p class="mt-0.5 text-[11px] text-slate-400">
                                Bookingan ke-{{ $booking->booking_number }}
                            </p>
                        </div>
                        @php
                            $userBadge = match ($booking->status) {
                                'dikonfirmasi' => 'text-emerald-700 border-emerald-300',
                                'ajukan_ulang' => 'text-blue-700 border-blue-300',
                                'ditolak' => 'text-rose-700 border-rose-300',
                                'selesai' => 'text-slate-600 border-slate-300',
                                default => 'text-amber-700 border-amber-300',
                            };
                        @endphp
                        <span class="rounded-none border px-2.5 py-1 text-[11px] font-bold {{ $userBadge }}">
                            {{ $booking->status_label }}
                        </span>
                    </div>

                    <dl class="mt-4 grid grid-cols-1 gap-3 text-xs sm:grid-cols-3">
                        <div>
                            <dt class="text-slate-400">Tanggal</dt>
                            <dd class="mt-0.5 font-bold text-slate-900">
                                {{ $booking->date->locale('id')->translatedFormat('l, d F Y') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400">Jam Bimbingan</dt>
                            <dd class="mt-0.5 font-bold text-slate-900">{{ $booking->start_time }} – {{ $booking->end_time }} WIB</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400">Durasi</dt>
                            <dd class="mt-0.5 font-bold text-slate-900">{{ $booking->session_label }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 rounded-xl bg-[#f4f7fc] p-3.5 border border-[#e3eaf8]">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Keluhan / Kendala Belajar</p>
                            @if ($booking->status === 'dikonfirmasi')
                                <button
                                    type="button"
                                    class="js-lihat-jawaban inline-flex items-center gap-1.5 rounded-lg bg-[#1d3c8a] px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-[#163175] cursor-pointer"
                                    data-number="{{ $booking->booking_number }}"
                                    data-date="{{ $booking->date->locale('id')->translatedFormat('l, d F Y') }}"
                                    data-time="{{ $booking->start_time }} – {{ $booking->end_time }} WIB"
                                    data-tutor="{{ $booking->tutor_pendamping }}"
                                    data-catatan="{{ $booking->catatan ?: 'Tidak ada catatan' }}"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1 1 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Lihat Jawaban</span>
                                </button>
                            @elseif ($booking->status === 'ajukan_ulang')
                                <button
                                    type="button"
                                    data-id="{{ $booking->id }}"
                                    class="js-lihat-usulan inline-flex items-center gap-1.5 rounded-lg bg-[#1d3c8a] px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-[#163175] cursor-pointer"
                                    data-number="{{ $booking->booking_number }}"
                                    data-old-date="{{ $booking->date->locale('id')->translatedFormat('l, d F Y') }}"
                                    data-old-time="{{ $booking->start_time }} – {{ $booking->end_time }} WIB"
                                    data-old-duration="{{ $booking->session_label }}"
                                    data-problem="{{ $booking->problem_description }}"
                                    data-tutor="{{ $booking->tutor_pendamping }}"
                                    data-new-date="{{ $booking->reschedule_date?->locale('id')->translatedFormat('l, d F Y') }}"
                                    data-new-time="{{ $booking->reschedule_start_time }} – {{ $booking->reschedule_end_time }} WIB"
                                    data-new-duration="{{ $booking->reschedule_session_label }}"
                                    data-alasan="{{ $booking->reschedule_alasan }}"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Lihat Jawaban</span>
                                </button>
                            @endif
                        </div>
                        <p class="mt-1 text-xs leading-relaxed text-slate-700">{{ $booking->problem_description }}</p>
                    </div>
                </article>
            @empty
                <div class="rounded-none bg-white p-8 text-center shadow-[0_8px_24px_-10px_rgba(15,23,42,0.05)] border border-slate-100">
                    <p class="text-sm font-bold text-slate-900">Belum ada booking</p>
                    <p class="mt-1 text-xs text-slate-500">
                        Mulai buat jadwal bimbingan Anda melalui halaman Pemesanan Sesi Kelas Tambahan.
                    </p>
                    <a href="{{ route('bookingkelas') }}" class="mt-4 inline-flex rounded-xl bg-[#1d3c8a] px-4 py-2 text-xs font-bold text-white transition hover:bg-[#163175]">
                        Buat Booking
                    </a>
                </div>
            @endforelse
        </section>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->links('components.pagination') }}
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-12 border-t border-slate-100 bg-white">
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

            <div class="text-[11px] text-slate-400">
                © 2025 Universitas Diponegoro.
            </div>
        </div>
    </footer>

    <!-- Modal Jawaban Konfirmasi -->
    <div id="jawaban-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-100">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Bookingan anda telah dikonfirmasi</h2>
                        <p class="mt-1 text-xs text-slate-500">Bookingan ke-<span id="jawaban-number">1</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 px-6 py-5">
                <dl class="grid grid-cols-1 gap-3 text-xs sm:grid-cols-2">
                    <div class="rounded-none bg-[#f8fafd] p-3.5 border border-slate-100">
                        <dt class="text-slate-400">Tanggal</dt>
                        <dd id="jawaban-date" class="mt-1 font-bold text-slate-900"></dd>
                    </div>
                    <div class="rounded-none bg-[#f8fafd] p-3.5 border border-slate-100">
                        <dt class="text-slate-400">Jam</dt>
                        <dd id="jawaban-time" class="mt-1 font-bold text-slate-900"></dd>
                    </div>
                </dl>

                <div class="rounded-none bg-emerald-50/70 p-3.5 border border-emerald-100">
                    <p class="text-[10px] font-bold text-emerald-700 uppercase">Tutor / Pembimbing yang Ditugaskan</p>
                    <p id="jawaban-tutor" class="mt-1 text-sm font-bold text-slate-900"></p>
                </div>

                <div class="rounded-none bg-[#f4f7fc] p-3.5 border border-[#e3eaf8]">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Catatan</p>
                    <p id="jawaban-catatan" class="mt-1 text-xs leading-relaxed text-slate-700"></p>
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-100 px-6 py-4">
                <button
                    id="jawaban-close"
                    type="button"
                    class="rounded-xl bg-[#1d3c8a] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#163175] cursor-pointer"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Usulan Jadwal Ulang -->
    <div id="usulan-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6 backdrop-blur-sm">
        <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl border border-slate-100">
            <div class="border-b border-slate-100 px-6 py-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#1d3c8a]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Usulan Jadwal Ulang dari Admin</h2>
                        <p class="mt-1 text-xs text-slate-500">Bookingan ke-<span id="usulan-number">1</span></p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 px-6 py-5">
                <section class="rounded-none bg-[#f8fafd] p-4 border border-slate-100">
                    <p class="text-[10px] font-bold tracking-[0.12em] text-slate-400 uppercase">Ringkasan Bookingan Sebelumnya</p>
                    <dl class="mt-3 grid grid-cols-1 gap-3 text-xs sm:grid-cols-3">
                        <div>
                            <dt class="text-slate-400">Tanggal</dt>
                            <dd id="usulan-old-date" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                        <div>
                            <dt class="text-slate-400">Jam</dt>
                            <dd id="usulan-old-time" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                        <div>
                            <dt class="text-slate-400">Durasi</dt>
                            <dd id="usulan-old-duration" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                    </dl>
                    <div class="mt-3 rounded-none bg-white p-3 border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Keluhan / Kendala</p>
                        <p id="usulan-problem" class="mt-1 text-xs leading-relaxed text-slate-700"></p>
                    </div>
                </section>

                <section class="rounded-none bg-blue-50/70 p-4 border border-blue-100">
                    <p class="text-[10px] font-bold tracking-[0.12em] text-[#1d3c8a] uppercase">Ringkasan Usulan Admin</p>
                    <dl class="mt-3 grid grid-cols-1 gap-3 text-xs sm:grid-cols-2">
                        <div class="rounded-none bg-white p-3 border border-blue-100">
                            <dt class="text-slate-400">Tutor / Pembimbing</dt>
                            <dd id="usulan-tutor" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                        <div class="rounded-none bg-white p-3 border border-blue-100">
                            <dt class="text-slate-400">Hari yang Diganti</dt>
                            <dd id="usulan-new-date" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                        <div class="rounded-none bg-white p-3 border border-blue-100">
                            <dt class="text-slate-400">Jam Pelaksanaan</dt>
                            <dd id="usulan-new-time" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                        <div class="rounded-none bg-white p-3 border border-blue-100">
                            <dt class="text-slate-400">Durasi</dt>
                            <dd id="usulan-new-duration" class="mt-0.5 font-bold text-slate-900"></dd>
                        </div>
                    </dl>
                    <div class="mt-3 rounded-none bg-white p-3 border border-blue-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Alasan Perubahan</p>
                        <p id="usulan-alasan" class="mt-1 text-xs leading-relaxed text-slate-700"></p>
                    </div>
                </section>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2.5 border-t border-slate-100 px-6 py-4">
                <form
                    id="usulan-tolak-form"
                    method="POST"
                    action="{{ route('riwayatbooking.tolak', ['booking' => '__BOOKING_ID__']) }}"
                    data-action-template="{{ route('riwayatbooking.tolak', ['booking' => '__BOOKING_ID__']) }}"
                >
                    @csrf
                    <button type="submit" class="rounded-xl bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 transition hover:bg-rose-100 cursor-pointer">
                        Tolak
                    </button>
                </form>
                <form
                    id="usulan-setuju-form"
                    method="POST"
                    action="{{ route('riwayatbooking.setuju', ['booking' => '__BOOKING_ID__']) }}"
                    data-action-template="{{ route('riwayatbooking.setuju', ['booking' => '__BOOKING_ID__']) }}"
                >
                    @csrf
                    <button type="submit" class="rounded-xl bg-[#1d3c8a] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#163175] cursor-pointer">
                        Setuju
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jawabanModal = document.getElementById('jawaban-modal');
            const jawabanClose = document.getElementById('jawaban-close');
            const jawabanFields = {
                number: document.getElementById('jawaban-number'),
                date: document.getElementById('jawaban-date'),
                time: document.getElementById('jawaban-time'),
                tutor: document.getElementById('jawaban-tutor'),
                catatan: document.getElementById('jawaban-catatan'),
            };

            const usulanModal = document.getElementById('usulan-modal');
            const usulanSetuju = document.getElementById('usulan-setuju-form');
            const usulanTolak = document.getElementById('usulan-tolak-form');
            const usulanFields = {
                number: document.getElementById('usulan-number'),
                oldDate: document.getElementById('usulan-old-date'),
                oldTime: document.getElementById('usulan-old-time'),
                oldDuration: document.getElementById('usulan-old-duration'),
                problem: document.getElementById('usulan-problem'),
                tutor: document.getElementById('usulan-tutor'),
                newDate: document.getElementById('usulan-new-date'),
                newTime: document.getElementById('usulan-new-time'),
                newDuration: document.getElementById('usulan-new-duration'),
                alasan: document.getElementById('usulan-alasan'),
            };

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
                hide(jawabanModal);
                hide(usulanModal);
            }

            document.querySelectorAll('.js-lihat-jawaban').forEach(function (button) {
                button.addEventListener('click', function () {
                    jawabanFields.number.textContent = button.dataset.number ?? '';
                    jawabanFields.date.textContent = button.dataset.date ?? '';
                    jawabanFields.time.textContent = button.dataset.time ?? '';
                    jawabanFields.tutor.textContent = button.dataset.tutor ?? '';
                    jawabanFields.catatan.textContent = button.dataset.catatan || 'Tidak ada catatan';
                    show(jawabanModal);
                });
            });

            document.querySelectorAll('.js-lihat-usulan').forEach(function (button) {
                button.addEventListener('click', function () {
                    const id = button.dataset.id ?? '';
                    if (usulanSetuju && usulanSetuju.dataset.actionTemplate) {
                        usulanSetuju.action = usulanSetuju.dataset.actionTemplate.replace('__BOOKING_ID__', id);
                    }
                    if (usulanTolak && usulanTolak.dataset.actionTemplate) {
                        usulanTolak.action = usulanTolak.dataset.actionTemplate.replace('__BOOKING_ID__', id);
                    }
                    usulanFields.number.textContent = button.dataset.number ?? '';
                    usulanFields.oldDate.textContent = button.dataset.oldDate ?? '';
                    usulanFields.oldTime.textContent = button.dataset.oldTime ?? '';
                    usulanFields.oldDuration.textContent = button.dataset.oldDuration ?? '';
                    usulanFields.problem.textContent = button.dataset.problem ?? '';
                    usulanFields.tutor.textContent = button.dataset.tutor ?? '';
                    usulanFields.newDate.textContent = button.dataset.newDate ?? '';
                    usulanFields.newTime.textContent = button.dataset.newTime ?? '';
                    usulanFields.newDuration.textContent = button.dataset.newDuration ?? '';
                    usulanFields.alasan.textContent = button.dataset.alasan ?? '';
                    show(usulanModal);
                });
            });

            if (jawabanClose) jawabanClose.addEventListener('click', closeAll);
            [jawabanModal, usulanModal].forEach(function (element) {
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

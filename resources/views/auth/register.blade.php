<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru - Portal Akademik Mahasiswa UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f7fb] text-slate-800 antialiased flex flex-col items-center justify-center px-4 py-10">

    <!-- Top Badge -->
    <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-[11px] font-semibold tracking-[0.14em] text-[#1d3c8a] shadow-sm border border-slate-100">
        <span class="h-1.5 w-1.5 rounded-full bg-[#1d3c8a]"></span>
        <span>PORTAL AKADEMIK MAHASISWA UNDIP</span>
    </div>

    <!-- Main Card -->
    <div class="w-full max-w-[540px] rounded-2xl bg-white px-6 py-9 sm:px-11 sm:py-11 shadow-[0_12px_35px_-10px_rgba(15,23,42,0.06)] border border-slate-100">

        <!-- Header & Logo -->
        <div class="text-center">
            <div class="mx-auto mb-4 flex h-24 w-24 items-center justify-center rounded-2xl bg-[#f8fafd] p-2.5 border border-slate-100/80">
                <img
                    src="{{ asset('images/Logo_Universitas_Diponegoro.png') }}"
                    alt="Logo Universitas Diponegoro"
                    class="h-full w-full object-contain"
                >
            </div>

            <p class="text-[11px] font-semibold tracking-[0.18em] text-slate-400 uppercase">
                Universitas Diponegoro
            </p>
            <h1 class="mt-1.5 font-serif text-3xl sm:text-[32px] font-bold tracking-tight text-slate-900">
                Buat Akun Baru
            </h1>
            <p class="mx-auto mt-2.5 max-w-md text-sm leading-relaxed text-slate-500">
                Isi data mahasiswa, verifikasi email kampus Anda, lalu buat kata sandi untuk mengakses sistem bimbingan.
            </p>
        </div>

        <hr class="my-7 border-slate-100">

        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700 border border-rose-100">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('code-sent'))
            <div class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-700 border border-emerald-100">
                {{ session('code-sent') }}
            </div>
        @endif

        <form action="{{ route('register.verify') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                    <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Nama Lengkap</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Contoh: Muhammad Rizky Pratama"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                >
            </div>

            <!-- Email Mahasiswa + Kirim Kode -->
            <div>
                <label for="email" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                    <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Email Mahasiswa</span>
                </label>
                <div class="flex flex-col sm:flex-row gap-2.5">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="nama@students.undip.ac.id"
                        class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                    >
                    <button
                        type="submit"
                        formaction="{{ route('register.code') }}"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#1d3c8a] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162f6e] active:scale-[0.99] cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>Kirim Kode</span>
                    </button>
                </div>
                <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Sistem memastikan alamat email benar-benar aktif di Undip saat kode dikirim.</span>
                </p>
            </div>

            <!-- NIM -->
            <div>
                <label for="nim" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                    <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                    <span>NIM (Nomor Induk Mahasiswa)</span>
                </label>
                <input
                    type="text"
                    id="nim"
                    name="nim"
                    value="{{ old('nim') }}"
                    inputmode="numeric"
                    required
                    placeholder="Contoh: 21120120140088"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                >
            </div>

            <!-- Kode Verifikasi -->
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label for="otp" class="flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                        <span>Kode Verifikasi</span>
                    </label>
                    <span class="text-[11px] font-semibold tracking-wider text-[#1d3c8a] uppercase">
                        6 Digit OTP
                    </span>
                </div>
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    value="{{ old('otp') }}"
                    maxlength="6"
                    inputmode="numeric"
                    placeholder="Masukkan 6 digit kode OTP"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                >
                <p class="mt-2 flex items-center gap-1.5 text-xs text-slate-500">
                    <svg class="h-3.5 w-3.5 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        {{ session('code-sent')
                            ? 'Masukkan kode yang dikirim ke email kampus Anda.'
                            : 'Isi nama, email, dan NIM, lalu klik “Kirim Kode”.' }}
                    </span>
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1d3c8a] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162f6e] active:scale-[0.99] cursor-pointer"
                >
                    <span>Lanjut Buat Kata Sandi</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>

        <hr class="my-7 border-slate-100">

        <p class="text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="inline-flex items-center gap-0.5 font-semibold text-[#1d3c8a] hover:underline">
                <span>Masuk di sini</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </p>
    </div>

</body>
</html>

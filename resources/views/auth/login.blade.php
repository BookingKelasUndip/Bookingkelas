<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Sistem Booking - Portal Akademik Mahasiswa UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen overflow-x-hidden bg-[#f8faff] text-slate-800 antialiased flex flex-col items-center justify-center px-4 py-10">

    <!-- Soft Corner Ambient Gradients -->
    <div class="pointer-events-none fixed -left-32 -top-32 h-[420px] w-[420px] rounded-full bg-[#e6ecfa]/80 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-36 -right-32 h-[440px] w-[440px] rounded-full bg-[#e3eaf9]/80 blur-3xl"></div>

    <div class="relative z-10 w-full max-w-[540px]">

        <!-- Top Badge -->
        <div class="mb-5 flex justify-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-[11px] font-semibold tracking-[0.14em] text-[#1d3c8a] shadow-[0_2px_10px_rgba(15,23,42,0.04)] border border-slate-100">
                <span class="h-1.5 w-1.5 rounded-full bg-[#1d3c8a]"></span>
                <span>PORTAL AKADEMIK MAHASISWA UNDIP</span>
            </div>
        </div>

        <!-- Main Card -->
        <div class="w-full rounded-2xl bg-white px-6 py-9 sm:px-11 sm:py-11 shadow-[0_14px_40px_-12px_rgba(15,23,42,0.07)] border border-slate-100/90">

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
                    Masuk ke Sistem Booking
                </h1>
                <p class="mx-auto mt-2.5 max-w-md text-sm leading-relaxed text-slate-500">
                    Silakan masukkan email kampus, NIM, dan kata sandi untuk mengakses sistem bimbingan.
                </p>
            </div>

            <hr class="my-7 border-slate-100">

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700 border border-rose-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Email Kampus / Mahasiswa -->
                <div>
                    <label for="email" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Email Kampus / Mahasiswa</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@students.undip.ac.id"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                    >
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

                <!-- Info Box SSO & Akademik -->
                <div class="rounded-xl bg-[#f3f6fc] px-4 py-3.5 border border-[#e3eaf8] flex items-start gap-3">
                    <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <p class="text-xs leading-relaxed text-[#2b447e]">
                        Data NIM dan email diverifikasi langsung melalui database terpusat SSO &amp; Akademik Universitas Diponegoro.
                    </p>
                </div>

                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6h.01M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2zm3 0V8a4 4 0 118 0v3" />
                        </svg>
                        <span>Kata Sandi</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        placeholder="Masukkan kata sandi"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                    >
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1d3c8a] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162f6e] active:scale-[0.99] cursor-pointer"
                    >
                        <span>Masuk Sekarang</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <hr class="my-7 border-slate-100">

            <!-- Footer Link -->
            <p class="text-center text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="inline-flex items-center gap-0.5 font-semibold text-[#1d3c8a] hover:underline">
                    <span>Daftar sekarang</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </p>
        </div>

        <!-- Bottom Info Strip -->
        <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-2 px-1 text-[11px] text-slate-400">
            <div class="inline-flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-[#1d3c8a]/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Sistem Autentikasi Mahasiswa Undip</span>
                <span class="text-slate-300">•</span>
                <a href="{{ route('admin.login') }}" class="font-medium text-slate-500 hover:text-[#1d3c8a] hover:underline">Login Admin</a>
            </div>
            <div>
                <span>Bantuan Teknis:</span>
                <a href="mailto:helpdesk@undip.ac.id" class="font-medium text-slate-500 hover:text-[#1d3c8a] hover:underline">
                    helpdesk@undip.ac.id
                </a>
            </div>
        </div>

    </div>

</body>
</html>
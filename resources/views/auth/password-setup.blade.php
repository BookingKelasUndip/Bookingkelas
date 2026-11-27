<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kata Sandi - Portal Akademik Mahasiswa UNDIP</title>
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
                Langkah Terakhir
            </p>
            <h1 class="mt-1.5 font-serif text-3xl sm:text-[32px] font-bold tracking-tight text-slate-900">
                Buat Kata Sandi
            </h1>
            <p class="mx-auto mt-2.5 max-w-md text-sm leading-relaxed text-slate-500">
                Email <span class="font-semibold text-[#1d3c8a]">{{ $email }}</span> telah terverifikasi. Buat kata sandi untuk mengamankan akun Anda.
            </p>
        </div>

        <hr class="my-7 border-slate-100">

        @if ($errors->any())
            <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700 border border-rose-100">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Step indicator -->
        <div class="mb-6 flex items-center justify-center gap-2 text-[11px] font-semibold tracking-wide uppercase">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Data & OTP
            </span>
            <span class="h-px w-6 bg-slate-200"></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-[#1d3c8a] px-3 py-1.5 text-white">
                2. Kata Sandi
            </span>
            <span class="h-px w-6 bg-slate-200"></span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-slate-500">
                3. Login
            </span>
        </div>

        <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
            @csrf

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
                    autocomplete="new-password"
                    required
                    minlength="8"
                    placeholder="Minimal 8 karakter"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                >
            </div>

            <!-- Konfirmasi Kata Sandi -->
            <div>
                <label for="password_confirmation" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                    <svg class="h-4 w-4 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Konfirmasi Kata Sandi</span>
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                    minlength="8"
                    placeholder="Ulangi kata sandi"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#1d3c8a] focus:outline-none focus:ring-4 focus:ring-[#1d3c8a]/10"
                >
            </div>

            <p class="flex items-center gap-1.5 text-xs text-slate-500">
                <svg class="h-3.5 w-3.5 shrink-0 text-[#1d3c8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Setelah selesai, Anda akan diarahkan ke halaman login untuk masuk menggunakan email, NIM, dan kata sandi.</span>
            </p>

            <!-- Submit Button -->
            <div class="pt-2">
                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#1d3c8a] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#162f6e] active:scale-[0.99] cursor-pointer"
                >
                    <span>Selesaikan Registrasi</span>
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal Admin - Kelas Tambahan UNDIP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen overflow-x-hidden bg-[#f6f8fc] text-slate-800 antialiased flex flex-col items-center justify-center px-4 py-10">

    <div class="pointer-events-none fixed -left-32 -top-32 h-[420px] w-[420px] rounded-full bg-[#e6ecfa]/80 blur-3xl"></div>
    <div class="pointer-events-none fixed -bottom-36 -right-32 h-[440px] w-[440px] rounded-full bg-[#dfe7f8]/80 blur-3xl"></div>

    <div class="relative z-10 w-full max-w-[540px]">

        <div class="mb-5 flex justify-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-[11px] font-semibold tracking-[0.14em] text-[#102863] shadow-[0_2px_10px_rgba(15,23,42,0.04)] border border-slate-100">
                <span class="h-1.5 w-1.5 rounded-full bg-[#102863]"></span>
                <span>PORTAL ADMIN KELAS TAMBAHAN UNDIP</span>
            </div>
        </div>

        <div class="w-full rounded-2xl bg-white px-6 py-9 sm:px-11 sm:py-11 shadow-[0_14px_40px_-12px_rgba(15,23,42,0.07)] border border-slate-100/90">

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
                    Masuk Portal Admin
                </h1>
                <p class="mx-auto mt-2.5 max-w-md text-sm leading-relaxed text-slate-500">
                    Silakan masukkan email undip dan kata sandi untuk mengelola booking kelas tambahan.
                </p>
            </div>

            <hr class="my-7 border-slate-100">

            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-700 border border-rose-100">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-[#102863]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Email Undip</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@undip.ac.id"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 flex items-center gap-2 text-[13px] font-semibold text-slate-800">
                        <svg class="h-4 w-4 text-[#102863]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6h.01M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2zm3 0V8a4 4 0 118 0v3" />
                        </svg>
                        <span>Kata Sandi</span>
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition focus:border-[#102863] focus:outline-none focus:ring-4 focus:ring-[#102863]/10"
                    >
                </div>

                <div class="rounded-xl border border-dashed border-[#c8d6f0] bg-[#f8fafd] px-4 py-3.5">
                    <p class="text-[11px] font-bold tracking-wider text-[#102863] uppercase">Akun Demo Admin</p>
                    <div class="mt-2 space-y-1 text-xs text-slate-600">
                        <p><strong>Email:</strong> admin@undip.ac.id</p>
                        <p><strong>Kata sandi:</strong> password</p>
                    </div>
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#102863] px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#0c1f4d] active:scale-[0.99] cursor-pointer"
                    >
                        <span>Masuk Portal Admin</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <hr class="my-7 border-slate-100">

            <p class="text-center text-sm text-slate-500">
                Login sebagai mahasiswa?
                <a href="{{ route('login') }}" class="inline-flex items-center gap-0.5 font-semibold text-[#102863] hover:underline">
                    <span>Kembali ke login user</span>
                </a>
            </p>
        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP — NeoManga</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/verify-otp.css') }}">
</head>
<body class="min-h-screen bg-[#0b0f19] text-white flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Dekorasi --}}
    <div class="absolute inset-0 bg-grid"></div>
    <div class="absolute -top-40 -right-40 w-[480px] h-[480px] bg-brand/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-[480px] h-[480px] bg-indigo-600/15 rounded-full blur-3xl"></div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 mb-8 group">
            <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-brand text-white text-xl shadow-lg glow-brand group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="font-display text-3xl font-bold tracking-tight">Neo<span class="text-brand">Manga</span></span>
        </a>

        {{-- Kartu OTP --}}
        <div class="bg-white/[.04] backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand/15 border border-brand/30 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-envelope-open-text text-2xl text-brand"></i>
                </div>
                <h1 class="font-display text-2xl font-bold mb-2">Verifikasi Email</h1>
                <p class="text-sm text-slate-400 leading-relaxed mb-8">
                    Kode 6 digit telah dikirim ke <span class="text-white font-medium">{{ auth()->user()->email }}</span>.<br>
                    Masukkan kode untuk mengaktifkan akunmu.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-sm text-emerald-300">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 flex items-start gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-sm text-red-300">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verify.otp') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="otp" class="block text-sm font-medium text-slate-300 mb-2">Kode OTP</label>
                    <input type="text"
                           id="otp"
                           name="otp"
                           class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white text-center text-2xl font-mono tracking-[.5em] placeholder-slate-600 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                           placeholder="000000"
                           maxlength="6"
                           required
                           autofocus
                           autocomplete="off"
                           inputmode="numeric">
                    @error('otp')
                        <p class="mt-2 text-sm text-red-400 flex items-center">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-brand hover:bg-brand-dark font-display font-semibold text-white text-sm tracking-wide transition-all hover:shadow-lg hover:shadow-brand/25 active:scale-[.98]">
                    <i class="fa-solid fa-shield-check mr-2"></i>Verifikasi
                </button>
            </form>

            {{-- Kirim ulang --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/5"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-[#0d1220] text-slate-500">Tidak menerima kode?</span>
                </div>
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('resend.otp') }}">
                    @csrf
                    <button type="submit" id="resendButton"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/5 hover:border-brand/40 transition-all">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span id="resendText">Tunggu 30 detik</span>
                    </button>
                </form>
            </div>

            {{-- Info kadaluarsa --}}
            <div class="mt-6 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-yellow-500/10 border border-yellow-500/25">
                    <i class="fa-solid fa-clock text-yellow-400 text-sm"></i>
                    <span class="text-sm text-yellow-300 font-medium">Kode OTP kadaluarsa dalam 5 menit</span>
                </div>
            </div>

            <p class="mt-6 text-center text-xs text-slate-600">
                Pastikan cek folder spam/sampah jika email tidak ditemukan
            </p>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">© {{ date('Y') }} NeoManga — Baca Manga, Manhwa &amp; Manhua</p>
    </div>

    <script src="{{ asset('js/auth/verify-otp-2.js') }}"></script>
</body>
</html>
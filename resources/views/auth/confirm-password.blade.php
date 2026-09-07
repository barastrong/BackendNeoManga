<!DOCTYPE html>
<html lang="id" style="overflow-x:hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password — NeoManga</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body class="min-h-screen bg-[#0b0f19] text-white flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Dekorasi --}}
    <div class="absolute inset-0 bg-grid"></div>
    <div class="absolute -top-40 -right-40 w-[480px] h-[480px] bg-brand/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-[480px] h-[480px] bg-indigo-600/15 rounded-full blur-3xl"></div>
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl"></div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 mb-8 group">
            <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-brand text-white text-xl shadow-lg glow-brand group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="font-display text-3xl font-bold tracking-tight">Neo<span class="text-brand">Manga</span></span>
        </a>

        {{-- Kartu konfirmasi --}}
        <div class="bg-white/[.04] backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-1">
                <h1 class="font-display text-2xl font-bold">Konfirmasi password</h1>
                <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-brand/15 text-brand border border-brand/20">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
            </div>
            <p class="text-sm text-slate-400 mb-6">Ini area aman — masukkan password untuk lanjut 🛡️</p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-sm text-red-300">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <ul class="list-disc ml-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5"><i class="fa-solid fa-lock w-5 text-brand"></i>Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 text-sm"></i>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full pl-11 pr-11 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePass('password','eyeIcon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="text-red-400 text-sm mt-1 hidden" id="passwordError"></div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-brand hover:bg-brand-dark font-display font-semibold text-white text-sm tracking-wide transition-all hover:shadow-lg hover:shadow-brand/25 active:scale-[.98]">
                    <i class="fa-solid fa-lock-open mr-2"></i>Konfirmasi
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/5 text-center text-sm text-slate-400">
                Bukan kamu?
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-semibold text-brand hover:text-brand-dark transition-colors">Keluar</button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">© {{ date('Y') }} NeoManga — Baca Manga, Manhwa &amp; Manhua</p>
    </div>

    <script src="{{ asset('js/auth/confirm-password.js') }}"></script>
</body>
</html>

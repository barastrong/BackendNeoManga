<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — NeoManga</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>
<body class="min-h-screen bg-[#0b0f19] text-white flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Dekorasi --}}
    <div class="absolute inset-0 bg-grid"></div>
    <div class="absolute -top-40 -right-40 w-[480px] h-[480px] bg-brand/20 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-[480px] h-[480px] bg-indigo-600/15 rounded-full blur-3xl"></div>
    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl"></div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 mb-7 group">
            <span class="flex items-center justify-center w-12 h-12 rounded-2xl bg-brand text-white text-xl shadow-lg glow-brand group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="font-display text-3xl font-bold tracking-tight">Neo<span class="text-brand">Manga</span></span>
        </a>

        {{-- Kartu daftar --}}
        <div class="bg-white/[.04] backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
            <div class="flex items-center gap-3 mb-1">
                <h1 class="font-display text-2xl font-bold">Daftar akun</h1>
                <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider bg-brand/15 text-brand rounded-full border border-brand/20">Gratis</span>
            </div>
            <p class="text-sm text-slate-400 mb-5">Satu langkah lagi — mulai baca manga favoritmu 📚</p>

            {{-- Value props --}}
            <div class="grid grid-cols-3 gap-2 mb-6">
                <div class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/[.04] border border-white/5">
                    <i class="fa-solid fa-infinity text-brand"></i>
                    <span class="text-xs text-slate-300 text-center">Gratis</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/[.04] border border-white/5">
                    <i class="fa-solid fa-bolt text-amber-400"></i>
                    <span class="text-xs text-slate-300 text-center">Update Tiap Hari</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-white/[.04] border border-white/5">
                    <i class="fa-solid fa-shield-halved text-emerald-400"></i>
                    <span class="text-xs text-slate-300 text-center">Data Aman</span>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-sm text-red-300">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <ul class="list-disc ml-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('register-store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1.5"><i class="fa-regular fa-user w-5 text-brand"></i>Nama</label>
                    <div class="relative">
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                               placeholder="Nama kamu">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5"><i class="fa-regular fa-envelope w-5 text-brand"></i>Email</label>
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                               placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5"><i class="fa-solid fa-lock w-5 text-brand"></i>Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full pl-4 pr-11 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                               placeholder="Buat password (min. 8 karakter)">
                        <button type="button" onclick="togglePass('password','eyeIcon')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="flex gap-1.5 mt-2">
                        <div class="meter-bar"></div>
                        <div class="meter-bar"></div>
                        <div class="meter-bar"></div>
                        <div class="meter-bar"></div>
                    </div>
                    <p id="pwHint" class="text-xs text-slate-500 mt-1.5">Gunakan huruf, angka &amp; simbol minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5"><i class="fa-solid fa-shield-halved w-5 text-brand"></i>Ulangi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full pl-4 pr-11 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-brand/60 focus:ring-2 focus:ring-brand/30 transition-all"
                               placeholder="Ketik ulang password">
                        <button type="button" onclick="togglePass('password_confirmation','eyeIcon2')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300 transition-colors">
                            <i id="eyeIcon2" class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-brand hover:bg-brand-dark font-display font-semibold text-white text-sm tracking-wide transition-all hover:shadow-lg hover:shadow-brand/25 active:scale-[.98]">
                    <i class="fa-solid fa-user-plus mr-2"></i>Buat Akun &amp; Daftar
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/5 text-center text-sm text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-brand hover:text-brand-dark transition-colors">Masuk di sini</a>
            </div>
        </div>

        <p class="text-center text-xs text-slate-600 mt-6">© {{ date('Y') }} NeoManga — Baca Manga, Manhwa &amp; Manhua</p>
    </div>

    <script src="{{ asset('js/auth/register.js') }}"></script>
</body>
</html>

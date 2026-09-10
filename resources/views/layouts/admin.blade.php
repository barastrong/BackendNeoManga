<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel — NeoManga')</title>
    <link rel="stylesheet" href="/css/app.css">
    <!-- Theme restore inline sebelum CSS render -->
    <script>
        (function () {
            try {
                var t = localStorage.getItem('nm-theme');
                var dark = t !== 'light';
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/bulk.css') }}">
    <script defer src="{{ asset('js/admin/bulk.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-[#0b0f19] font-sans antialiased text-slate-300">

<div x-data="{ sidebarOpen: false }" class="adm-layout min-h-screen">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="adm-overlay" :class="sidebarOpen ? 'open' : ''" x-cloak></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'open' : ''"
           class="adm-sidebar">
        <div class="adm-sb-brand">
            <img src="/images/neomanga-logo.png" alt="NeoManga" class="adm-sb-logo">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="adm-sb-name">Neo<span>Manga</span></a>
                <p class="adm-sb-sub">Admin Panel</p>
            </div>
        </div>

        <div class="adm-sb-label">Menu Utama</div>
        <nav class="adm-sb-nav">
            <a href="{{ route('admin.dashboard') }}" class="adm-sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>Dashboard
            </a>
            <a href="{{ route('admin.user.index') }}" class="adm-sb-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>Users
            </a>
            <a href="{{ route('admin.analytics') }}" class="adm-sb-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>Analisis &amp; Statistik
            </a>
            <a href="{{ route('admin.manga.index') }}" class="adm-sb-item {{ request()->routeIs('admin.manga.*') ? 'active' : '' }}">
                <img src="/images/neomanga-logo.png" alt="NeoManga" style="width:18px;height:18px;object-fit:contain;border-radius:4px;vertical-align:-3px">Manga
            </a>
            <a href="{{ route('admin.chapter.index') }}" class="adm-sb-item {{ request()->routeIs('admin.chapter.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i>Chapter
            </a>
            <a href="{{ route('admin.category.index') }}" class="adm-sb-item {{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i>Kategori
            </a>
            <a href="{{ route('admin.moderation.index') }}" class="adm-sb-item {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                <i class="fa-solid fa-flag"></i>Moderasi
            </a>
        </nav>

        <div class="adm-sb-label">Lainnya</div>
        <nav class="adm-sb-nav">
            <a href="{{ route('dashboard') }}" class="adm-sb-item">
                <i class="fa-solid fa-globe"></i>Lihat Situs
            </a>
            <a href="{{ route('profile.edit') }}" class="adm-sb-item">
                <i class="fa-solid fa-user-gear"></i>Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="adm-sb-item" style="width:100%;background:none;border:none;cursor:pointer;color:#f87171;text-align:left">
                    <i class="fa-solid fa-right-from-bracket"></i>Keluar
                </button>
            </form>
        </nav>

        <div class="adm-sb-foot">
            <div class="adm-sb-card">
                <b>NeoManga v2.0</b>
                <span>© {{ date('Y') }} — Semua hak cipta</span>
            </div>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="adm-main flex-1">
        <header class="flex items-center justify-between px-6 h-[72px] adm-surface border-b border-white/5 sticky top-0 z-10">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-slate-400 hover:text-white focus:outline-none lg:hidden">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <h2 class="font-display text-lg font-semibold text-white hidden sm:block">
                            @yield('page-title', 'Dashboard')
                        </h2>
                    </div>

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2.5 rounded-full py-1.5 pl-1.5 pr-3 hover:bg-white/10 transition-colors focus:outline-none">
                            <img class="h-8 w-8 rounded-full object-cover ring-2 ring-brand/50"
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=ff2e4d&color=fff" alt="Avatar">
                            <span class="hidden md:block text-sm font-medium text-slate-200">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-500"></i>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                             class="absolute right-0 mt-2 w-52 bg-[#131a2c] rounded-xl shadow-xl border border-white/10 py-1.5 z-20" x-cloak>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10">
                                <i class="fa-regular fa-user mr-2.5 text-slate-500"></i>Profil Saya
                            </a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-white/10">
                                <i class="fa-solid fa-globe mr-2.5 text-slate-500"></i>Lihat Situs
                            </a>
                            <hr class="my-1.5 border-white/10">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-500/10">
                                    <i class="fa-solid fa-right-from-bracket mr-2.5"></i>Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8">
                    <div class="max-w-7xl mx-auto">
                        @yield('content')
                    </div>
                </main>
    </div>
</div>

@stack('scripts')
</body>
</html>

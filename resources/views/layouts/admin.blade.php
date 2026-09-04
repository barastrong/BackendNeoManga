<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-[#0b0f19] font-sans antialiased text-slate-300">

<div x-data="{ sidebarOpen: false }" class="relative min-h-screen lg:flex">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 z-20 bg-black/50 transition-opacity lg:hidden" x-cloak></div>

    {{-- Theme var untuk admin (native CSS) --}}
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-64 bg-[#0d1220] text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static">
        <div class="flex items-center gap-3 px-5 h-[72px] border-b border-white/5">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-brand text-white shadow-lg shadow-brand/30">
                <i class="fa-solid fa-book-open text-sm"></i>
            </span>
            <div class="leading-tight">
                <a href="{{ route('admin.dashboard') }}" class="font-display text-lg font-bold tracking-tight">Neo<span class="text-brand">Manga</span></a>
                <p class="text-[10px] uppercase tracking-widest text-slate-500">Admin Panel</p>
            </div>
        </div>

        <div class="px-3 mt-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500 px-5 py-3">Menu Utama</div>
        <nav class="px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.dashboard') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>Dashboard
            </a>
            <a href="{{ route('admin.user.index') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.user.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-users w-5 text-center"></i>Users
            </a>
            <a href="{{ route('admin.manga.index') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.manga.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-book-open w-5 text-center"></i>Manga
            </a>
            <a href="{{ route('admin.chapter.index') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.chapter.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i>Chapter
            </a>
            <a href="{{ route('admin.category.index') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.category.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-tags w-5 text-center"></i>Kategori
            </a>
            <a href="{{ route('admin.moderation.index') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200
                      {{ request()->routeIs('admin.moderation.*') ? 'bg-brand text-white shadow-lg shadow-brand/25' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-flag w-5 text-center"></i>Moderasi
            </a>
        </nav>

        <div class="px-3 mt-6 text-[10px] font-semibold uppercase tracking-widest text-slate-500 px-5 py-3">Lainnya</div>
        <nav class="px-3 space-y-1">
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200 text-slate-400 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-globe w-5 text-center"></i>Lihat Situs
            </a>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200 text-slate-400 hover:bg-white/5 hover:text-white">
                <i class="fa-solid fa-user-gear w-5 text-center"></i>Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-sm font-medium transition-all duration-200 text-red-400 hover:bg-red-500/10 hover:text-red-300">
                    <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>Keluar
                </button>
            </form>
        </nav>

        <div class="absolute bottom-5 left-0 right-0 px-6">
            <div class="rounded-xl bg-white/5 border border-white/10 p-3.5 text-xs text-slate-400">
                <p class="font-semibold text-slate-300 mb-0.5">NeoManga v2.0</p>
                <p>© {{ date('Y') }} — Semua hak cipta</p>
            </div>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="flex-1 flex flex-col min-w-0">
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

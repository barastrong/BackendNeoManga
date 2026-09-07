bash.exe: warning: could not find /tmp, please create!
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>@yield('title', 'NeoManga — Baca Manga, Manhwa & Manhua Bahasa Indonesia')</title>
    <meta name="description" content="@yield('meta_description', 'Baca manga, manhwa & manhua bahasa Indonesia gratis di NeoManga. Update terbaru setiap hari, lengkap dan nyaman.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:site_name" content="NeoManga">
    <meta property="og:title" content="@yield('title', 'NeoManga — Baca Manga')">
    <meta property="og:description" content="@yield('meta_description', 'Baca manga, manhwa & manhua bahasa Indonesia gratis di NeoManga.')">
    <meta property="og:type" content="website">
    <meta name="robots" content="index, follow">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <!-- Theme restore inline sebelum CSS render (anti flash) -->
    <script>
        (function () {
            try {
                var t = localStorage.getItem('nm-theme');
                var dark = t !== 'light'; // dark-first
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="min-h-screen flex flex-col">
    <div x-data="{ mobileMenuOpen: false, mobileSearchOpen: false }" x-init="$watch('mobileMenuOpen', v => document.body.style.overflow = v ? 'hidden' : 'auto')" class="min-h-screen flex flex-col">

        <!-- ======= HEADER ======= -->
        <header class="sticky top-0 z-40 w-full backdrop-blur-xl bg-white/80 dark:bg-[#0b0f19]/85 border-b border-slate-200/70 dark:border-white/5">
            <div class="container-nm">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-2">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden btn-icon -ml-2" aria-label="Menu">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 flex-shrink-0">
                            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#ff2e4d] text-white shadow-lg shadow-[#ff2e4d]/30">
                                <i class="fa-solid fa-book-open text-lg"></i>
                            </span>
                            <span class="font-display text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                                Neo<span class="text-[#ff2e4d]">Manga</span>
                            </span>
                        </a>
                    </div>

                    <!-- Nav desktop -->
                    <nav class="hidden lg:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('/') ? 'nav-link-active' : '' }}">Beranda</a>
                        <a href="{{ route('manga.list') }}" class="nav-link {{ request()->is('manga*') || request()->is('search') ? 'nav-link-active' : '' }}">Daftar Manga</a>
                        <a href="{{ route('history.index') }}" class="nav-link {{ request()->is('history*') ? 'nav-link-active' : '' }}">Riwayat</a>
                        <a href="{{ route('bookmark.index') }}" class="nav-link {{ request()->is('bookmarks*') ? 'nav-link-active' : '' }}">Bookmark</a>
                    </nav>

                    <!-- Aksi kanan -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="hidden md:block">
                            <form action="{{ route('manga.search') }}" method="GET" class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input name="q" type="search" placeholder="Cari manga..." value="{{ request('q') ?? '' }}"
                                       class="w-36 lg:w-44 bg-slate-100 dark:bg-white/5 border border-transparent text-sm pl-9 pr-3 py-2 rounded-full
                                              focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/60 focus:bg-white dark:focus:bg-slate-800
                                              transition-all duration-300 focus:w-48 lg:focus:w-60 placeholder:text-slate-400 dark:placeholder:text-slate-500">
                            </form>
                        </div>

                        <button @click="mobileSearchOpen = !mobileSearchOpen" class="md:hidden btn-icon" aria-label="Cari">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>

                        <button id="themeToggle" aria-label="Ganti tema" class="btn-icon">
                            <i id="themeIconSun" class="fa-solid fa-sun text-lg"></i>
                            <i id="themeIconMoon" class="fa-solid fa-moon text-lg" style="display:none"></i>
                        </button>

                        @auth
                            @if(Auth::check() && Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" aria-label="Panel Admin" class="btn-icon bg-[#ff2e4d]/10 text-[#ff2e4d] hover:bg-[#ff2e4d]/20">
                                    <i class="fa-solid fa-user-shield"></i>
                                </a>
                            @endif

                            <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                                <button @click="dropdownOpen = !dropdownOpen" class="block focus:outline-none transition-transform duration-200 hover:scale-105" :aria-expanded="dropdownOpen.toString()">
                                    <img class="w-9 h-9 rounded-full object-cover ring-2 ring-[#ff2e4d]/60 ring-offset-2 ring-offset-white dark:ring-offset-[#0b0f19]" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ff2e4d&color=fff&font-size=0.45" alt="Avatar">
                                </button>
                                <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-3 w-72 bg-white dark:bg-[#0d1220] rounded-2xl shadow-2xl shadow-black/40 ring-1 ring-black/5 dark:ring-white/10 origin-top-right overflow-hidden z-50" style="display: none;">
                                    {{-- Header card user --}}
                                    <div class="relative px-5 pt-5 pb-4 bg-gradient-to-br from-[#ff2e4d]/15 via-transparent to-indigo-500/10 dark:from-[#ff2e4d]/20 dark:via-transparent dark:to-indigo-500/10">
                                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#ff2e4d]/20 rounded-full blur-2xl pointer-events-none"></div>
                                        <div class="flex items-center gap-3.5 relative">
                                            <div class="relative flex-shrink-0">
                                                <img class="w-14 h-14 rounded-2xl object-cover ring-2 ring-[#ff2e4d]/50 ring-offset-2 ring-offset-[#0d1220]" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ff2e4d&color=fff&size=112&font-size=0.4" alt="{{ auth()->user()->name }}">
                                                @if(auth()->user()->isAdmin())
                                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#ff2e4d] text-white text-[9px] flex items-center justify-center ring-2 ring-[#0d1220]"><i class="fa-solid fa-shield-halved"></i></span>
                                                @else
                                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 text-white text-[9px] flex items-center justify-center ring-2 ring-[#0d1220]"><i class="fa-solid fa-check"></i></span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-display font-bold text-[15px] text-slate-900 dark:text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                                @if(auth()->user()->isAdmin())
                                                    <span class="inline-flex items-center gap-1 mt-1.5 text-[9px] font-bold uppercase tracking-wider bg-[#ff2e4d]/15 text-[#ff4d66] border border-[#ff2e4d]/25 rounded-full px-2 py-0.5"><i class="fa-solid fa-user-shield text-[8px]"></i> Admin</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 mt-1.5 text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/25 rounded-full px-2 py-0.5"><i class="fa-solid fa-circle-check text-[8px]"></i> Member</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 border-t border-slate-200 dark:border-white/5">
                                        <a href="{{ route('user.profile') }}" class="dropdown-item !rounded-xl"><i class="fa-regular fa-user w-5 text-slate-400"></i><span>Profil Saya</span><i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 dark:text-slate-600"></i></a>
                                        <a href="{{ route('history.index') }}" class="dropdown-item !rounded-xl"><i class="fa-solid fa-clock-rotate-left w-5 text-slate-400"></i><span>Riwayat Baca</span><i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 dark:text-slate-600"></i></a>
                                        <a href="{{ route('bookmark.index') }}" class="dropdown-item !rounded-xl"><i class="fa-solid fa-bookmark w-5 text-slate-400"></i><span>Bookmark</span><i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 dark:text-slate-600"></i></a>
                                    </div>
                                    @if(auth()->check() && auth()->user()->isAdmin())
                                    <div class="p-2 pt-0">
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item !rounded-xl"><i class="fa-solid fa-user-shield w-5 text-slate-400"></i><span>Panel Admin</span><i class="fa-solid fa-chevron-right ml-auto text-[10px] text-slate-300 dark:text-slate-600"></i></a>
                                    </div>
                                    @endif
                                    <div class="p-2 pt-0 border-t border-slate-200 dark:border-white/5">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item !rounded-xl !text-red-600 dark:!text-[#ff4d66] hover:!bg-red-50 dark:hover:!bg-[#ff2e4d]/10">
                                                <i class="fa-solid fa-right-from-bracket w-5"></i><span>Keluar</span><i class="fa-solid fa-chevron-right ml-auto text-[10px] opacity-0"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="hidden lg:flex items-center gap-2">
                                <a href="{{ route('login') }}" class="btn-primary"><i class="fa-solid fa-right-to-bracket mr-1.5 text-xs"></i>Masuk</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Search mobile -->
            <div x-show="mobileSearchOpen" x-transition class="md:hidden border-t border-slate-200 dark:border-white/5" style="display: none;">
                <form action="{{ route('manga.search') }}" method="GET" class="container-nm py-3">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input name="q" type="search" placeholder="Cari manga..." value="{{ request('q') ?? '' }}"
                               class="w-full bg-slate-100 dark:bg-slate-800 text-sm pl-10 pr-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-[#ff2e4d]/60">
                    </div>
                </form>
            </div>
        </header>

        <!-- Mobile drawer -->
        <div x-show="mobileMenuOpen" class="lg:hidden fixed inset-0 z-50" style="display: none;">
            <div @click="mobileMenuOpen = false" x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative w-72 max-w-[80vw] h-full bg-white dark:bg-[#0d1220] shadow-xl flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-white/5">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#ff2e4d] text-white"><i class="fa-solid fa-book-open text-sm"></i></span>
                        <span class="font-display text-lg font-bold text-slate-900 dark:text-white">Neo<span class="text-[#ff2e4d]">Manga</span></span>
                    </a>
                    <button @click="mobileMenuOpen = false" class="btn-icon -mr-2"><i class="fa-solid fa-xmark text-2xl"></i></button>
                </div>
                <nav class="flex-grow p-4 space-y-1.5">
                    @php
                        $mobileLinkClasses = "flex items-center w-full px-4 py-3 text-base font-medium rounded-xl transition-colors";
                        $mobileActiveClasses = 'bg-[#ff2e4d]/10 text-[#ff2e4d] dark:text-[#ff4d66]';
                        $mobileInactiveClasses = 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/5';
                    @endphp
                    <a href="{{ route('dashboard') }}" class="{{ $mobileLinkClasses }} {{ request()->is('/') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-house w-6 mr-3 text-slate-400"></i><span>Beranda</span></a>
                    <a href="{{ route('manga.list') }}" class="{{ $mobileLinkClasses }} {{ request()->is('manga*') || request()->is('search') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-book w-6 mr-3 text-slate-400"></i><span>Daftar Manga</span></a>
                    <a href="{{ route('history.index') }}" class="{{ $mobileLinkClasses }} {{ request()->is('history*') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-clock-rotate-left w-6 mr-3 text-slate-400"></i><span>Riwayat</span></a>
                    <a href="{{ route('bookmark.index') }}" class="{{ $mobileLinkClasses }} {{ request()->is('bookmarks*') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-bookmark w-6 mr-3 text-slate-400"></i><span>Bookmark</span></a>
                </nav>
                @guest
                    <div class="p-4 border-t border-slate-200 dark:border-white/5">
                        <a href="{{ route('login') }}" class="btn-primary text-center w-full block">Masuk</a>
                    </div>
                @endguest
            </div>
        </div>

        <!-- ======= MAIN ======= -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- ======= FOOTER ======= -->
        <footer class="mt-auto border-t border-slate-200/70 dark:border-white/5 bg-white dark:bg-[#0d1220]">
            <div class="container-nm py-14">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
                    <div class="lg:col-span-1">
                        <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-4">
                            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#ff2e4d] text-white"><i class="fa-solid fa-book-open"></i></span>
                            <span class="font-display text-xl font-extrabold text-slate-900 dark:text-white">Neo<span class="text-[#ff2e4d]">Manga</span></span>
                        </a>
                        <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">Platform baca manga, manhwa &amp; manhua bahasa Indonesia. Update terbaru setiap hari, gratis dan nyaman.</p>
                    </div>
                    <div>
                        <h3 class="footer-heading">Navigasi</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Beranda</a></li>
                            <li><a href="{{ route('manga.list') }}" class="footer-link">Daftar Manga</a></li>
                            <li><a href="{{ route('history.index') }}" class="footer-link">Riwayat</a></li>
                            <li><a href="{{ route('bookmark.index') }}" class="footer-link">Bookmark</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="footer-heading">Lainnya</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="footer-link">Tentang Kami</a></li>
                            <li><a href="#" class="footer-link">Kebijakan Privasi</a></li>
                            <li><a href="{{ route('manga.list') }}" class="footer-link">Semua Manga</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="footer-heading">Ikuti Kami</h3>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-[#1DA1F2] hover:text-white transition-all duration-300 hover:-translate-y-1" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-[#1877F2] hover:text-white transition-all duration-300 hover:-translate-y-1" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:bg-[#E4405F] hover:text-white transition-all duration-300 hover:-translate-y-1" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-200/70 dark:border-white/5">
                <div class="container-nm py-5 text-center text-sm text-slate-500 dark:text-slate-500">
                    <p>© {{ date('Y') }} NeoManga. Seluruh hak cipta dilindungi.</p>
                </div>
            </div>
        </footer>
    </div>

<script src="{{ asset('js/layouts/theme.js') }}"></script>
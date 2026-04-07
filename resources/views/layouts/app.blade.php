<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'NeoManga'))</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style type="text/tailwindcss">
        @layer base {
            html {
                font-family: 'Figtree', sans-serif;
            }
            body {
                @apply bg-slate-50 dark:bg-slate-900;
                background-image: radial-gradient(ellipse at top, #f1f5f9, #e2e8f0), radial-gradient(ellipse at top, transparent, #e2e8f0);
            }
            .dark body {
                background-image: radial-gradient(ellipse at top, #1e293b, #0f172a), radial-gradient(ellipse at top, transparent, #0f172a);
            }
            @keyframes subtle-pan {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .aurora-bg {
                background-size: 400% 400%;
                animation: subtle-pan 15s ease infinite;
                @apply bg-gradient-to-br from-indigo-100 via-sky-100 to-white dark:from-slate-900 dark:via-slate-800/80 dark:to-indigo-950/70;
            }
        }
        @layer components {
            .btn-icon {
                @apply flex items-center justify-center h-10 w-10 rounded-full text-slate-500 dark:text-slate-400 bg-transparent hover:bg-slate-500/10 transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900;
            }
            .btn-primary {
                @apply inline-flex items-center justify-center px-6 py-2.5 rounded-lg text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 transition-all duration-200 shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transform hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500 dark:focus-visible:ring-offset-slate-900;
            }
            .dropdown-item {
                @apply flex items-center space-x-3 w-full px-3 py-2 text-sm rounded-lg text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors duration-200;
            }
            .nav-link {
                @apply relative px-3.5 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 transition-colors duration-300 hover:text-slate-900 dark:hover:text-white after:content-[''] after:absolute after:w-0 after:h-0.5 after:bottom-1 after:left-1/2 after:-translate-x-1/2 after:bg-indigo-500 after:transition-all after:duration-300;
            }
            .nav-link-active {
                @apply text-slate-900 dark:text-white after:w-1/2;
            }
            .footer-heading {
                @apply text-sm font-semibold uppercase text-slate-400 dark:text-slate-500 mb-4 tracking-wider;
            }
            .footer-link {
                @apply text-slate-500 dark:text-slate-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-colors duration-200;
            }
            .social-link {
                @apply w-10 h-10 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-800 text-slate-500 dark:text-slate-400 transition-all duration-300 transform hover:scale-110 hover:-translate-y-1;
            }
        }
    </style>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Figtree', 'sans-serif'] } } }
        }
    </script>
    
    @stack('styles')
</head>

<body class="font-sans antialiased text-slate-800 dark:text-slate-200 transition-colors duration-300">
    <div x-data="{ mobileMenuOpen: false, mobileSearchOpen: false }" x-init="$watch('mobileMenuOpen', v => document.body.style.overflow = v ? 'hidden' : 'auto')" id="app" class="min-h-screen flex flex-col">
        <header id="page-header" class="sticky top-0 z-40 w-full transition-transform duration-300">
            <div class="relative bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-900/5 dark:border-white/5">
                <div class="absolute inset-0 -z-10 opacity-70 dark:opacity-100 aurora-bg"></div>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center space-x-2">
                            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden btn-icon -ml-2">
                                <i class="fa-solid fa-bars-staggered text-xl"></i>
                            </button>
                            <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center space-x-3">
                                <i class="fa-solid fa-book-journal-whills text-indigo-600 dark:text-indigo-500 text-3xl"></i>
                                <span class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">NeoManga</span>
                            </a>
                        </div>
                        
                        <nav class="hidden lg:flex items-center space-x-4">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('/') ? 'nav-link-active' : '' }}">Home</a>
                            <a href="{{ route('manga.list') }}" class="nav-link {{ request()->is('manga*') || request()->is('search') ? 'nav-link-active' : '' }}">Manga List</a>
                            <a href="{{ route('history.index') }}" class="nav-link {{ request()->is('history*') ? 'nav-link-active' : '' }}">History</a>
                            <a href="{{ route('bookmark.index') }}" class="nav-link {{ request()->is('bookmarks*') ? 'nav-link-active' : '' }}">Bookmark</a>
                        </nav>
                        
                        <div class="flex items-center space-x-1 sm:space-x-2">
                            <div class="hidden sm:block">
                                <form action="{{ route('manga.search') }}" method="GET" class="relative group">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                    <input name="q" type="search" placeholder="Search..." class="w-32 lg:w-40 bg-slate-500/5 dark:bg-white/5 border border-transparent text-sm pl-10 pr-4 py-2 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 focus:w-40 lg:focus:w-56" value="{{ request('q') ?? '' }}">
                                </form>
                            </div>

                            <button @click="mobileSearchOpen = !mobileSearchOpen" class="sm:hidden btn-icon">
                                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                            </button>

                            <button id="autoRefreshToggle" aria-label="Toggle Auto Refresh" class="btn-icon" title="Toggle Auto Refresh">
                                <i class="fa-solid fa-rotate text-lg"></i>
                            </button>
                            
                            <button id="themeToggle" aria-label="Toggle Theme" class="btn-icon">
                                <i class="fa-solid fa-sun text-lg hidden dark:block"></i>
                                <i class="fa-solid fa-moon text-lg dark:hidden"></i>
                            </button>

                            @auth
                                @if(Auth::check() && Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" aria-label="Admin Panel" class="btn-icon bg-red-500/10 text-red-600 hover:bg-red-500/20">
                                        <i class="fa-solid fa-user-shield"></i>
                                    </a>
                                @endif
                                
                                <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                                    <button @click="dropdownOpen = !dropdownOpen" class="transition-transform duration-200 hover:scale-105 block focus:outline-none">
                                        <img class="w-10 h-10 rounded-full object-cover ring-2 ring-offset-2 ring-offset-slate-100 dark:ring-offset-slate-900 ring-indigo-500" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&font-size=0.45" alt="Avatar">
                                    </button>
                                    
                                    <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-2xl shadow-black/10 ring-1 ring-black/5 dark:ring-white/10 origin-top-right p-2" style="display: none;">
                                        <div class="px-2.5 py-2 border-b border-slate-200 dark:border-white/10">
                                            <p class="font-semibold truncate text-slate-800 dark:text-white">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                                        </div>
                                        <div class="py-1.5 space-y-1">
                                            <a href="#" class="dropdown-item"><i class="fa-regular fa-user w-5 text-slate-400"></i><span>Profile</span></a>
                                            <a href="#" class="dropdown-item"><i class="fa-solid fa-gear w-5 text-slate-400"></i><span>Settings</span></a>
                                        </div>
                                        <div class="border-t border-slate-200 dark:border-white/10 my-1"></div>
                                        <div class="py-1.5">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item !text-red-600 dark:!text-red-400 hover:!bg-red-50 dark:hover:!bg-red-500/10">
                                                    <i class="fa-solid fa-right-from-bracket w-5"></i><span>Logout</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="hidden lg:flex items-center space-x-2">
                                    <a href="{{ route('login') }}" class="btn-primary">Login</a>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
                <div x-show="mobileSearchOpen" x-transition class="sm:hidden border-t border-slate-200 dark:border-white/5" style="display: none;">
                     <form action="{{ route('manga.search') }}" method="GET" class="relative p-4">
                        <i class="fa-solid fa-magnifying-glass absolute left-7 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input name="q" type="search" placeholder="Cari manga..." class="w-full bg-slate-100 dark:bg-slate-800 text-sm pl-10 pr-4 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ request('q') ?? '' }}">
                    </form>
                </div>
            </div>
        </header>

        <div x-show="mobileMenuOpen" class="lg:hidden fixed inset-0 z-50" style="display: none;">
            <div @click="mobileMenuOpen = false" x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative w-72 max-w-[80vw] h-full bg-white dark:bg-slate-800 shadow-xl flex flex-col">
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2"><i class="fa-solid fa-book-journal-whills text-indigo-600 dark:text-indigo-500 text-2xl"></i><span class="text-xl font-bold text-slate-800 dark:text-white">NeoManga</span></a>
                    <button @click="mobileMenuOpen = false" class="btn-icon -mr-2"><i class="fa-solid fa-xmark text-2xl"></i></button>
                </div>
                <nav class="flex-grow p-4 space-y-2">
                    @php
                        $mobileLinkClasses = "flex items-center w-full px-4 py-3 text-base font-medium rounded-lg transition-colors";
                        $mobileActiveClasses = 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400';
                        $mobileInactiveClasses = 'text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/50';
                    @endphp
                    <a href="{{ route('dashboard') }}" class="{{ $mobileLinkClasses }} {{ request()->is('/') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-house w-6 mr-3 text-slate-400"></i><span>Home</span></a>
                    <a href="{{ route('manga.list') }}" class="{{ $mobileLinkClasses }} {{ request()->is('manga*') || request()->is('search') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-book w-6 mr-3 text-slate-400"></i><span>Manga List</span></a>
                    <a href="{{ route('history.index') }}" class="{{ $mobileLinkClasses }} {{ request()->is('history*') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-clock-rotate-left w-6 mr-3 text-slate-400"></i><span>History</span></a>
                    <a href="{{ route('bookmark.index') }}" class="{{ $mobileLinkClasses }} {{ request()->is('bookmarks*') ? $mobileActiveClasses : $mobileInactiveClasses }}"><i class="fa-solid fa-bookmark w-6 mr-3 text-slate-400"></i><span>Bookmark</span></a>
                </nav>
                @guest
                    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('login') }}" class="btn-primary text-center w-full block">Login</a>
                    </div>
                @endguest
            </div>
        </div>

        <main class="flex-grow">
            @yield('content')
        </main>

        <footer class="bg-white dark:bg-slate-950/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    <div class="md:col-span-2 lg:col-span-1">
                        <a href="{{ url('/') }}" class="flex items-center space-x-2 text-xl font-bold text-slate-800 dark:text-white mb-4"><i class="fa-solid fa-book-journal-whills text-indigo-500"></i><span>NeoManga</span></a>
                        <p class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">Platform baca manga online terlengkap dengan update terbaru. Nikmati pengalaman membaca yang nyaman dan modern.</p>
                    </div>
                    <div>
                        <h3 class="footer-heading">Navigasi</h3>
                        <ul class="space-y-3">
                            <li><a href="{{ route('dashboard') }}" class="footer-link">Home</a></li>
                            <li><a href="{{ route('manga.list') }}" class="footer-link">Manga List</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="footer-heading">Lainnya</h3>
                        <ul class="space-y-3">
                            <li><a href="#" class="footer-link">Tentang Kami</a></li>
                            <li><a href="#" class="footer-link">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="footer-heading">Ikuti Kami</h3>
                        <div class="flex space-x-3">
                            <a href="#" class="social-link hover:bg-[#1DA1F2] hover:text-white" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                            <a href="#" class="social-link hover:bg-[#1877F2] hover:text-white" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="#" class="social-link hover:bg-[#E4405F] hover:text-white" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-100 dark:bg-black/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 text-center text-sm text-slate-500 dark:text-slate-400">
                    <p>© {{ date('Y') }} {{ config('app.name', 'NeoManga') }}. All Rights Reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const themeToggle = document.getElementById('themeToggle');
                const html = document.documentElement;
                const applyTheme = (theme) => html.classList.toggle('dark', theme === 'dark');
                const toggleTheme = () => {
                    const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                    localStorage.setItem('theme', newTheme);
                    applyTheme(newTheme);
                };
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                applyTheme(savedTheme || (systemPrefersDark ? 'dark' : 'light'));
                if (themeToggle) themeToggle.addEventListener('click', toggleTheme);

                const autoRefreshToggle = document.getElementById('autoRefreshToggle');
                const refreshIcon = autoRefreshToggle ? autoRefreshToggle.querySelector('i') : null;
                let refreshTimer = null;
                const autoRefreshInterval = 15000;
                const startAutoRefresh = () => {
                    if (refreshTimer || !refreshIcon) return;
                    refreshIcon.classList.add('fa-spin', 'text-indigo-500', 'dark:text-indigo-400');
                    autoRefreshToggle.setAttribute('title', 'Auto Refresh is ON');
                    refreshTimer = setInterval(() => window.location.reload(), autoRefreshInterval);
                };
                const stopAutoRefresh = () => {
                    clearInterval(refreshTimer);
                    refreshTimer = null;
                    if(refreshIcon){
                        refreshIcon.classList.remove('fa-spin', 'text-indigo-500', 'dark:text-indigo-400');
                        autoRefreshToggle.setAttribute('title', 'Auto Refresh is OFF');
                    }
                };
                const toggleAutoRefresh = () => {
                    const isEnabled = localStorage.getItem('autoRefresh') === 'true';
                    localStorage.setItem('autoRefresh', !isEnabled);
                    isEnabled ? stopAutoRefresh() : startAutoRefresh();
                };
                if (autoRefreshToggle && refreshIcon) {
                    if (localStorage.getItem('autoRefresh') === 'true') startAutoRefresh();
                    else stopAutoRefresh();
                    autoRefreshToggle.addEventListener('click', toggleAutoRefresh);
                }
                
                let lastScrollTop = 0;
                const header = document.getElementById('page-header');
                if (header) {
                    window.addEventListener('scroll', () => {
                        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        if (scrollTop > lastScrollTop && scrollTop > header.offsetHeight) {
                            header.style.transform = 'translateY(-100%)';
                        } else {
                            header.style.transform = 'translateY(0)';
                        }
                        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
                    }, { passive: true });
                }
            } catch (error) { console.error("An error occurred in the main script:", error); }
        });
    </script>
    @stack('scripts')
</body>
</html>
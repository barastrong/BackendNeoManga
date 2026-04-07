<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manga Site</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased">

<div x-data="{ sidebarOpen: false }" class="relative min-h-screen lg:flex">

    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 z-20 bg-black/50 transition-opacity lg:hidden"
        x-cloak
    ></div>

    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
    >
        <div class="flex items-center justify-center px-6 h-20 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center text-white">
                <i class="fa-solid fa-shield-halved fa-lg text-indigo-400"></i>
                <span class="ml-3 text-xl font-bold tracking-wider">AdminPanel</span>
            </a>
        </div>

        <nav class="mt-4 px-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center rounded-lg px-4 py-3 transition-colors duration-200 border-l-4 
                      {{ request()->routeIs('admin.dashboard') 
                         ? 'bg-slate-800 border-indigo-500 text-white' 
                         : 'border-transparent text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-6 text-center"></i>
                <span class="ml-4">Dashboard</span>
            </a>

            <a href="{{ route('admin.user.index') }}" 
               class="mt-2 flex items-center rounded-lg px-4 py-3 transition-colors duration-200 border-l-4 
                      {{ request()->routeIs('admin.user.*') 
                         ? 'bg-slate-800 border-indigo-500 text-white' 
                         : 'border-transparent text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users w-6 text-center"></i>
                <span class="ml-4">Users</span>
            </a>
            
            <a href="{{ route('admin.manga.index') }}" 
               class="mt-2 flex items-center rounded-lg px-4 py-3 transition-colors duration-200 border-l-4 
                      {{ request()->routeIs('admin.manga.*') 
                         ? 'bg-slate-800 border-indigo-500 text-white' 
                         : 'border-transparent text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-book-open w-6 text-center"></i>
                <span class="ml-4">Manga</span>
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="flex justify-between items-center p-4 bg-white border-b border-slate-200">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>
            </div>

            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                    <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Your avatar">
                </button>

                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-20" x-cloak>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Settings</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white">Logout</a>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-6">
            <div class="container mx-auto">
                @yield('content')
            </div>
        </main>
    </div>
</div>

</body>
</html>
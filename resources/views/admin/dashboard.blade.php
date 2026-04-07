@extends('layouts.adminSidebar')

@section('content')
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
        <p class="mt-1 text-slate-500">Selamat datang kembali, Admin!</p>
    </div>

    <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="flex items-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600">
                <i class="fa-solid fa-book fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Manga</p>
                <p class="text-3xl font-bold text-slate-800">{{ $mangaCount }}</p>
            </div>
        </div>

        <div class="flex items-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600">
                <i class="fa-solid fa-layer-group fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Chapter</p>
                <p class="text-3xl font-bold text-slate-800">{{ $chapterCount }}</p>
            </div>
        </div>

        <div class="flex items-center p-6 bg-white rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600">
                <i class="fa-solid fa-users fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-slate-500">Total Pengguna</p>
                <p class="text-3xl font-bold text-slate-800">{{ $userCount }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800">Manga Terbaru</h2>
            <p class="mt-1 text-sm text-slate-500">Daftar manga yang baru saja ditambahkan.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Judul
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                            Tanggal Ditambahkan
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Edit</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($latestMangas as $manga)
                        <tr class="hover:bg-slate-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-900">{{ $manga->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $manga->status === 'ongoing' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($manga->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                {{ $manga->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-box-open fa-3x text-slate-400 mb-4"></i>
                                    <p class="font-semibold">Belum ada manga</p>
                                    <p class="text-sm">Silakan tambahkan manga baru untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
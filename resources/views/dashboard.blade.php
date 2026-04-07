@extends('layouts.app')

@section('title', 'NeoManga - Wesbite Baca Komik Terbaru dan Terlengkap Secara Online.')

@section('content')
<div class="container mx-auto px-4 py-8 md:px-6 md:py-10">
    @if($popularMangas->isNotEmpty())
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Manga Populer</h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-x-4 gap-y-8">
                @foreach($popularMangas as $manga)
                    <div>
                        <div class="relative group">
                            <a href="{{ route('manga.show', $manga->slug) }}">
                                <div class="relative aspect-[3/4] rounded-md overflow-hidden shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    @if($manga->status === 'completed')
                                        <div class="absolute top-6 left-[-34px] transform -rotate-45 bg-red-600 text-white font-bold text-xs uppercase px-8 py-1 shadow-md z-10">
                                            Completed
                                        </div>
                                    @endif
                                    @if($manga->cover_image)
                                        <img src="{{ asset('storage/' . $manga->cover_image) }}" 
                                             alt="{{ $manga->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2">
                                        @if($manga->type === 'manga')
                                            <img src="https://flagcdn.com/w40/jp.png" alt="Manga" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manga (Japan)">
                                        @elseif($manga->type === 'manhwa')
                                            <img src="https://flagcdn.com/w40/kr.png" alt="Manhwa" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manhwa (Korea)">
                                        @elseif($manga->type === 'manhua')
                                            <img src="https://flagcdn.com/w40/cn.png" alt="Manhua" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manhua (China)">
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('manga.show', $manga->slug) }}">
                                <h3 class="font-bold text-base text-gray-900 dark:text-white leading-tight truncate hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ $manga->title }}">
                                    {{ $manga->title }}
                                </h3>
                            </a>
                            
                            @if($manga->latestPublishedChapter)
                            <a href="{{ route('chapter.show', $manga->latestPublishedChapter->slug ) }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <div class="flex justify-between items-center text-sm mt-2 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1">
                                    <span>Chapter {{ $manga->latestPublishedChapter->number }}</span>
                                    <span>
                                        {{ $manga->latestPublishedChapter->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}
                                    </span>
                                </div>
                            </a>
                            @else
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 italic">Belum ada chapter</p>
                            @endif

                            <div class="flex items-center mt-2">
                                @php $rounded_rating = round($manga->ratings_avg_rating * 2) / 2; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $rounded_rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($mangas->count() > 0)
        <div>
            <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Update Comic</h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-x-4 gap-y-8">
                @foreach($mangas as $manga)
                    <div>
                        <div class="relative group">
                            <a href="{{ route('manga.show', $manga->slug) }}">
                                <div class="relative aspect-[3/4] rounded-md overflow-hidden shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    @if($manga->status === 'completed')
                                        <div class="absolute top-6 left-[-34px] transform -rotate-45 bg-red-600 text-white font-bold text-xs uppercase px-8 py-1 shadow-md z-10">
                                            Completed
                                        </div>
                                    @endif
                                    @if($manga->cover_image)
                                        <img src="{{ asset('storage/' . $manga->cover_image) }}" 
                                             alt="{{ $manga->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-300 ease-in-out group-hover:scale-105">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100 dark:bg-gray-700">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    <div class="absolute top-2 right-2">
                                        @if($manga->type === 'manga')
                                            <img src="https://flagcdn.com/w40/jp.png" alt="Manga" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manga (Japan)">
                                        @elseif($manga->type === 'manhwa')
                                            <img src="https://flagcdn.com/w40/kr.png" alt="Manhwa" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manhwa (Korea)">
                                        @elseif($manga->type === 'manhua')
                                            <img src="https://flagcdn.com/w40/cn.png" alt="Manhua" class="w-10 h-6 rounded-sm object-cover shadow-md" title="Manhua (China)">
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('manga.show', $manga->slug) }}">
                                <h3 class="font-bold text-base text-gray-900 dark:text-white leading-tight truncate hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="{{ $manga->title }}">
                                    {{ $manga->title }}
                                </h3>
                            </a>
                            
                            @if($manga->latestPublishedChapter)
                            <a href="{{ route('chapter.show', $manga->latestPublishedChapter->slug ) }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                <div class="flex justify-between items-center text-sm mt-2 border border-gray-300 dark:border-gray-600 rounded-md px-2 py-1">
                                    <span>Chapter {{ $manga->latestPublishedChapter->number }}</span>
                                    <span>
                                        {{ $manga->latestPublishedChapter->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}
                                    </span>
                                </div>
                            </a>
                            @else
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 italic">Belum ada chapter</p>
                            @endif

                            <div class="flex items-center mt-2">
                                @php $rounded_rating = round($manga->ratings_avg_rating * 2) / 2; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $rounded_rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                @if ($mangas->hasPages())
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                        @if ($mangas->onFirstPage())
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 dark:bg-gray-700 rounded-md cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Previous
                            </span>
                        @else
                            <a href="{{ $mangas->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Previous
                            </a>
                        @endif

                        <div class="hidden sm:block text-sm text-gray-700 dark:text-gray-400">
                            Halaman <span class="font-medium text-gray-900 dark:text-white">{{ $mangas->currentPage() }}</span> dari <span class="font-medium text-gray-900 dark:text-white">{{ $mangas->lastPage() }}</span>
                        </div>
                        
                        @if ($mangas->hasMorePages())
                            <a href="{{ $mangas->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition ease-in-out duration-150">
                                Next
                                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            </a>
                        @else
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-200 dark:bg-gray-700 rounded-md cursor-not-allowed">
                                Next
                                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    @else
        <div class="text-center py-20 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
            <svg class="w-20 h-20 mx-auto mb-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <h3 class="text-xl font-medium mb-2 text-gray-900 dark:text-gray-100">Belum ada manga</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manga akan muncul di sini setelah ditambahkan.</p>
        </div>
    @endif
</div>
@endsection
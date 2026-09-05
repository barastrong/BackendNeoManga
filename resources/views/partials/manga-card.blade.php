@props(['manga'])
<div class="manga-card">
    <a href="{{ route('manga.show', $manga->slug) }}" class="block">
        <div class="manga-cover">
            {{-- Badge status --}}
            @if($manga->status === 'completed')
                <span class="absolute top-2 left-2 z-10 bg-emerald-500 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-md shadow">Tamat</span>
            @elseif($manga->status === 'ongoing')
                <span class="absolute top-2 left-2 z-10 bg-[#ff2e4d] text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-md shadow">Ongoing</span>
            @elseif($manga->status === 'hiatus')
                <span class="absolute top-2 left-2 z-10 bg-amber-500 text-white text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-md shadow">Hiatus</span>
            @endif

            {{-- Cover --}}
            @if($manga->cover_image)
                <img src="{{ $manga->cover_url }}" alt="{{ $manga->title }}" loading="lazy"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 group-hover:rotate-[0.5deg]">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-800 dark:to-slate-700">
                    <svg class="w-12 h-12 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif

            {{-- Overlay gradient bawah --}}
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

            {{-- Badge tipe (bendera) --}}
            <div class="absolute top-2 right-2 flex flex-col gap-1">
                @if($manga->type === 'manga')
                    <img src="https://flagcdn.com/w40/jp.png" alt="Manga" class="w-8 h-5 rounded object-cover shadow ring-1 ring-black/20" title="Manga (Jepang)">
                @elseif($manga->type === 'manhwa')
                    <img src="https://flagcdn.com/w40/kr.png" alt="Manhwa" class="w-8 h-5 rounded object-cover shadow ring-1 ring-black/20" title="Manhwa (Korea)">
                @elseif($manga->type === 'manhua')
                    <img src="https://flagcdn.com/w40/cn.png" alt="Manhua" class="w-8 h-5 rounded object-cover shadow ring-1 ring-black/20" title="Manhua (China)">
                @endif
            </div>

            {{-- Rating --}}
            @if($manga->ratings_avg_rating > 0)
                <div class="absolute bottom-2 left-2 flex items-center gap-1 bg-black/70 backdrop-blur-sm text-white text-xs font-semibold px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <i class="fa-solid fa-star text-amber-400 text-[10px]"></i>
                    <span>{{ number_format($manga->ratings_avg_rating, 1) }}</span>
                </div>
            @endif
        </div>

        <div class="mt-2.5">
            <h3 class="manga-title" title="{{ $manga->title }}">{{ $manga->title }}</h3>

            {{-- Author (fallback kalau kosong) --}}
            <p class="mt-0.5 text-slate-400 dark:text-slate-500 truncate" style="font-size:11px">
                @if($manga->author)
                    <i class="fa-solid fa-pen-nib opacity-60 mr-1" style="font-size:9px"></i>{{ $manga->author }}
                @else
                    <span class="italic">Tanpa Author</span>
                @endif
            </p>

            {{-- Genre chips (max 3) --}}
            @if($manga->genres->isNotEmpty())
                <div class="mt-1.5 flex flex-wrap gap-1">
                    @foreach($manga->genres->take(3) as $genre)
                        <span style="font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:.03em;color:#ff4d66;background:rgba(255,46,77,.12);border:1px solid rgba(255,46,77,.22);padding:1px 6px;border-radius:4px">{{ $genre->name }}</span>
                    @endforeach
                </div>
            @endif

            @if($manga->latestPublishedChapter)
                <a href="{{ route('chapter.show', $manga->latestPublishedChapter->slug) }}"
                   class="mt-2 flex items-center justify-between text-xs bg-slate-100 dark:bg-white/5 hover:bg-[#ff2e4d]/10 dark:hover:bg-[#ff2e4d]/15 text-slate-600 dark:text-slate-300 hover:text-[#e62242] dark:hover:text-[#ff4d66] border border-slate-200 dark:border-white/5 rounded-lg px-2.5 py-1.5 transition-colors">
                    <span class="font-semibold flex items-center gap-1.5"><i class="fa-solid fa-book-open-reader text-[10px] opacity-60"></i>Chapter {{ $manga->latestPublishedChapter->number }}</span>
                    <span class="opacity-70">{{ $manga->latestPublishedChapter->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}</span>
                </a>
            @else
                <p class="mt-2 text-xs text-slate-400 dark:text-slate-500 italic">Belum ada chapter</p>
            @endif
        </div>
    </a>
</div>

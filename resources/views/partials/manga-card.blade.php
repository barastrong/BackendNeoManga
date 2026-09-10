@props(['manga'])
<div class="manga-card">
    <a href="{{ route('manga.show', $manga->slug) }}" class="manga-cover" title="{{ $manga->title }}">
        @if($manga->cover_image)
            <img src="{{ $manga->cover_url }}" alt="{{ $manga->title }}" loading="lazy">
        @else
            <span class="mc-ph"><img src="/images/neomanga-logo.png" alt="NeoManga" style="width:40px;height:40px;object-fit:contain;border-radius:4px"></span>
        @endif

        {{-- Badge status kiri atas --}}
        @if($manga->status === 'completed')
            <span class="mc-badge mc-badge-tamat">Tamat</span>
        @elseif($manga->status === 'ongoing')
            <span class="mc-badge mc-badge-ongoing">Ongoing</span>
        @elseif($manga->status === 'hiatus')
            <span class="mc-badge mc-badge-hiatus">Hiatus</span>
        @endif

        {{-- Flag asal kanan atas --}}
        @if($manga->type === 'manga')
            <span class="mc-flag"><img src="https://flagcdn.com/w40/jp.png" alt="Manga" title="Manga (Jepang)"></span>
        @elseif($manga->type === 'manhwa')
            <span class="mc-flag"><img src="https://flagcdn.com/w40/kr.png" alt="Manhwa" title="Manhwa (Korea)"></span>
        @elseif($manga->type === 'manhua')
            <span class="mc-flag"><img src="https://flagcdn.com/w40/cn.png" alt="Manhua" title="Manhua (China)"></span>
        @endif

        {{-- Badge COLOR utk komik berwarna --}}
        @if(in_array($manga->type, ['manhwa', 'manhua', 'webtoon']))
            <span class="mc-color"><i class="fa-solid fa-palette"></i>COLOR</span>
        @endif
    </a>

    <div class="mc-body">
        <h3 class="manga-title" title="{{ $manga->title }}">{{ $manga->title }}</h3>

        @if($manga->latestPublishedChapter)
            <a href="{{ route('chapter.show', $manga->latestPublishedChapter->slug) }}" class="mc-ch-row" title="Baca chapter terbaru">
                <span class="mc-ch-num"><img src="/images/neomanga-logo.png" alt="NeoManga" style="width:11px;height:11px;object-fit:contain;border-radius:2px;vertical-align:-2px">CH {{ $manga->latestPublishedChapter->number }}</span>
                <span class="mc-date">{{ $manga->latestPublishedChapter->created_at->diffForHumans(['short' => true, 'parts' => 1]) }}</span>
            </a>
        @else
            <p class="mc-ch-empty">Belum ada chapter</p>
        @endif
    </div>
</div>

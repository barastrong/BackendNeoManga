@extends('layouts.app')

@section('title', $chapter->manga->title . ' - Chapter ' . $chapter->number)
@section('meta_description', 'Baca ' . $chapter->manga->title . ' Chapter ' . $chapter->number . ' bahasa Indonesia di NeoManga — gratis & update terbaru.')

<link rel="stylesheet" href="{{ asset('css/chapter/show.css') }}">

@section('content')
<div class="min-h-screen">
    {{-- Progress bar baca --}}
    <div id="readingProgress" class="reading-progress"></div>

    <div class="max-w-5xl mx-auto px-2 sm:px-4 py-6">

        {{-- Header chapter --}}
        <div class="mb-5 bg-white dark:bg-[#0d1220] border border-slate-200 dark:border-white/5 rounded-2xl p-4 sm:p-5 shadow-sm">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-3 flex-wrap">
                <a href="{{ route('dashboard') }}" class="hover:text-[#ff2e4d] transition-colors"><i class="fa-solid fa-house mr-1"></i>Beranda</a>
                <i class="fa-solid fa-angle-right text-[10px]"></i>
                <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="hover:text-[#ff2e4d] transition-colors truncate max-w-[45vw]">{{ $chapter->manga->title }}</a>
                <i class="fa-solid fa-angle-right text-[10px]"></i>
                <span class="font-semibold text-slate-800 dark:text-slate-200">Chapter {{ $chapter->number }}</span>
            </nav>
            <div class="border-t border-slate-200 dark:border-white/5 pt-3 text-center">
                <h1 class="font-display text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $chapter->manga->title }}</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Chapter {{ $chapter->number }} — {{ $chapter->title ?? '' }}</p>
            </div>
        </div>

        {{-- Tombol nav atas --}}
        <div class="flex items-center justify-between gap-3 mb-4">
            <div id="chapter-dropdown-container-top" class="relative">
                <button id="chapterListBtnTop" class="reader-btn" type="button">
                    <i class="fa-solid fa-list-ul"></i><span>Daftar Chapter</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div id="chapterListTop" class="absolute top-full mt-2 left-0 w-64 bg-white dark:bg-[#131a2c] rounded-xl shadow-2xl max-h-80 overflow-y-auto z-50 hidden border border-slate-200 dark:border-white/10">
                    <div class="p-2">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400 px-3 py-1.5 uppercase tracking-wide">Pilih Chapter</div>
                        <div class="grid grid-cols-4 gap-1 max-h-64 overflow-y-auto">
                            @foreach($allChapters as $ch)
                                <a href="{{ route('chapter.show', $ch->slug) }}"
                                   class="block text-center px-1.5 py-1.5 rounded-lg text-xs font-medium transition-colors
                                          @if($ch->id === $chapter->id) bg-[#ff2e4d] text-white @else text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 @endif">
                                    {{ $ch->number }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ $prevChapter ? route('chapter.show', $prevChapter->slug) : route('manga.show', $chapter->manga->slug) }}"
                   data-prev-chapter
                   class="reader-btn {{ !$prevChapter ? 'opacity-40 pointer-events-none' : '' }}"
                   title="{{ $prevChapter ? 'Chapter Sebelumnya' : 'Kembali ke Info Manga' }}">
                    <i class="fa-solid fa-chevron-left"></i><span>Prev</span>
                </a>
                <a href="{{ $nextChapter ? route('chapter.show', $nextChapter->slug) : route('manga.show', $chapter->manga->slug) }}"
                   data-next-chapter
                   class="reader-btn {{ !$nextChapter ? 'opacity-40 pointer-events-none' : '' }}"
                   title="{{ $nextChapter ? 'Chapter Berikutnya' : 'Kembali ke Info Manga' }}">
                    <span>Next</span><i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        {{-- Gambar chapter --}}
        <div class="space-y-1.5">
            @foreach($chapter->image_urls as $index => $imageUrl)
                <div class="flex justify-center">
                    <img src="{{ $imageUrl }}"
                         alt="{{ $chapter->manga->title }} Chapter {{ $chapter->number }} - Halaman {{ $index + 1 }}"
                         class="reader-img"
                         loading="lazy"
                         decoding="async"
                         width="800"
                         height="1200">
                </div>
            @endforeach
        </div>

        {{-- Navigasi bawah sticky --}}
        <div class="reader-nav-bar -mx-2 sm:-mx-4 mt-6 px-4 py-3 flex items-center justify-between gap-3 rounded-t-2xl">
            <div class="flex items-center gap-2 min-w-0">
                <a href="{{ route('manga.show', $chapter->manga->slug) }}"
                   class="reader-btn flex-shrink-0" title="Kembali ke info manga">
                    <i class="fa-solid fa-circle-chevron-left"></i><span>Info</span>
                </a>
                <a href="{{ $prevChapter ? route('chapter.show', $prevChapter->slug) : '#' }}"
                   class="reader-btn flex-shrink-0 {{ !$prevChapter ? 'opacity-40 pointer-events-none' : '' }}"
                   title="Chapter Sebelumnya">
                    <i class="fa-solid fa-chevron-left"></i><span>Prev</span>
                </a>
            </div>

            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="#top" class="reader-btn" title="Ke atas"><i class="fa-solid fa-arrow-up"></i><span>Atas</span></a>
                <a href="{{ $nextChapter ? route('chapter.show', $nextChapter->slug) : '#' }}"
                   class="reader-btn-primary flex-shrink-0 {{ !$nextChapter ? 'opacity-40 pointer-events-none' : '' }}"
                   title="Chapter Berikutnya">
                    <span>Chapter {{ $nextChapter ? 'Berikutnya' : 'Terakhir' }}</span><i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>

        {{-- Komentar --}}
        <div id="comments-section" class="mt-8 bg-white dark:bg-[#0d1220] rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-white/5">
            <h2 class="font-display text-xl font-bold mb-6 text-slate-900 dark:text-white">
                <i class="fa-regular fa-comments text-[#ff2e4d] mr-2"></i>Komentar ({{ $totalCommentsCount }})
            </h2>
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                    @csrf
                    <input type="hidden" name="manga_id" value="{{ $chapter->manga->id }}">
                    <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                    <div class="flex items-start gap-3">
                        <img class="w-10 h-10 rounded-full object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ff2e4d&color=fff" alt="{{ auth()->user()->name }}">
                        <div class="flex-1">
                            <textarea name="content" rows="3" class="w-full p-3 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-slate-800 dark:text-slate-200 focus:ring-[#ff2e4d]/60 focus:border-[#ff2e4d] transition" placeholder="Tulis komentar..." required></textarea>
                            <div class="text-right mt-2">
                                <button type="submit" class="btn-primary !py-2"><i class="fa-solid fa-paper-plane mr-1.5 text-xs"></i>Kirim</button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="border-2 border-dashed border-slate-300 dark:border-white/10 rounded-xl p-6 text-center mb-8">
                    <h3 class="font-display font-semibold text-slate-800 dark:text-white mb-1">Gabung diskusi!</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Kamu harus login untuk berkomentar.</p>
                    <a href="{{ route('login') }}" class="btn-primary !py-2 !px-5"><i class="fa-solid fa-right-to-bracket mr-1.5 text-xs"></i>Masuk</a>
                </div>
            @endauth

            <div class="space-y-5">
                @forelse ($comments as $comment)
                    <div id="comment-{{ $comment->id }}" class="flex items-start gap-3">
                        <img class="w-9 h-9 rounded-full object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random&color=fff" alt="{{ $comment->user->name }}">
                        <div class="flex-1">
                            <div class="bg-slate-100 dark:bg-white/5 rounded-xl p-4">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-800 dark:text-white text-sm">{{ $comment->user->name }}</span>
                                        @if ($comment->user->isAdmin())
                                            <span class="bg-[#ff2e4d] text-white text-[10px] font-bold px-2 py-0.5 rounded-full"><i class="fa-solid fa-shield-halved mr-1"></i>NeoAdmin</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-slate-400 flex-shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="mt-2 flex justify-between items-end gap-3">
                                    <p class="text-slate-700 dark:text-slate-300 text-sm whitespace-pre-wrap">{{ $comment->content }}</p>
                                    @if (auth()->check() && auth()->user()->isAdmin())
                                        <form id="delete-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="flex-shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-form-id="delete-form-{{ $comment->id }}" class="delete-comment-btn text-slate-400 hover:text-red-500 transition-all hover:scale-125" title="Hapus Komentar">
                                                <i class="fa-solid fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @auth
                            <div class="flex items-center gap-4 mt-2 pl-2 text-sm">
                                <button data-comment-id="{{ $comment->id }}" class="like-btn font-medium text-slate-500 hover:text-red-500 transition">
                                    <i class="fa-heart {{ $comment->isLikedBy() ? 'fas text-red-500' : 'far' }}"></i>
                                    <span id="like-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                                </button>
                                <button data-comment-id="{{ $comment->id }}" data-username="{{ $comment->user->name }}" class="reply-btn font-medium text-slate-500 hover:text-slate-900 dark:hover:text-white transition">
                                    <i class="fa-regular fa-comment mr-1"></i>Balas
                                </button>
                            </div>
                            @endauth
                            <div id="reply-form-{{ $comment->id }}" class="mt-3 ml-4" style="display: none;">
                                <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                                    @csrf
                                    <textarea name="content" rows="2" class="w-full p-2.5 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-800 dark:text-slate-200 focus:ring-[#ff2e4d]/60 transition" placeholder="Tulis balasan..." required></textarea>
                                    <div class="flex justify-end items-center gap-3 mt-2">
                                        <button type="button" data-comment-id="{{ $comment->id }}" class="close-reply-btn text-sm font-medium text-slate-500 hover:underline">Batal</button>
                                        <button type="submit" class="btn-primary !py-1.5 !px-4 text-xs"><i class="fa-solid fa-paper-plane mr-1 text-xs"></i>Balas</button>
                                    </div>
                                </form>
                            </div>
                            <div class="mt-3 ml-6 space-y-3">
                                @foreach ($comment->replies as $reply)
                                <div id="comment-{{ $reply->id }}" class="flex items-start gap-2.5">
                                    <img class="w-7 h-7 rounded-full object-cover flex-shrink-0" src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random&color=fff" alt="{{ $reply->user->name }}">
                                    <div class="flex-1">
                                        <div class="bg-slate-100 dark:bg-white/5 rounded-xl p-3">
                                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                                <span class="font-semibold text-slate-800 dark:text-white text-xs">{{ $reply->user->name }}</span>
                                                <span class="text-[11px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-slate-700 dark:text-slate-300 text-sm mt-1.5 whitespace-pre-wrap">{{ $reply->content }}</p>
                                        </div>
                                        @if (auth()->check() && auth()->user()->isAdmin())
                                            <form id="delete-form-{{ $reply->id }}" action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="mt-1.5">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-form-id="delete-form-{{ $reply->id }}" class="delete-comment-btn text-slate-400 hover:text-red-500 text-xs transition-all hover:scale-110" title="Hapus Balasan">
                                                    <i class="fa-solid fa-trash-alt mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-sm">Belum ada komentar. Jadilah yang pertama!</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal konfirmasi hapus --}}
<div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center p-4">
    <div id="deleteModalContent" class="bg-white dark:bg-[#131a2c] rounded-xl shadow-xl max-w-sm w-full mx-4 p-6 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/50 mb-4">
            <i class="fa-solid fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
        </div>
        <h3 class="font-display font-semibold text-slate-900 dark:text-white">Hapus Komentar?</h3>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex items-center justify-center gap-3 mt-6">
            <button id="cancelDeleteBtn" class="btn-ghost !py-2">Batal</button>
            <button id="confirmDeleteBtn" class="btn-primary !py-2 bg-red-600 hover:bg-red-700 shadow-red-600/25"><i class="fa-solid fa-trash mr-1.5"></i>Ya, Hapus</button>
        </div>
    </div>
</div>

@auth
<div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#131a2c] rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-lg font-semibold text-slate-900 dark:text-white"><i class="fa-solid fa-bookmark text-[#ff2e4d] mr-2"></i>Masuk Diperlukan</h3>
            <button id="closeModal" class="text-slate-400 hover:text-slate-600 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">Login untuk menyimpan progres baca & bookmark-mu.</p>
        <div class="flex gap-3">
            <button id="closeModal2" class="btn-ghost flex-1">Batal</button>
            <a href="{{ route('login') }}" class="btn-primary flex-1"><i class="fa-solid fa-right-to-bracket mr-1.5 text-xs"></i>Masuk</a>
        </div>
    </div>
</div>
@endauth

<script src="{{ asset('js/chapter/show.js') }}"></script>

{{-- Script komentar dari JS global (like/reply/delete) — cek @stack --}}
@stack('comment-scripts')
@endpush
@endsection

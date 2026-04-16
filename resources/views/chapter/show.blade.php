@extends('layouts.app')

@section('title', $chapter->manga->title . ' - Chapter ' . $chapter->number)

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-2 sm:px-4 py-8">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-lg">
                <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Home</a>
                    <span>/</span>
                    <a href="{{ route('manga.show', $chapter->manga->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $chapter->manga->title }}</a>
                    <span>/</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">Chapter {{ $chapter->number }}</span>
                </nav>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 text-center">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $chapter->manga->title }} - Chapter {{ $chapter->number }}</h1>
                    @if($chapter->manga->alternative_title)
                        <h2 class="text-lg text-gray-600 dark:text-gray-400 mt-1">{{ $chapter->manga->alternative_title }}</h2>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 border-y border-gray-200 dark:border-gray-700 p-2 rounded-lg mb-4">
                <div class="flex items-center justify-between gap-4">
                    
                    <div id="chapter-dropdown-container-top" class="relative w-full md:w-64">
                        <button class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <span class="font-semibold truncate">{{ $chapter->number }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform transform ml-2"></i>
                        </button>
                        <div class="absolute top-full mt-2 left-0 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg max-h-80 overflow-y-auto z-50
                            hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-top border border-gray-200 dark:border-gray-700">
                            <ul class="text-sm p-1">
                                @foreach($allChapters as $ch)
                                    <li>
                                        <a href="{{ route('chapter.show', $ch->slug) }}" 
                                           class="block w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 @if($ch->id === $chapter->id) bg-indigo-500 text-white font-bold @endif">
                                            Chapter {{ $ch->number }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ $prevChapter ? route('chapter.show', $prevChapter->slug) : route('manga.show', $chapter->manga->slug) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ !$prevChapter ? 'opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700' : 'bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}"
                           title="{{ $prevChapter ? 'Previous Chapter' : 'Back to Manga Info' }}">
                            <i class="fas fa-chevron-left"></i>
                            <span class="hidden md:inline font-semibold">Prev</span>
                        </a>
                        <a href="{{ $nextChapter ? route('chapter.show', $nextChapter->slug) : route('manga.show', $chapter->manga->slug) }}" 
                           class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ !$nextChapter ? 'opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700' : 'bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}"
                           title="{{ $nextChapter ? 'Next Chapter' : 'Back to Manga Info' }}">
                            <span class="hidden md:inline font-semibold">Next</span>
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-1 mb-8 bg-black">
                @foreach($chapter->image_urls as $index => $imageUrl)
                    <div class="flex justify-center">
                        <img src="{{ $imageUrl }}" alt="Chapter {{ $chapter->number }} - Halaman {{ $index + 1 }}" class="max-w-full h-auto" loading="lazy">
                    </div>
                @endforeach
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-12">
                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-2 rounded-lg">
                    <div class="flex items-center justify-between gap-4">
                        
                        <div id="chapter-dropdown-container-bottom" class="relative w-full md:w-64">
                            <button class="w-full flex items-center justify-between px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                <span class="font-semibold truncate">{{ $chapter->number }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform transform ml-2"></i>
                            </button>
                            <div class="absolute bottom-full mb-2 left-0 w-full bg-white dark:bg-gray-800 rounded-md shadow-lg max-h-80 overflow-y-auto z-50
                                hidden opacity-0 scale-95 transition-all duration-200 ease-out origin-bottom border border-gray-200 dark:border-gray-700">
                                <ul class="text-sm p-1">
                                    @foreach($allChapters as $ch)
                                        <li>
                                            <a href="{{ route('chapter.show', $ch->slug) }}" 
                                               class="block w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 @if($ch->id === $chapter->id) bg-indigo-500 text-white font-bold @endif">
                                                Chapter {{ $ch->number }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ $prevChapter ? route('chapter.show', $prevChapter->slug) : route('manga.show', $chapter->manga->slug) }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ !$prevChapter ? 'opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700' : 'bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}"
                               title="{{ $prevChapter ? 'Previous Chapter' : 'Back to Manga Info' }}">
                                <i class="fas fa-chevron-left"></i>
                                <span class="hidden md:inline font-semibold">Prev</span>
                            </a>
                            <a href="{{ $nextChapter ? route('chapter.show', $nextChapter->slug) : route('manga.show', $chapter->manga->slug) }}" 
                               class="flex items-center gap-2 px-3 py-2 rounded-md transition-colors {{ !$nextChapter ? 'opacity-50 cursor-not-allowed bg-gray-200 dark:bg-gray-700' : 'bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600' }}"
                               title="{{ $nextChapter ? 'Next Chapter' : 'Back to Manga Info' }}">
                                <span class="hidden md:inline font-semibold">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="comments-section" class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Comments ({{ $totalCommentsCount }})</h2>

                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                        @csrf
                        <input type="hidden" name="manga_id" value="{{ $chapter->manga->id }}">
                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        <div class="flex items-start space-x-4">
                            <img class="w-10 h-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&color=fff" alt="Avatar">
                            <div class="flex-1">
                                <textarea name="content" rows="3" class="w-full p-3 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500 transition" placeholder="Add a public comment for this chapter..." required></textarea>
                                <div class="text-right mt-2">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Join the Discussion!</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">You must be logged in to post a comment.</p>
                        <a href="{{ route('login') }}">
                            <button class="mt-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                Login to Comment
                            </button>
                        </a>
                    </div>
                @endauth

                <div class="space-y-6">
                    @forelse ($comments as $comment)
                        <div id="comment-{{ $comment->id }}" class="flex items-start space-x-4">
                            <img class="w-10 h-10 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random&color=fff" alt="Avatar">
                            <div class="flex-1">
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            @if ($comment->user->isAdmin())
                                                <span class="font-semibold text-red-500 dark:text-red-400">{{ $comment->user->name }}</span>
                                                <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                                    <i class="fas fa-shield-alt fa-fw mr-1"></i>NeoAdmin
                                                </span>
                                            @else
                                                <span class="font-semibold text-gray-800 dark:text-white">{{ $comment->user->name }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-4">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="mt-2 flex justify-between items-end">
                                        <div class="flex-grow">
                                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $comment->content }}</p>
                                        </div>
                                        @if (auth()->check() && auth()->user()->isAdmin())
                                            <form id="delete-form-{{ $comment->id }}" action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="ml-4 flex-shrink-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-form-id="delete-form-{{ $comment->id }}" class="delete-comment-btn text-gray-400 dark:text-gray-500 text-sm transition-all duration-200 hover:text-red-500 dark:hover:text-red-400 hover:scale-125" title="Delete Comment">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @auth
                                <div class="flex items-center space-x-4 mt-2 pl-2 text-sm">
                                    <button data-comment-id="{{ $comment->id }}" class="like-btn font-medium text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition">
                                        <i class="fa-heart {{ $comment->isLikedBy() ? 'fas text-red-500' : 'far' }}"></i>
                                        <span id="like-count-{{ $comment->id }}">{{ $comment->likes_count }}</span>
                                    </button>
                                    <button data-comment-id="{{ $comment->id }}" data-username="{{ $comment->user->name }}" class="reply-btn font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                        Reply
                                    </button>
                                </div>
                                @endauth
                                <div id="reply-form-{{ $comment->id }}" class="mt-4 ml-4" style="display: none;">
                                    <form action="{{ route('comments.reply', $comment->id) }}" method="POST">
                                        @csrf
                                        <textarea name="content" rows="2" class="w-full p-2 bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500 transition" placeholder="Write a reply..." required></textarea>
                                        <div class="text-right mt-2"><button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded-lg">Post Reply</button></div>
                                    </form>
                                </div>
                                <div class="mt-4 ml-8 space-y-4">
                                    @foreach ($comment->replies as $reply)
                                    <div id="comment-{{ $reply->id }}" class="flex items-start space-x-3">
                                        <img class="w-8 h-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random&color=fff" alt="Avatar">
                                        <div class="flex-1">
                                            <div class="bg-gray-100 dark:bg-gray-700/50 rounded-lg p-3">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        @if ($reply->user->isAdmin())
                                                            <span class="font-semibold text-red-500 dark:text-red-400 text-sm">{{ $reply->user->name }}</span>
                                                            <span class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                                                <i class="fas fa-shield-alt fa-fw mr-1"></i>NeoAdmin
                                                            </span>
                                                        @else
                                                            <span class="font-semibold text-gray-800 dark:text-white text-sm">{{ $reply->user->name }}</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                                </div>
                                                <div class="mt-2 flex justify-between items-end text-sm">
                                                    <div class="flex-grow">
                                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->content }}</p>
                                                    </div>
                                                    @if (auth()->check() && auth()->user()->isAdmin())
                                                        <form id="delete-form-{{ $reply->id }}" action="{{ route('comments.destroy', $reply->id) }}" method="POST" class="ml-4 flex-shrink-0">
                                                            @csrf
                                                            @method('DELETE')
                                                             <button type="button" data-form-id="delete-form-{{ $reply->id }}" class="delete-comment-btn text-gray-400 dark:text-gray-500 text-sm transition-all duration-200 hover:text-red-500 dark:hover:text-red-400 hover:scale-125" title="Delete Reply">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No comments yet. Be the first one to comment!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full mx-4 transform transition-all scale-95 opacity-0" id="deleteModalContent">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Delete Comment?</h3>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this comment? This action cannot be undone.</p>
        </div>
        <div class="flex items-center justify-center gap-4 p-4 border-t border-gray-200 dark:border-gray-700">
            <button id="cancelDeleteBtn" class="flex-1 bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                Cancel
            </button>
            <button id="confirmDeleteBtn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                Yes, Delete
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function initializeChapterDropdown(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const button = container.querySelector('button');
        const dropdown = container.querySelector('div.absolute');
        if (!button || !dropdown) return;
        const icon = button.querySelector('i');
        const closeDropdown = () => {
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.add('opacity-0', 'scale-95');
                if (icon) icon.classList.remove('rotate-180');
                setTimeout(() => dropdown.classList.add('hidden'), 200);
            }
        };
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'scale-95');
                    if (icon) icon.classList.add('rotate-180');
                }, 10);
            } else {
                closeDropdown();
            }
        });
        window.addEventListener('click', function(event) {
            if (!container.contains(event.target)) {
                closeDropdown();
            }
        });
    }
    initializeChapterDropdown('chapter-dropdown-container-top');
    initializeChapterDropdown('chapter-dropdown-container-bottom');

    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const username = this.dataset.username;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            const textarea = replyForm.querySelector('textarea');
            const isHidden = replyForm.style.display === 'none';
            replyForm.style.display = isHidden ? 'block' : 'none';
            if (isHidden) {
                textarea.value = `@${username} `;
                textarea.focus();
            }
        });
    });

    @auth
    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.like-btn')) {
            e.preventDefault();
            const button = e.target.closest('.like-btn');
            const commentId = button.dataset.commentId;
            const likeCountSpan = document.getElementById(`like-count-${commentId}`);
            const likeIcon = button.querySelector('i');
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeCountSpan.textContent = data.likes_count;
                    if (data.liked) {
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas', 'text-red-500');
                    } else {
                        likeIcon.classList.remove('fas', 'text-red-500');
                        likeIcon.classList.add('far');
                    }
                }
            })
            .catch(error => console.error('Error liking comment:', error));
        }
    });
    @endauth

    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
        const deleteModalContent = document.getElementById('deleteModalContent');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        let formToSubmit = null;
        const showModal = () => {
            deleteModal.classList.remove('hidden');
            setTimeout(() => {
                deleteModalContent.classList.remove('scale-95', 'opacity-0');
                deleteModalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        };
        const hideModal = () => {
            deleteModalContent.classList.add('scale-95', 'opacity-0');
            deleteModalContent.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
            }, 200);
        };
        document.querySelectorAll('.delete-comment-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                formToSubmit = document.getElementById(this.dataset.formId);
                if (formToSubmit) {
                    showModal();
                }
            });
        });
        confirmDeleteBtn.addEventListener('click', function() {
            if (formToSubmit) {
                formToSubmit.submit();
            }
            hideModal();
        });
        cancelDeleteBtn.addEventListener('click', hideModal);
        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                hideModal();
            }
        });
    }
});
</script>
@endpush
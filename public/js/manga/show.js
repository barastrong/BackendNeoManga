document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const username = this.dataset.username;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            const textarea = replyForm.querySelector('textarea');
            const isHidden = replyForm.style.display === 'none';
            
            document.querySelectorAll('[id^="reply-form-"]').forEach(form => form.style.display = 'none');
            
            replyForm.style.display = isHidden ? 'block' : 'none';

            if (isHidden) {
                const mentionPrefix = `@${username} `;
                textarea.value = mentionPrefix;
                
                textarea.focus();
                const end = textarea.value.length;
                textarea.setSelectionRange(end, end);

                textarea.addEventListener('input', function() {
                    if (!this.value.startsWith(mentionPrefix)) {
                        this.value = mentionPrefix;
                    }
                });

                textarea.addEventListener('keydown', function(e) {
                    const isProtected = this.selectionStart <= mentionPrefix.length;
                    if (isProtected && (e.key === 'Backspace' || e.key === 'Delete')) {
                        if (this.selectionStart < mentionPrefix.length || (this.selectionStart === mentionPrefix.length && e.key === 'Backspace')) {
                            e.preventDefault();
                        }
                    }
                });
            }
        });
    });
    
    document.querySelectorAll('.close-reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = 'none';
        });
    });

    const chapterSearchInput = document.getElementById('chapterSearchInput');
    const chapterListContainer = document.getElementById('chapterListContainer');
    const chapterGrid = chapterListContainer ? chapterListContainer.querySelector('.grid') : null;
    const noChaptersFoundMessage = document.getElementById('noChaptersFound');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const sortChaptersBtn = document.getElementById('sortChaptersBtn');
    const sortIcon = document.getElementById('sortIcon');
    const sortText = document.getElementById('sortText');
    
    const chapterElements = chapterGrid ? Array.from(chapterGrid.querySelectorAll('.chapter-item')).map(el => ({
        element: el,
        number: parseFloat(el.dataset.chapterNumber)
    })) : [];

    const updateChapterList = () => {
        if (!chapterGrid) return;
        
        const searchTerm = chapterSearchInput.value.toLowerCase().trim();
        const sortOrder = sortChaptersBtn.dataset.sortOrder;

        const filteredChapters = chapterElements.filter(chapter => {
            const chapterNumText = chapter.element.querySelector('.chapter-number').textContent.toLowerCase();
            return chapterNumText.includes(searchTerm);
        });

        filteredChapters.sort((a, b) => {
            if (sortOrder === 'desc') {
                return b.number - a.number; 
            } else {
                return a.number - b.number; 
            }
        });

        chapterGrid.innerHTML = ''; 
        filteredChapters.forEach(chapter => {
            chapterGrid.appendChild(chapter.element);
        });
        
        const hasResults = filteredChapters.length > 0;
        if(chapterListContainer) chapterListContainer.classList.toggle('hidden', !hasResults);
        if(noChaptersFoundMessage) noChaptersFoundMessage.classList.toggle('hidden', hasResults);
        if(clearSearchBtn) clearSearchBtn.classList.toggle('hidden', searchTerm.length === 0);
    };
    
    if (chapterSearchInput) {
        chapterSearchInput.addEventListener('input', updateChapterList);
    }
    
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', () => {
            chapterSearchInput.value = '';
            updateChapterList();
            chapterSearchInput.focus();
        });
    }

    if (sortChaptersBtn) {
        sortChaptersBtn.addEventListener('click', () => {
            const newOrder = sortChaptersBtn.dataset.sortOrder === 'desc' ? 'asc' : 'desc';
            sortChaptersBtn.dataset.sortOrder = newOrder;

            sortIcon.className = newOrder === 'desc' ? 'fas fa-sort-down' : 'fas fa-sort-up';
            sortText.textContent = newOrder === 'desc' ? 'Newest' : 'Oldest';

            updateChapterList();
        });
    }

    @auth
    const bookmarkBtn = document.getElementById('bookmarkBtn');
    if (bookmarkBtn) {
        bookmarkBtn.addEventListener('click', function() {
            const mangaId = this.dataset.mangaId;
            this.disabled = true;
            fetch(`/bookmark/toggle/${mangaId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const bookmarkText = document.getElementById('bookmarkText');
                    const followersCount = document.getElementById('followersCount');
                    if (data.is_bookmarked) {
                        this.className = 'w-full max-w-sm mx-auto md:max-w-none mt-4 font-bold py-2 px-4 rounded-lg transition duration-200 text-white bg-green-600 hover:bg-green-700';
                        bookmarkText.textContent = 'Remove Bookmark';
                    } else {
                        this.className = 'w-full max-w-sm mx-auto md:max-w-none mt-4 font-bold py-2 px-4 rounded-lg transition duration-200 text-white bg-[#ff2e4d] hover:bg-[#e62242]';
                        bookmarkText.textContent = 'Add Bookmark';
                    }
                    followersCount.textContent = data.followers_count;
                }
            }).catch(error => console.error('Error:', error)).finally(() => { this.disabled = false; });
        });
    }

    document.body.addEventListener('click', function(e) {
        if (e.target.closest('.like-btn')) {
            e.preventDefault();
            const button = e.target.closest('.like-btn');
            const commentId = button.dataset.commentId;
            fetch(`/comments/${commentId}/like`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeCountSpan = document.getElementById(`like-count-${commentId}`);
                    const likeIcon = button.querySelector('i');
                    likeCountSpan.textContent = data.likes_count;
                    likeIcon.classList.toggle('far', !data.liked);
                    likeIcon.classList.toggle('fas', data.liked);
                    likeIcon.classList.toggle('text-red-500', data.liked);
                }
            })
            .catch(error => console.error('Error liking comment:', error));
        }
    });
    @endauth

    const deleteModal = document.getElementById('deleteConfirmModal');
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
            if (formToSubmit) showModal();
        });
    });

    confirmDeleteBtn.addEventListener('click', () => {
        if (formToSubmit) formToSubmit.submit();
        hideModal();
    });

    cancelDeleteBtn.addEventListener('click', hideModal);
    deleteModal.addEventListener('click', (e) => { if (e.target === deleteModal) hideModal(); });

    @guest
    const loginModal = document.getElementById('loginModal');
    const closeModal = document.getElementById('closeModal');
    const loginPromptTriggers = document.querySelectorAll('.js-login-prompt');

    const showLoginModal = () => {
        loginModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const hideLoginModal = () => {
        loginModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    loginPromptTriggers.forEach(trigger => trigger.addEventListener('click', showLoginModal));
    if (closeModal) closeModal.addEventListener('click', hideLoginModal);
    loginModal.addEventListener('click', (e) => { if (e.target === loginModal) hideLoginModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !loginModal.classList.contains('hidden')) hideLoginModal(); });
    @endguest
});

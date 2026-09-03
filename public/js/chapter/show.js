// Dropdown daftar chapter
document.querySelectorAll('[id^="chapterListBtn"]').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        const panel = document.getElementById('chapterListTop');
        if (panel) panel.classList.toggle('hidden');
    });
});
document.addEventListener('click', () => {
    document.getElementById('chapterListTop')?.classList.add('hidden');
});

// Scroll progress bar baca
(function () {
    const bar = document.getElementById('readingProgress');
    if (!bar) return;
    let ticking = false;
    function update() {
        const doc = document.documentElement;
        const max = doc.scrollHeight - window.innerHeight;
        const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
        bar.style.width = pct + '%';
        ticking = false;
    }
    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
})();

// Navigasi keyboard: panah kiri = prev, kanan = next
document.addEventListener('keydown', function (e) {
    const tag = (e.target.tagName || '').toLowerCase();
    if (tag === 'input' || tag === 'textarea' || tag === 'select') return;
    if (e.key === 'ArrowRight') {
        const next = document.querySelector('a[data-next-chapter]');
        if (next) window.location.href = next.href;
    } else if (e.key === 'ArrowLeft') {
        const prev = document.querySelector('a[data-prev-chapter]');
        if (prev) window.location.href = prev.href;
    }
});

// ===== Komentar: reply / like / delete =====
document.querySelectorAll('.reply-btn').forEach(button => {
    button.addEventListener('click', function () {
        const commentId = this.dataset.commentId;
        const username = this.dataset.username;
        const replyForm = document.getElementById('reply-form-' + commentId);
        if (!replyForm) return;
        const textarea = replyForm.querySelector('textarea');
        const isHidden = replyForm.style.display === 'none';
        document.querySelectorAll('[id^="reply-form-"]').forEach(f => f.style.display = 'none');
        replyForm.style.display = isHidden ? 'block' : 'none';
        if (isHidden && textarea) {
            textarea.value = '@' + username + ' ';
            textarea.focus();
        }
    });
});
document.querySelectorAll('.close-reply-btn').forEach(button => {
    button.addEventListener('click', function () {
        const replyForm = document.getElementById('reply-form-' + this.dataset.commentId);
        if (replyForm) replyForm.style.display = 'none';
    });
});
@auth
document.body.addEventListener('click', function (e) {
    const likeBtn = e.target.closest('.like-btn');
    if (!likeBtn) return;
    const commentId = likeBtn.dataset.commentId;
    const countSpan = document.getElementById('like-count-' + commentId);
    const icon = likeBtn.querySelector('i');
    fetch('/comments/' + commentId + '/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (countSpan) countSpan.textContent = data.likes_count;
            if (icon) {
                icon.classList.toggle('fas', data.liked);
                icon.classList.toggle('far', !data.liked);
                icon.classList.toggle('text-red-500', data.liked);
            }
        }
    })
    .catch(err => console.error('Like error:', err));
});
@endauth

// Modal hapus komentar
(function () {
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (!deleteModal) return;
    const content = document.getElementById('deleteModalContent');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    let formToSubmit = null;
    document.querySelectorAll('.delete-comment-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            formToSubmit = document.getElementById(this.dataset.formId);
            if (formToSubmit) {
                deleteModal.classList.remove('hidden');
                if (content) { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }
            }
        });
    });
    function hide() {
        if (content) { content.classList.add('scale-95', 'opacity-0'); content.classList.remove('scale-100', 'opacity-100'); }
        deleteModal.classList.add('hidden');
    }
    if (confirmBtn) confirmBtn.addEventListener('click', () => { if (formToSubmit) formToSubmit.submit(); hide(); });
    if (cancelBtn) cancelBtn.addEventListener('click', hide);
    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) hide(); });
})();

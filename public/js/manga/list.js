// Genre modal
(function () {
    const modal = document.getElementById('genreModal');
    const genreBtn = document.getElementById('genreBtn');
    const cancelBtn = document.getElementById('cancelGenreBtn');
    const applyBtn = document.getElementById('applyGenreBtn');
    const closeX = document.getElementById('closeGenreModalX');
    if (!modal || !genreBtn) return;

    function openModal() { modal.classList.remove('hidden'); }
    function closeModal() { modal.classList.add('hidden'); }
    genreBtn.addEventListener('click', openModal);
    cancelBtn && cancelBtn.addEventListener('click', closeModal);
    closeX && closeX.addEventListener('click', closeModal);
    applyBtn && applyBtn.addEventListener('click', () => { document.getElementById('filterForm').submit(); });

    modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
})();

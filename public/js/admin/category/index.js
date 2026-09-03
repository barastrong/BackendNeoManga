function openModal(mode, id = null, name = '') {
        document.getElementById('cat-modal').classList.add('open');
        document.getElementById('cat-modal-title').textContent = mode === 'edit' ? 'Edit Kategori' : 'Tambah Kategori';
        const input = document.getElementById('cat-name');
        input.value = name || '';
        const form = document.getElementById('cat-form');
        const putWrap = document.getElementById('cat-method-put');
        if (mode === 'edit') {
            form.action = '/admin/categories/' + id;
            putWrap.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        } else {
            form.action = form.dataset.defaultAction;
            putWrap.innerHTML = '';
        }
        setTimeout(() => input.focus(), 50);
    }
    function closeModal() {
        document.getElementById('cat-modal').classList.remove('open');
    }
    document.getElementById('cat-modal').addEventListener('click', function (e) {
        if (e.target === this) closeModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

// Init dari session flash (server inject ke hidden fields)
(function () {
    const id = document.getElementById('cat-edit-id');
    const name = document.getElementById('cat-edit-name');
    if (id && id.value) {
        openModal('edit', parseInt(id.value, 10), name ? name.value : '');
    }
})();

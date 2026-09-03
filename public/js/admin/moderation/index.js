// Tab switching
    document.querySelectorAll('.mx-tab').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.mx-tab').forEach(b => { b.classList.remove('active'); });
            this.classList.add('active');
            document.querySelectorAll('.mx-panel').forEach(p => p.classList.remove('active'));
            const panel = document.getElementById(this.dataset.panel);
            if (panel) panel.classList.add('active');
        });
    });

    // Filter antrean laporan
    function filterQueue(q) {
        const term = q.toLowerCase();
        let visible = 0;
        document.querySelectorAll('#queue-list .queue-item').forEach(el => {
            const show = !term || el.dataset.search.includes(term);
            el.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        const cnt = document.getElementById('queue-count');
        if (cnt) cnt.textContent = visible + ' ditampilkan';
    }

    // Filter tabel generik
    function filterTable(q, tableId) {
        const term = q.toLowerCase();
        document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
            row.style.display = (!term || (row.dataset.search || '').includes(term)) ? '' : 'none';
        });
    }

    // Toast session

// Toast dari flash session (data-msg diisi server)
(function () {
    const t = document.getElementById('mx-toast');
    const msg = document.getElementById('mx-toast-msg');
    if (t && msg && msg.dataset.msg) {
        msg.textContent = msg.dataset.msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3200);
    }
})();

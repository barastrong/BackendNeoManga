// ===== Theme system NeoManga (dark-first) =====
// 1) Restore class <html> sebelum/awal render (anti flash).
// 2) Toggle saat #themeToggle diklik → simpan di localStorage 'nm-theme'.
(function () {
    const root = document.documentElement;
    const KEY = 'nm-theme';

    function apply(theme) {
        root.classList.toggle('dark', theme === 'dark');
    }

    // Restore (dark-first: belum pernah pilih / bukan 'light' → DARK)
    try {
        apply(localStorage.getItem(KEY) !== 'light' ? 'dark' : 'light');
    } catch (e) { apply('dark'); }

    // Toggle
    const toggle = document.getElementById('themeToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            const next = root.classList.contains('dark') ? 'light' : 'dark';
            apply(next);
            try { localStorage.setItem(KEY, next); } catch (e) {}
        });
    }
})();
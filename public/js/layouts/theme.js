// ===== Theme system NeoManga (dark-first) =====
// 1) Restore class <html> sebelum/awal render (anti flash).
// 2) Toggle saat #themeToggle diklik → simpan di localStorage 'nm-theme'.
// 3) Icon sun/moon dikelola JS langsung (CSS utility dark:block gak tersedia di app.css).
(function () {
    const root = document.documentElement;
    const KEY = 'nm-theme';
    const sun = document.getElementById('themeIconSun');
    const moon = document.getElementById('themeIconMoon');

    function apply(theme) {
        root.classList.toggle('dark', theme === 'dark');
        if (sun) sun.style.display = theme === 'dark' ? 'block' : 'none';
        if (moon) moon.style.display = theme === 'dark' ? 'none' : 'block';
    }

    // Restore (dark-first: belum pernah pilih / bukan 'light' → DARK)
    let initial;
    try { initial = localStorage.getItem(KEY) !== 'light' ? 'dark' : 'light'; } catch (e) { initial = 'dark'; }
    apply(initial);

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
// ===== Register NeoManga: eye toggle + password strength meter =====
function togglePass(inputId, iconId) {
    const p = document.getElementById(inputId);
    const i = document.getElementById(iconId);
    if (p.type === 'password') { p.type = 'text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash'); }
    else { p.type = 'password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye'); }
}

(function () {
    const pw = document.getElementById('password');
    const bars = document.querySelectorAll('.meter-bar');
    const hint = document.getElementById('pwHint');
    if (!pw || !bars.length) return;

    function calc(s) {
        let score = 0;
        if (s.length >= 8) score++;
        if (/[A-Z]/.test(s)) score++;
        if (/[0-9]/.test(s)) score++;
        if (/[^A-Za-z0-9]/.test(s)) score++;
        return score;
    }
    const labels = ['Terlalu pendek', 'Lemah', 'Cukup', 'Bagus', 'Kuat'];
    const colors = ['', '#f87171', '#fbbf24', '#38bdf8', '#34d399'];

    pw.addEventListener('input', function () {
        const v = pw.value;
        const score = v ? calc(v) : 0;
        bars.forEach((b, idx) => {
            b.style.background = idx < score ? colors[score] : 'rgba(255,255,255,.08)';
        });
        hint.textContent = v ? 'Kekuatan password: ' + labels[score] : 'Gunakan huruf, angka & simbol minimal 8 karakter';
        hint.style.color = v ? colors[score] : '';
    });
})();

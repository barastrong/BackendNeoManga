// Eye toggle untuk field password di halaman konfirmasi
function togglePass(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
}

// Validasi form konfirmasi password
(function () {
    const form = document.querySelector('form[action*="confirm-password"]');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const password = document.getElementById('password');
        const passwordError = document.getElementById('passwordError');
        if (!password || !passwordError) return;

        passwordError.classList.add('hidden');
        if (!password.value) {
            passwordError.textContent = 'Password wajib diisi.';
            passwordError.classList.remove('hidden');
            e.preventDefault();
        }
    });
})();

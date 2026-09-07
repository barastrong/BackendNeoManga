// Eye toggle untuk field password di halaman reset password
function togglePass(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;
    const show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    icon.className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
}

// Validasi form reset password
(function () {
    const form = document.querySelector('form[action*="reset-password"]');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const conf = document.getElementById('password_confirmation');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const confError = document.getElementById('passwordConfirmationError');
        let hasError = false;

        [emailError, passwordError, confError].forEach(el => el && el.classList.add('hidden'));

        const emailVal = email.value.trim();
        if (!emailVal || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal)) {
            emailError.textContent = 'Masukkan email yang terdaftar.';
            emailError.classList.remove('hidden');
            hasError = true;
        }

        if (password.value.length < 8) {
            passwordError.textContent = 'Password minimal 8 karakter.';
            passwordError.classList.remove('hidden');
            hasError = true;
        }

        if (conf.value !== password.value) {
            confError.textContent = 'Konfirmasi password tidak cocok.';
            confError.classList.remove('hidden');
            hasError = true;
        }

        if (hasError) e.preventDefault();
    });
})();

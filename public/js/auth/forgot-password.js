// Validasi form lupa password — pesan error ringan, error server tetap tampil via Blade
(function () {
    const form = document.getElementById('forgotForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const email = document.getElementById('email');
        const emailError = document.getElementById('emailError');
        if (!email || !emailError) return;

        emailError.classList.add('hidden');
        const val = email.value.trim();

        if (!val) {
            emailError.textContent = 'Alamat email wajib diisi.';
            emailError.classList.remove('hidden');
            e.preventDefault();
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            emailError.textContent = 'Harap masukkan alamat email yang valid.';
            emailError.classList.remove('hidden');
            e.preventDefault();
        }
    });
})();

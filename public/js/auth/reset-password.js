// Membuat partikel
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            const particleCount = 50;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        document.addEventListener('DOMContentLoaded', createParticles);
        
        // Validasi Form
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                // Ambil semua value input
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const passwordConfirmation = document.getElementById('password_confirmation').value;
                let hasError = false;

                // Ambil semua elemen error
                const emailError = document.getElementById('emailError');
                const passwordError = document.getElementById('passwordError');
                const passwordConfirmationError = document.getElementById('passwordConfirmationError');

                // Reset semua pesan error
                [emailError, passwordError, passwordConfirmationError].forEach(el => el.classList.add('hidden'));

                // Validasi Email
                if (!email) {
                    emailError.textContent = 'Alamat email wajib diisi.';
                    emailError.classList.remove('hidden');
                    hasError = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailError.textContent = 'Harap masukkan alamat email yang valid.';
                    emailError.classList.remove('hidden');
                    hasError = true;
                }

                // Validasi Password
                if (!password) {
                    passwordError.textContent = 'Password baru wajib diisi.';
                    passwordError.classList.remove('hidden');
                    hasError = true;
                } else if (password.length < 8) {
                    passwordError.textContent = 'Password minimal harus 8 karakter.';
                    passwordError.classList.remove('hidden');
                    hasError = true;
                }

                // Validasi Konfirmasi Password
                if (!passwordConfirmation) {
                    passwordConfirmationError.textContent = 'Konfirmasi password wajib diisi.';
                    passwordConfirmationError.classList.remove('hidden');
                    hasError = true;
                } else if (password && password !== passwordConfirmation) {
                    passwordConfirmationError.textContent = 'Konfirmasi password tidak cocok dengan password baru.';
                    passwordConfirmationError.classList.remove('hidden');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault(); // Mencegah form dikirim jika ada error
                }
            });
        }

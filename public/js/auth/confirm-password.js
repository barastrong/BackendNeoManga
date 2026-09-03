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
                const password = document.getElementById('password').value;
                const passwordError = document.getElementById('passwordError');
                let hasError = false;

                // Reset error
                passwordError.classList.add('hidden');

                // Validasi Password
                if (!password) {
                    passwordError.textContent = 'Password wajib diisi.';
                    passwordError.classList.remove('hidden');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault(); // Mencegah form dikirim jika ada error
                }
            });
        }

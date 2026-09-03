// Script tetap sama
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
        
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email').value;
                const emailError = document.getElementById('emailError');
                let hasError = false;
                
                emailError.classList.add('hidden');
                
                if (!email) {
                    emailError.textContent = 'Alamat email wajib diisi.';
                    emailError.classList.remove('hidden');
                    hasError = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailError.textContent = 'Harap masukkan alamat email yang valid.';
                    emailError.classList.remove('hidden');
                    hasError = true;
                }
                
                if (hasError) {
                    e.preventDefault();
                }
            });
        }

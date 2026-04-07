<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="version" content="1.0">
    <title>Reset Password - Neon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap');
        
        body {
            font-family: 'Orbitron', monospace;
        }
        
        /* DIUBAH: Membuat class khusus untuk judul agar lebih terbaca */
        .neon-title-text {
            color: #fff; /* Memberi warna putih solid pada teks inti */
            /* Memberi glow putih tipis dan glow cyan yang lebih menyebar */
            text-shadow: 
                0 0 5px #fff,
                0 0 10px #fff,
                0 0 20px #00ffff,
                0 0 35px #00ffff;
        }

        /* Efek neon standar untuk teks lain yang lebih kecil */
        .neon-text {
            text-shadow: 0 0 10px #00ffff, 0 0 20px #00ffff, 0 0 30px #00ffff, 0 0 40px #00ffff;
        }
        
        .neon-border {
            box-shadow: 0 0 20px #00ffff, inset 0 0 20px rgba(0, 255, 255, 0.1);
        }
        
        .neon-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 20px #00ffff, inset 0 0 20px rgba(0, 255, 255, 0.1); }
            to { box-shadow: 0 0 30px #00ffff, 0 0 40px #00ffff, inset 0 0 30px rgba(0, 255, 255, 0.2); }
        }
        
        .neon-button {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .neon-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }
        
        .neon-button:hover::before {
            left: 100%;
        }
        
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: #00ffff;
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
            box-shadow: 0 0 10px #00ffff;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 1; }
            50% { transform: translateY(-100px) rotate(180deg); opacity: 0.5; }
        }
        
        .glass-morphism {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black flex items-center justify-center p-4">
    
    <!-- Floating Particles -->
    <div class="floating-particles" id="particles"></div>
    
    <!-- Main Container -->
    <div class="relative z-10 w-full">
        <!-- Form Container -->
        <div class="bg-black bg-opacity-50 glass-morphism rounded-2xl p-6 sm:p-8 w-full max-w-md mx-auto neon-border neon-glow">
            
            <!-- Header -->
            <div class="text-center mb-6">
                <!-- DIUBAH: Mengganti class `neon-text` dan `text-cyan-400` dengan `neon-title-text` yang baru -->
                <h1 class="text-3xl sm:text-4xl font-bold neon-title-text mb-2">RESET PASSWORD</h1>
                <div class="w-16 h-1 bg-gradient-to-r from-cyan-400 to-purple-500 mx-auto rounded-full"></div>
            </div>

            <!-- Intro Text -->
            <p class="mb-6 text-sm text-center text-gray-300">
                Lupa password? Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan mengirimkan link untuk mengatur ulang password baru.
            </p>
            
            <!-- Session Status (Pesan Sukses) -->
            <div id="sessionStatus" class="hidden mb-4 p-3 rounded-lg bg-green-900 bg-opacity-50 border border-green-400 text-green-300 text-sm"></div>
            
            <!-- Form -->
            <form method="POST" action="/forgot-password" class="space-y-6">\
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <!-- Menggunakan class .neon-text biasa untuk label yang lebih kecil -->
                    <label for="email" class="block text-sm font-medium text-purple-300 neon-text">
                        Alamat Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        required 
                        autofocus
                        class="w-full px-4 py-3 bg-gray-900 bg-opacity-50 border border-cyan-400 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-300"
                        placeholder="Masukkan email Anda"
                    />
                    <div class="text-red-400 text-sm mt-1 hidden" id="emailError"></div>
                </div>
                
                <!-- Action Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full neon-button bg-gradient-to-r from-cyan-500 to-purple-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-cyan-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-gray-800"
                    >
                        KIRIM LINK RESET PASSWORD
                    </button>
                </div>
                
                <!-- Back to Login Link -->
                <div class="text-center pt-4 border-t border-gray-700">
                    <p class="text-gray-400 text-sm">
                        Kembali ke halaman login? 
                        <a href="/login" class="text-cyan-400 hover:text-cyan-300 transition-colors duration-300 underline">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
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
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="version" content="1.0">
    <title>Reset Password - Neon</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
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
    
    <script src="{{ asset('js/auth/forgot-password.js') }}"></script>
</body>
</html>
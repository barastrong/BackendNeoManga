<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="version" content="1.0">
    <title>Set New Password - Neon</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="{{ asset('css/auth/reset-password.css') }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-black flex items-center justify-center p-4">
    
    <!-- Floating Particles -->
    <div class="floating-particles" id="particles"></div>
    
    <!-- Main Container -->
    <div class="relative z-10 w-full">
        <!-- Form Container -->
        <div class="bg-black bg-opacity-50 glass-morphism rounded-2xl p-6 sm:p-8 w-full max-w-md mx-auto neon-border neon-glow">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold neon-title-text mb-2">SET NEW PASSWORD</h1>
                <div class="w-16 h-1 bg-gradient-to-r from-cyan-400 to-purple-500 mx-auto rounded-full"></div>
            </div>
            
            <!-- Form -->
            <form method="POST" action="/reset-password" class="space-y-6">
                @csrf
                <!-- 
                    Di aplikasi nyata (seperti Laravel), token ini akan diisi secara dinamis.
                    Nilainya diambil dari URL yang diklik pengguna di email mereka.
                -->
                <input type="hidden" name="token" value="placeholder_token_from_url">
                
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-purple-300 neon-text">
                        Alamat Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        required 
                        autofocus
                        autocomplete="username"
                        class="w-full px-4 py-3 bg-gray-900 bg-opacity-50 border border-cyan-400 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-300"
                        placeholder="Email Anda yang terdaftar"
                    />
                    <div class="text-red-400 text-sm mt-1 hidden" id="emailError"></div>
                </div>
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-purple-300 neon-text">
                        Password Baru
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-4 py-3 bg-gray-900 bg-opacity-50 border border-cyan-400 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-300"
                        placeholder="Masukkan password baru Anda"
                    />
                    <div class="text-red-400 text-sm mt-1 hidden" id="passwordError"></div>
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-purple-300 neon-text">
                        Konfirmasi Password Baru
                    </label>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        class="w-full px-4 py-3 bg-gray-900 bg-opacity-50 border border-cyan-400 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-300"
                        placeholder="Ulangi password baru Anda"
                    />
                    <div class="text-red-400 text-sm mt-1 hidden" id="passwordConfirmationError"></div>
                </div>
                
                <!-- Action Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full neon-button bg-gradient-to-r from-cyan-500 to-purple-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-cyan-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-gray-800"
                    >
                        RESET PASSWORD
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="{{ asset('js/auth/reset-password.js') }}"></script>
</body>
</html>
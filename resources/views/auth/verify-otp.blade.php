<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - NeoManga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Particle background styles */
    .particle-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      pointer-events: none;
    }
    #particleCanvas {
      width: 100%;
      height: 100%;
      display: block;
    }
  </style>
</head>
<body class="bg-gray-100">
<div class="particle-background">
  <canvas id="particleCanvas"></canvas>
</div>

    <!-- Main Content -->
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-2xl sm:rounded-lg sm:px-10 border border-gray-200">
                
                <!-- Header -->
                <div class="text-center space-y-4">
                    <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-envelope-open-text text-2xl text-orange-600"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900">Verifikasi Email</h2>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi email Anda dengan memasukkan kode OTP yang telah kami kirim ke alamat email Anda.
                    </p>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- OTP Form -->
                <form method="POST" action="{{ route('verify.otp') }}" class="mt-8 space-y-6">
                    @csrf
                    
                    <!-- OTP Input -->
                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode OTP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   id="otp"
                                   name="otp" 
                                   class="appearance-none block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm bg-gray-50 text-center text-lg font-mono tracking-widest"
                                   placeholder="000000"
                                   maxlength="6"
                                   required 
                                   autofocus
                                   autocomplete="off">
                        </div>
                        @error('otp')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Verify Button -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            <i class="fas fa-shield-check mr-2"></i>
                            VERIFIKASI
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative mt-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Tidak menerima kode?</span>
                    </div>
                </div>

                <!-- Resend Button -->
                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('resend.otp') }}">
                        @csrf
                        <button type="submit" 
                                id="resendButton"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span id="resendText">Tunggu 30 detik</span>
                        </button>
                    </form>
                </div>

                <!-- Expiry Notice -->
                <div class="mt-6 text-center">
                    <div class="inline-flex items-center px-3 py-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        <span class="text-sm text-yellow-800 font-medium">
                            Kode OTP akan kadaluarsa dalam 5 menit
                        </span>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Pastikan untuk memeriksa folder spam/sampah jika tidak menemukan email kami
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Particles Script -->
     <script src="/js/particles.js"></script>
    <script>
        // OTP specific functionality
        let cooldown = 30;
        let timer = null;
        const resendButton = document.getElementById('resendButton');
        const resendText = document.getElementById('resendText');

        function startCooldown() {
            cooldown = 30;
            resendButton.disabled = true;
            resendButton.classList.add('opacity-50', 'cursor-not-allowed');
            resendButton.classList.remove('hover:bg-gray-50');

            timer = setInterval(() => {
                cooldown--;
                resendText.innerHTML = `<i class="fas fa-clock mr-2"></i>Tunggu ${cooldown} detik`;

                if (cooldown <= 0) {
                    clearInterval(timer);
                    resendButton.disabled = false;
                    resendButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    resendButton.classList.add('hover:bg-gray-50');
                    resendText.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim ulang kode OTP';
                }
            }, 1000);
        }

        // Start cooldown when page loads
        startCooldown();

        // Handle form submit for resend
        resendButton.closest('form').addEventListener('submit', function(e) {
            if (cooldown > 0) {
                e.preventDefault();
                return;
            }
            startCooldown();
        });

        // Only allow numeric input and auto-format
        const otpInput = document.querySelector('input[name="otp"]');
        otpInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Auto-submit when 6 digits are entered
            if (this.value.length === 6) {
                // Add a small delay for better UX
                setTimeout(() => {
                    this.closest('form').submit();
                }, 300);
            }
        });

        // Add paste support for OTP
        otpInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste.replace(/[^0-9]/g, '').substring(0, 6);
            this.value = cleanPaste;
            
            if (cleanPaste.length === 6) {
                setTimeout(() => {
                    this.closest('form').submit();
                }, 300);
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="version" content="2.0">
    <title>Sign In | NeonManga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .font-orbitron {
            font-family: 'Orbitron', monospace;
        }
        .neon-glow-text {
            color: #fff;
            text-shadow: 
                0 0 7px #fff,
                0 0 10px #fff,
                0 0 21px #fff,
                0 0 42px #0fa,
                0 0 82px #0fa,
                0 0 92px #0fa,
                0 0 102px #0fa,
                0 0 151px #0fa;
        }
        .glass-morphism {
            background: rgba(12, 5, 24, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .animated-gradient-border {
            position: relative;
            border-radius: 1.25rem;
            padding: 2px;
            overflow: hidden;
            z-index: 1;
        }
        .animated-gradient-border::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                transparent,
                rgba(16, 185, 129, 0.7),
                rgba(139, 92, 246, 0.7),
                transparent 30%
            );
            animation: rotate 4s linear infinite;
            z-index: -1;
        }
        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }
        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        .particle {
            position: absolute;
            background: #10b981;
            border-radius: 50%;
            animation: float 10s ease-in-out infinite;
            box-shadow: 0 0 10px #10b981, 0 0 20px #10b981;
            opacity: 0;
        }
        @keyframes float {
            0% {
                transform: translateY(10vh) translateX(0);
                opacity: 0;
            }
            10%, 90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-110vh) translateX(20px);
                opacity: 0;
            }
        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #111827 inset !important;
            -webkit-text-fill-color: #fff !important;
            caret-color: #fff;
        }
    </style>
</head>
<body class="min-h-screen bg-[#0C0518] bg-cover bg-center bg-no-repeat flex items-center justify-center p-4" style="background-image: linear-gradient(rgba(12, 5, 24, 0.8), rgba(12, 5, 24, 1)), url('https://source.unsplash.com/1600x900/?cyberpunk,city');">
    
    <div class="floating-particles" id="particles"></div>
    
    <div class="animated-gradient-border">
        <div class="bg-[#0C0518] rounded-xl w-full max-w-md mx-auto p-6 sm:p-8 glass-morphism">
            
            <div class="text-center mb-8">
                <h1 class="text-4xl sm:text-5xl font-bold font-orbitron neon-glow-text mb-2">NEONMANGA</h1>
                <p class="text-purple-300 text-sm">Sign in to access your library</p>
            </div>
            
            <form method="POST" action="{{route('login')}}" class="space-y-6">
                @csrf
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                            <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                        </svg>
                    </div>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        required 
                        autofocus 
                        autocomplete="email"
                        class="w-full pl-10 pr-4 py-3 bg-gray-900 bg-opacity-70 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300"
                        placeholder="your@email.com"
                    />
                </div>
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full pl-10 pr-10 py-3 bg-gray-900 bg-opacity-70 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-300"
                        placeholder="Password"
                    />
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-400">
                        <svg id="eye-open" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                            <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.18l.88-1.472A9.002 9.002 0 0110 4c2.75 0 5.261 1.253 6.457 3.938l.88 1.472a1.65 1.65 0 010 1.18l-.88 1.472A9.002 9.002 0 0110 16c-2.75 0-5.261-1.253-6.457-3.938l-.88-1.472zM10 14a4 4 0 100-8 4 4 0 000 8z" clip-rule="evenodd" />
                        </svg>
                        <svg id="eye-closed" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a9.003 9.003 0 003.959-5.074.75.75 0 00-.016-.42l-.88-1.472A9.002 9.002 0 0010 4c-1.214 0-2.36.29-3.395.79L4.328 3.28A.75.75 0 003.28 2.22zM7.5 12.036V9h1.54l2.5 2.5a4 4 0 01-3.96 1.036.75.75 0 00-.11-.53z" clip-rule="evenodd" />
                            <path d="M10 14a4 4 0 01-4-4 .75.75 0 00-1.5 0 5.5 5.5 0 005.5 5.5.75.75 0 000-1.5z" />
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-emerald-500 bg-gray-800 border-gray-600 rounded focus:ring-emerald-500 focus:ring-2 accent-emerald-500"/>
                        <span class="ml-2 text-sm text-gray-300">Remember me</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-purple-400 hover:text-purple-300 transition-colors duration-300 underline">
                        Forgot password?
                    </a>
                </div>
                
                <div>
                    <button 
                        type="submit" 
                        class="w-full font-semibold py-3 px-6 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900
                        bg-gradient-to-r from-emerald-500 to-purple-500 text-white
                        hover:from-emerald-600 hover:to-purple-600 transform hover:scale-105"
                    >
                        SIGN IN
                    </button>
                </div>
                
                <div class="text-center pt-4 border-t border-gray-800">
                    <p class="text-gray-400 text-sm">
                        Don't have an account? 
                        <a href="/register" class="font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-300 underline">
                            Sign Up Now
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const particlesContainer = document.getElementById('particles');
            if (particlesContainer) {
                const particleCount = 40;
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    const size = Math.random() * 4 + 1;
                    particle.style.width = `${size}px`;
                    particle.style.height = `${size}px`;
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 10 + 's';
                    particle.style.animationDuration = Math.random() * 5 + 8 + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function () {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
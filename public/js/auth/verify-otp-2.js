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

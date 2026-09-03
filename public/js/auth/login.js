function togglePass() {
            const p = document.getElementById('password');
            const i = document.getElementById('eyeIcon');
            if (p.type === 'password') { p.type = 'text'; i.classList.remove('fa-eye'); i.classList.add('fa-eye-slash'); }
            else { p.type = 'password'; i.classList.remove('fa-eye-slash'); i.classList.add('fa-eye'); }
        }

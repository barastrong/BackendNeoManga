// Theme toggle
        (function () {
            const root = document.documentElement;
            const toggle = document.getElementById('themeToggle');
            function setTheme(theme) {
                root.classList.toggle('dark', theme === 'dark');
                localStorage.setItem('nm-theme', theme);
            }
            if (toggle) {
                toggle.addEventListener('click', () => {
                    setTheme(root.classList.contains('dark') ? 'light' : 'dark');
                });
            }
        })();

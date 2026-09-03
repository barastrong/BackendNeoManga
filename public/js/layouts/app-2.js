// Dark mode default: ON (dark-first, khas situs baca manga), toggle di header
        if (localStorage.getItem('nm-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }

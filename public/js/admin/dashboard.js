function setChartFilter(btn) {
            const group = document.getElementById('time-filter-group');
            group.querySelectorAll('.chart-filter').forEach(b => {
                b.className = "chart-filter px-3 py-1 rounded-lg text-xs font-semibold transition-all text-slate-400 hover:text-white";
            });
            btn.className = "chart-filter px-3 py-1 rounded-lg text-xs font-semibold transition-all bg-brand text-white shadow";
            document.querySelectorAll('.chart-panel').forEach(p => {
                p.style.display = p.dataset.daysPanel === btn.dataset.days ? 'block' : 'none';
            });
        }

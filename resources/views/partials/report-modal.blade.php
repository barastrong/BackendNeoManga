{{-- Modal Report Komentar — dipakai manga/show & chapter/show --}}
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-60 z-[60] hidden flex items-center justify-center p-4">
    <div class="bg-white dark:bg-[#0d1220] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-white/5">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                <span class="text-red-500"><i class="fas fa-flag"></i></span> Laporkan Komentar
            </h3>
            <button type="button" data-report-close class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors text-xl leading-none">&times;</button>
        </div>
        <form id="reportForm" method="POST" class="p-5 space-y-4">
            @csrf
            <p class="text-sm text-slate-600 dark:text-slate-300">Alasan kamu melaporkan komentar ini akan membantu admin menindaklanjuti. Komentar yang melanggar akan dihapus & pengguna bisa di-ban.</p>
            <div class="space-y-2.5" id="reportReasons">
                @foreach ([
                    'spam'       => ['fa-bullhorn', 'Spam / promosi'],
                    'pelecehan'  => ['fa-user-slash', 'Pelecehan / bullying'],
                    'spoiler'    => ['fa-eye-slash', 'Spoiler'],
                    'offensive'  => ['fa-skull-crossbones', 'Konten ofensif / SARA'],
                    'lainnya'    => ['fa-ellipsis', 'Lainnya'],
                ] as $value => [$icon, $label])
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-white/10 cursor-pointer transition hover:border-red-400 dark:hover:border-red-500/50 has-[:checked]:border-red-500 has-[:checked]:bg-red-500/5">
                        <input type="radio" name="reason" value="{{ $value }}" class="accent-red-500" {{ $loop->first ? 'checked' : '' }}>
                        <i class="fas {{ $icon }} text-red-400 w-5 text-center"></i>
                        <span class="text-sm text-slate-700 dark:text-slate-200">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-xl transition duration-200">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    var modal = document.getElementById('reportModal');
    if (!modal) return;
    var form = document.getElementById('reportForm');
    var closeBtn = modal.querySelector('[data-report-close]');

    function open(btn) {
        var cid = btn.getAttribute('data-comment-id');
        var author = btn.getAttribute('data-author') || '';
        form.setAttribute('action', '/comments/' + cid + '/report');
        var h = modal.querySelector('h3');
        if (h && author) { /* tambah info author */ }
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function close() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    // Delegasi: tombol report dinamis (komentar utama + balasan, halaman mana pun)
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-report-btn]');
        if (btn) { e.preventDefault(); open(btn); return; }
        if (e.target.closest('[data-report-close]') || e.target === modal) close();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
})();
</script>

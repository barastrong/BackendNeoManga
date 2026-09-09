<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class ScraperBatchCommand extends Command
{
    protected $signature = 'scraper:batch
        {--scripts= : Daftar script dipisah koma, default 4 scraper kiryuu}
        {--dry-run : Hanya tampilkan yang akan dijalankan}';

    protected $description = 'Jalankan scraper Kiryuu satu-per-satu (serial, anti tabrakan) sampai semua halaman selesai';

    /** Skrip utama: file sama, beda orderby → jalan berurutan biar history JSON gak rebutan. */
    private array $defaultScripts = ['kiryuu_scraper.py', 'update.py', 'title.py', 'rating.py'];

    public function handle(): int
    {
        $dir = config('scraper.dir');
        $python = config('scraper.python');

        if (! is_dir($dir)) {
            $this->error("Folder scraper tidak ada: {$dir}");

            return 1;
        }
        if (! is_file($python)) {
            $this->error("Python venv tidak ada: {$python}");

            return 1;
        }

        $scripts = $this->option('scripts')
            ? explode(',', $this->option('scripts'))
            : $this->defaultScripts;

        $this->info('Scraper batch dimulai — ' . count($scripts) . ' script berurutan');
        $this->line("Folder : {$dir}");
        $this->line("Python : {$python}");

        // Kunci: cegah 2 batch jalan bersamaan (tabrakan history JSON)
        $lock = storage_path('logs/scraper_batch.lock');
        if (file_exists($lock)) {
            $this->error('Batch sedang berjalan (lock ada). Tunggu selesai atau hapus: ' . $lock);

            return 1;
        }
        file_put_contents($lock, date('c'));

        if ($this->option('dry-run')) {
            foreach ($scripts as $s) {
                $this->line('  akan jalankan: ' . $s);
            }
            @unlink($lock);

            return 0;
        }

        $failed = [];
        $startAll = microtime(true);

        try {
            foreach ($scripts as $i => $script) {
                $path = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR . $script;
                if (! is_file($path)) {
                    $this->warn("  [{$i}] SKIP — file tidak ada: {$script}");
                    $failed[] = $script;

                    continue;
                }

                $this->info("  [{$i}] ▶ {$script} — mulai " . date('H:i:s'));
                $t0 = microtime(true);

                // Serial: tunggu script selesai (semua halaman / auto-stop) baru lanjut
                $proc = new Process([$python, $path], $dir, [
                    'PYTHONIOENCODING' => 'utf-8',   // fix: emoji 🤖 di print() gagal di cp1252
                    'PYTHONUTF8' => '1',
                ]);
                $proc->setTimeout(0);      // tanpa batas waktu
                $proc->setIdleTimeout(0);

                // stream output ke console
                $proc->run(function ($type, $buf) use ($script) {
                    fwrite(STDOUT, $buf);
                });

                $dur = gmdate('i:s', (int) (microtime(true) - $t0));
                if ($proc->isSuccessful()) {
                    $this->info("  ✓ {$script} selesai ({$dur} menit) — exit {$proc->getExitCode()}");
                } else {
                    $this->error("  ✗ {$script} GAGAL ({$dur}) — exit {$proc->getExitCode()}");
                    $failed[] = $script;
                }
            }
        } finally {
            @unlink($lock);
        }

        $total = gmdate('i:s', (int) (microtime(true) - $startAll));
        if ($failed) {
            $this->error("SELESAI dengan gagal ({$total}): " . implode(', ', $failed));

            return 1;
        }
        $this->info("✅ SEMUA SELESAI dalam {$total} — 4 scraper jalan serial tanpa tabrakan.");

        return 0;
    }
}

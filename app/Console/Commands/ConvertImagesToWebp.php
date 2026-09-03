<?php

namespace App\Console\Commands;

use App\Models\Chapter;
use App\Models\Manga;
use App\Services\CloudinaryStorageService;
use Illuminate\Console\Command;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp {--type=all : manga, chapter, atau all}';
    protected $description = 'Konversi semua gambar JPG/PNG di Cloudinary ke WebP';
    private array $errors = [];

    public function __construct(private CloudinaryStorageService $storage)
    {
        parent::__construct();
    }

    private string $errorPath = '';

    public function handle(): void
    {
        $type = $this->option('type');
        $this->errors = [];
        $this->errorPath = storage_path('logs/convert_errors.json');

        // Reset file error
        file_put_contents($this->errorPath, json_encode([], JSON_PRETTY_PRINT));

        if (in_array($type, ['all', 'manga'])) {
            $this->convertMangas();
        }

        if (in_array($type, ['all', 'chapter'])) {
            $this->convertChapters();
        }

        $this->info('Selesai! Total error: ' . count($this->errors));
        if (!empty($this->errors)) {
            $this->warn('Cek error di: ' . $this->errorPath);
        }
    }

    private function logError(array $data): void
    {
        $this->errors[] = $data;
        file_put_contents(
            $this->errorPath,
            json_encode($this->errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function convertMangas(): void
    {
        $mangas = Manga::whereNotNull('cover_image')
            ->where('cover_image', 'not like', '%.webp')
            ->get();

        $this->info("Konversi {$mangas->count()} cover manga...");
        $bar = $this->output->createProgressBar($mangas->count());

        foreach ($mangas as $manga) {
            try {
                $oldUrl  = $manga->cover_image;
                $filename = \Illuminate\Support\Str::slug($manga->title) . '.webp';

                $newUrl = $this->storage->uploadFromUrl('manga-covers', $filename, $oldUrl);
                $this->storage->deleteCover($oldUrl);

                $manga->update(['cover_image' => $newUrl]);
            } catch (\Exception $e) {
                $this->newLine();
                $this->warn("Gagal [{$manga->title}]: " . $e->getMessage());
                $this->logError([
                    'type'  => 'manga_cover',
                    'manga' => $manga->title,
                    'url'   => $manga->cover_image,
                    'error' => $e->getMessage(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }

    private function convertChapters(): void
    {
        $chapters = Chapter::whereNotNull('chapter_images')->get();
        $total    = $chapters->count();

        $this->info("Konversi images dari {$total} chapter...");
        $bar = $this->output->createProgressBar($total);

        foreach ($chapters as $chapter) {
            $images    = $chapter->chapter_images;
            $hasOldImg = collect($images)->contains(fn($url) => !str_ends_with($url, '.webp'));

            if (!$hasOldImg) {
                $bar->advance();
                continue;
            }

            $newImages = [];
            $manga     = $chapter->manga;

            foreach ($images as $index => $oldUrl) {
                if (str_ends_with($oldUrl, '.webp')) {
                    $newImages[] = $oldUrl;
                    continue;
                }

                try {
                    $folder   = "{$manga->slug}/chapter-{$chapter->number}";
                    $filename = "{$folder}/" . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '.webp';

                    $newUrl      = $this->storage->uploadFromUrl('chapters', $filename, $oldUrl);
                    $newImages[] = $newUrl;

                    $this->storage->deleteFiles('chapters', [$oldUrl]);
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("Gagal [{$manga->title}] chapter {$chapter->number} img {$index}: " . $e->getMessage());
                    $this->warn("  URL: {$oldUrl}");
                    $this->logError([
                        'type'    => 'chapter_image',
                        'manga'   => $manga->title ?? $manga->slug ?? 'unknown',
                        'chapter' => $chapter->number,
                        'index'   => $index,
                        'url'     => $oldUrl,
                        'error'   => $e->getMessage(),
                    ]);
                    $newImages[] = $oldUrl;
                }
            }

            $chapter->update(['chapter_images' => $newImages]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
    }
}

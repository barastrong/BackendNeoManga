<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SupabaseStorageService
{
    private string $url;
    private string $key;

    public function __construct()
    {
        $this->url = rtrim(env('SUPABASE_URL'), '/');
        $this->key = env('SUPABASE_KEY');
    }

    private function convertToWebp(string $binaryContent): string
    {
        $img = imagecreatefromstring($binaryContent);
        if (!$img) throw new \Exception('Gagal membaca gambar');
        ob_start();
        imagewebp($img, null, 85);
        $webp = ob_get_clean();
        imagedestroy($img);
        return $webp;
    }

    private function pushToSupabase(string $bucket, string $path, string $content): string
    {
        $response = Http::withHeaders([
            'apikey'        => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type'  => 'image/webp',
            'x-upsert'      => 'true',
        ])->withBody($content, 'image/webp')
          ->post("{$this->url}/storage/v1/object/{$bucket}/{$path}");

        if (!$response->successful()) {
            throw new \Exception("Upload gagal: " . $response->body());
        }

        return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    private function webpPath(string $path): string
    {
        return preg_replace('/\.[^.]+$/', '.webp', $path);
    }

    public function uploadCover(UploadedFile $file, string $mangaTitle): string
    {
        $path    = Str::slug($mangaTitle) . '.webp';
        $content = $this->convertToWebp(file_get_contents($file->getRealPath()));
        return $this->pushToSupabase('manga-covers', $path, $content);
    }

    public function uploadChapterImages(array $files, string $mangaSlug, int|string $chapterNumber): array
    {
        $folder = "{$mangaSlug}/chapter-{$chapterNumber}";
        $urls   = [];

        foreach ($files as $index => $file) {
            $path    = "{$folder}/" . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '.webp';
            $content = $this->convertToWebp(file_get_contents($file->getRealPath()));
            $urls[]  = $this->pushToSupabase('chapters', $path, $content);
        }

        return $urls;
    }

    public function uploadFromUrl(string $bucket, string $path, string $url): string
    {
        $response = Http::timeout(60)->withHeaders([
            'User-Agent' => 'Mozilla/5.0',
        ])->get($url);

        if (!$response->successful()) {
            throw new \Exception("Gagal download ({$response->status()}): {$url}");
        }

        $body = $response->body();
        if (empty($body)) {
            throw new \Exception("Response kosong: {$url}");
        }

        $content = $this->convertToWebp($body);
        $webpPath = $this->webpPath($path);

        return $this->pushToSupabase($bucket, $webpPath, $content);
    }

    public function deleteCover(string $url): void
    {
        $path = $this->pathFromUrl($url, 'manga-covers');
        if ($path) $this->delete('manga-covers', [$path]);
    }

    public function deleteFiles(string $bucket, array $paths): void
    {
        $this->delete($bucket, $paths);
    }

    private function delete(string $bucket, array $paths): void
    {
        Http::withHeaders([
            'apikey'        => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
        ])->delete("{$this->url}/storage/v1/object/{$bucket}", [
            'prefixes' => $paths,
        ]);
    }

    private function pathFromUrl(string $url, string $bucket): ?string
    {
        $base = "{$this->url}/storage/v1/object/public/{$bucket}/";
        return str_starts_with($url, $base) ? substr($url, strlen($base)) : null;
    }
}

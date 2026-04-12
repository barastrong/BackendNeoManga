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

    private function upload(string $bucket, string $path, UploadedFile $file): string
    {
        $response = Http::withHeaders([
            'apikey'        => $this->key,
            'Authorization' => 'Bearer ' . $this->key,
            'Content-Type'  => $file->getMimeType(),
        ])->withBody(
            file_get_contents($file->getRealPath()),
            $file->getMimeType()
        )->post("{$this->url}/storage/v1/object/{$bucket}/{$path}");

        if (!$response->successful()) {
            throw new \Exception("Supabase upload failed: " . $response->body());
        }

        return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    public function uploadCover(UploadedFile $file, string $mangaTitle): string
    {
        $filename = Str::slug($mangaTitle) . '.' . $file->getClientOriginalExtension();
        return $this->upload('manga-covers', $filename, $file);
    }

    public function uploadChapterImages(array $files, string $mangaSlug, int|string $chapterNumber): array
    {
        $folder = "{$mangaSlug}/chapter-{$chapterNumber}";
        $urls   = [];

        foreach ($files as $index => $file) {
            $ext      = $file->getClientOriginalExtension();
            $filename = "{$folder}/" . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . ".{$ext}";
            $urls[]   = $this->upload('chapters', $filename, $file);
        }

        return $urls;
    }

    public function deleteCover(string $url): void
    {
        $path = $this->pathFromUrl($url, 'manga-covers');
        if ($path) $this->delete('manga-covers', [$path]);
    }

    public function deleteChapterFolder(string $mangaSlug, int|string $chapterNumber): void
    {
        // Supabase delete by prefix tidak tersedia, jadi simpan list path dari chapter_images
        // Dipanggil dari luar dengan path list jika diperlukan
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

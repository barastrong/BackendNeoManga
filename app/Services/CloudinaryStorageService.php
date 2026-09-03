<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CloudinaryStorageService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->cloudName = env('CLOUDINARY_CLOUD_NAME', '');
        $this->apiKey    = env('CLOUDINARY_API_KEY', '');
        $this->apiSecret = env('CLOUDINARY_API_SECRET', '');
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

    private function uploadRaw(string $folder, string $publicId, string $content, string $mime = 'image/webp'): string
    {
        $response = Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->asForm()
            ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload", [
                'file'              => 'data:' . $mime . ';base64,' . base64_encode($content),
                'folder'            => $folder,
                'public_id'         => $publicId,
                'overwrite'         => 'true',
                'unique_filename'   => 'false',
            ]);

        if (!$response->successful()) {
            throw new \Exception('Upload Cloudinary gagal: ' . $response->body());
        }
        return $response->json('secure_url');
    }

    private function destroy(string $publicId): void
    {
        Http::withBasicAuth($this->apiKey, $this->apiSecret)
            ->asForm()
            ->post("https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy", [
                'public_id'     => $publicId,
                'resource_type' => 'image',
            ]);
    }

    private function publicIdFromUrl(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        $prefix = "/{$this->cloudName}/image/upload/";
        if (!$path || !str_starts_with($path, $prefix)) return null;
        $id = substr($path, strlen($prefix));
        $id = preg_replace('#^v\d+/#', '', $id);
        return preg_replace('/\.[^.]+$/', '', $id);
    }

    public function uploadCover(UploadedFile $file, string $mangaTitle): string
    {
        $content = $this->convertToWebp(file_get_contents($file->getRealPath()));
        return $this->uploadRaw('neomanga/manga-covers', Str::slug($mangaTitle), $content);
    }

    public function uploadChapterImages(array $files, string $mangaSlug, int|string $chapterNumber): array
    {
        $urls = [];
        foreach ($files as $index => $file) {
            $content = $this->convertToWebp(file_get_contents($file->getRealPath()));
            $urls[]  = $this->uploadRaw(
                "neomanga/chapters/{$mangaSlug}/chapter-{$chapterNumber}",
                str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                $content
            );
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
        $dir     = dirname($path);
        $folder  = $dir === '.' ? "neomanga/{$bucket}" : "neomanga/{$bucket}/{$dir}";
        $publicId = preg_replace('/\.[^.]+$/', '', basename($path));

        return $this->uploadRaw($folder, $publicId, $content);
    }

    public function deleteCover(string $url): void
    {
        if ($id = $this->publicIdFromUrl($url)) $this->destroy($id);
    }

    public function deleteFiles(string $bucket, array $paths): void
    {
        foreach ($paths as $path) {
            $id = str_starts_with($path, 'http')
                ? $this->publicIdFromUrl($path)
                : "neomanga/{$bucket}/" . preg_replace('/\.[^.]+$/', '', $path);
            if ($id) $this->destroy($id);
        }
    }
}
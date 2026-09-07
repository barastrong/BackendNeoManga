<?php

namespace App\Http\Controllers\Site;

use App\Models\Manga;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Sitemap XML dinamis — URL dari DB, cache 1 jam.
     */
    public function index(Request $request): \Illuminate\Http\Response
    {
        $base = rtrim(config('app.seo_base_url') ?: config('app.url'), '/');

        $urls = collect([
            ['loc' => $base . '/', 'priority' => '1.0'],
            ['loc' => "$base/manga", 'priority' => '0.8'],
            ['loc' => "$base/search", 'priority' => '0.5'],
        ]);

        // Semua manga (URL utama: /content/{slug})
        $mangas = cache()->remember('sitemap_mangas', 3600, fn () =>
            Manga::select('slug', 'updated_at')->get()
        );
        foreach ($mangas as $m) {
            $urls->push([
                'loc'      => "$base/content/{$m->slug}",
                'priority' => '0.9',
                'lastmod'  => $m->updated_at?->toIso8601String(),
            ]);
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
             . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($u['loc']) . "</loc>\n";
            if (!empty($u['lastmod'])) {
                $xml .= "    <lastmod>{$u['lastmod']}</lastmod>\n";
            }
            $xml .= "    <priority>{$u['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * robots.txt — izinkan crawler, blokir area privat, tunjuk sitemap.
     */
    public function robots(): \Illuminate\Http\Response
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /verify-otp',
            'Disallow: /profile',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
        ];
        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    /**
     * llms.txt — ringkasan situs untuk AI/LLM.
     */
    public function llms(): \Illuminate\Http\Response
    {
        $b = rtrim(url('/'), '/');
        $lines = [
            '# NeoManga',
            '',
            '> Web reader manga & komik modern (Laravel 12, dark-first).',
            '',
            '## Konten Utama',
            '',
            "- Beranda: {$b}/",
            "- Semua Manga: {$b}/manga",
            "- Pencarian: {$b}/search",
            '',
            '## Teknis',
            '',
            "- Sitemap: {$b}/sitemap.xml",
            '- Repo: https://github.com/barastrong/BackendNeoManga',
            '- Aplikasi butuh runtime PHP + MySQL (Laravel), bukan statis.',
        ];
        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }
}
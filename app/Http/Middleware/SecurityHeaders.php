<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware keamanan dasar: header proteksi + cegah clickjacking/sniffing.
 * Dipasang global via bootstrap/app.php.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Cegah web di-embed ke situs lain (clickjacking). Sama-origin = frame sendiri tetap boleh.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // Cegah browser menebak MIME (mis. file upload jadi HTML yang jalan).
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Cegah XSS klasik di browser lama.
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        // Batasi info yang bocor via header Referer.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // Cache-Control: halaman HTML jangan di-cache (data user bocor via tombol Back).
        if (! $response->headers->has('Cache-Control')) {
            $response->headers->set('Cache-Control', 'no-store, private');
        }
        // HSTS — cuma aktif kalau HTTPS beneran (di belakang ngrok sekalipun).
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}

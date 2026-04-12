<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleCors
{
    private array $allowedOrigins = [
        'http://localhost:5173',
        'https://neomanga.vercel.app',
    ];

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->headers->get('Origin');
        $allowOrigin = in_array($origin, $this->allowedOrigins) ? $origin : '';

        if ($request->isMethod('OPTIONS')) {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $allowOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        if ($allowOrigin) {
            $response->headers->set('Access-Control-Allow-Origin', $allowOrigin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
        }

        return $response;
    }
}

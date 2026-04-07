<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class ApiKeyAuth {
    public function handle(Request $request, Closure $next) {
        if ($request->header('X-API-KEY') && $request->header('X-API-KEY') === config('app.api_key')) {
            return $next($request);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // OTP verify: 5 percobaan / 5 menit per user (keyed by user id, bukan IP
        // — ganti IP pun tidak bypass. 5 menit = masa berlaku kode OTP).
        RateLimiter::for('otp', function (Request $request) {
            return Limit::perMinutes(5, 5)
                ->by('otp:'.($request->user()?->id ?? $request->ip()));
        });

        // Resend OTP: 3x / 10 menit — anti spam email.
        RateLimiter::for('otp-resend', function (Request $request) {
            return Limit::perMinutes(10, 3)
                ->by('otp-resend:'.($request->user()?->id ?? $request->ip()));
        });
    }
}

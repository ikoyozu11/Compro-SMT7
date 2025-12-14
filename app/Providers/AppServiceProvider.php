<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        // Ensure MySQL session timezone is Asia/Jakarta (+07:00) for consistent DATE() and CURDATE() behavior
        try {
            DB::statement("SET time_zone = '+07:00'");
        } catch (\Throwable $e) {
            // Ignore if connection not ready in certain contexts (e.g., during config:cache)
        }
    }
}

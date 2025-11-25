<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthServiceInterface; // Impor Interface
use App\Services\AuthService;          // Impor Implementasi

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Beri tahu Laravel bahwa interface AuthServiceInterface harus di-resolve menjadi AuthService
        $this->app->bind(
            AuthServiceInterface::class, // Interface
            AuthService::class           // Implementasi
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

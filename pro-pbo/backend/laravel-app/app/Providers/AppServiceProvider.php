<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthServiceInterface; // Impor Interface
use App\Services\AuthService;          // Impor Implementasi
use App\Services\JobServiceInterface;
use App\Services\JobService;
use App\Services\ApplicationServiceInterface;
use App\Services\ApplicationService;
use App\Services\ProfileServiceInterface;
use App\Services\ProfileService;
use App\Services\DocumentServiceInterface;
use App\Services\DocumentService;
use App\Services\StudentProfileService;
use App\Services\CompanyProfileService;
use App\Repositories\JobRepositoryInterface;
use App\Repositories\JobRepository;
use App\Repositories\ApplicationRepositoryInterface;
use App\Repositories\ApplicationRepository;
use App\Repositories\DocumentRepositoryInterface;
use App\Repositories\DocumentRepository;

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

        // Bind JobService
        $this->app->bind(
            JobServiceInterface::class,
            JobService::class
        );

        // Bind ApplicationService
        $this->app->bind(
            ApplicationServiceInterface::class,
            ApplicationService::class
        );

        // Bind ProfileService
        $this->app->bind(
            ProfileServiceInterface::class,
            ProfileService::class
        );

        // Bind DocumentService
        $this->app->bind(
            DocumentServiceInterface::class,
            DocumentService::class
        );

        // Bind profile services
        $this->app->bind(
            StudentProfileService::class,
            StudentProfileService::class
        );

        $this->app->bind(
            CompanyProfileService::class,
            CompanyProfileService::class
        );

        // Bind repositories
        $this->app->bind(
            JobRepositoryInterface::class,
            JobRepository::class
        );

        $this->app->bind(
            ApplicationRepositoryInterface::class,
            ApplicationRepository::class
        );

        $this->app->bind(
            DocumentRepositoryInterface::class,
            DocumentRepository::class
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

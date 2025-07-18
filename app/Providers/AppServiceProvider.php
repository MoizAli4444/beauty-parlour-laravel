<?php

namespace App\Providers;

use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\ServiceVariantRepositoryInterface;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceVariantRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // $this->app->bind(
        //     \App\Interfaces\BookingRepositoryInterface::class,
        //     \App\Repositories\BookingRepository::class
        // );
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ServiceVariantRepositoryInterface::class, ServiceVariantRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Interfaces\ServiceRepositoryInterface;
use App\Repositories\ServiceRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

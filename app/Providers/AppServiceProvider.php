<?php

namespace App\Providers;


use App\Interfaces\AddonRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\OfferRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\ServiceVariantRepositoryInterface;
use App\Interfaces\StaffRepositoryInterface;
use App\Repositories\AddonRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OfferRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceVariantRepository;
use App\Repositories\StaffRepository;
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
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(AddonRepositoryInterface::class, AddonRepository::class);

        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

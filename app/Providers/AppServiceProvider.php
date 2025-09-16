<?php

namespace App\Providers;


use App\Interfaces\AddonRepositoryInterface;
use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\OfferRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\ServiceVariantRepositoryInterface;
use App\Interfaces\StaffRepositoryInterface;
use App\Repositories\AddonRepository;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\Blog\BlogRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\BookingReview\BookingReviewRepository;
use App\Repositories\BookingReview\BookingReviewRepositoryInterface;
use App\Repositories\ContactMessage\ContactMessageRepository;
use App\Repositories\ContactMessage\ContactMessageRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\Deal\DealRepository;
use App\Repositories\Deal\DealRepositoryInterface;
use App\Repositories\Faq\FaqRepository;
use App\Repositories\Faq\FaqRepositoryInterface;
use App\Repositories\Gallery\GalleryRepository;
use App\Repositories\Gallery\GalleryRepositoryInterface;
use App\Repositories\OfferRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceVariantRepository;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use App\Repositories\StaffRepository;
use App\Repositories\Testimonial\TestimonialRepository;
use App\Repositories\Testimonial\TestimonialRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ServiceVariantRepositoryInterface::class, ServiceVariantRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(AddonRepositoryInterface::class, AddonRepository::class);

        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);

        $this->app->bind(BookingReviewRepositoryInterface::class, BookingReviewRepository::class);

        $this->app->bind(GalleryRepositoryInterface::class, GalleryRepository::class);

        $this->app->bind(DealRepositoryInterface::class, DealRepository::class);

        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);

        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
        
        $this->app->bind(TestimonialRepositoryInterface::class, TestimonialRepository::class);

        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

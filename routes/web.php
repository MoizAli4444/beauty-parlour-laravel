<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceVariantController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/php-limits', function () {
    return [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/dashboard', function () {
    return view('admin.dashboard.dashboard');
})->name('dashboard');

Route::middleware(['role:admin'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});


Route::middleware('auth')->group(function () {


    // ==========================
    // Services Module Routes
    // ==========================



    // Custom Service Routes
    Route::prefix('services')->name('services.')->group(function () {
        // For DataTables AJAX loading
        Route::get('/datatable', [ServiceController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [ServiceController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [ServiceController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('services', ServiceController::class);

    // ==============================
    // Service Variants Module Routes
    // ==============================


    // Custom Routes
    Route::prefix('service-variants')->name('service-variants.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [ServiceVariantController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [ServiceVariantController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [ServiceVariantController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [ServiceVariantController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('service-variants', ServiceVariantController::class);

    // ==============================
    // Staff Module Routes
    // ==============================

    // Custom Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [StaffController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [StaffController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [StaffController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('staff', StaffController::class);

    // ==============================
    // Customers Module Routes
    // ==============================

    // Custom Routes
    Route::prefix('customers')->name('customers.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [CustomerController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [CustomerController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [CustomerController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('customers', CustomerController::class);


    /// ==============================
    // Offer Module Routes
    // ==============================

    Route::prefix('offers')->name('offers.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [OfferController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [OfferController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [OfferController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [OfferController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('offers', OfferController::class);



    // ==============================
    // Addons Module Routes
    // ==============================

    Route::prefix('addons')->name('addons.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [AddonController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [AddonController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [AddonController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [AddonController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('addons', AddonController::class);


    /// ==============================
    // Booking Module Routes
    /// ==============================

    Route::prefix('bookings')->name('bookings.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [BookingController::class, 'datatable'])->name('datatable');
        Route::patch('/{id}/status/{status}', [BookingController::class, 'changeStatus'])->name('bookings.changeStatus');
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');


        // For toggling status
        Route::patch('{id}/toggle-status', [BookingController::class, 'toggleStatus'])->name('toggle-status');

        Route::post('/{id}/status', [BookingController::class, 'changeStatus'])->name('changeStatus');


        // Bulk actions
        // Route::post('bulk-delete', [BookingController::class, 'bulkDelete'])->name('bulkDelete');
        // Route::post('bulk-status', [BookingController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('bookings', BookingController::class);



    // ==============================
    // Booking Reviews Module Routes
    // ==============================
    Route::prefix('booking-reviews')->name('booking-reviews.')->group(function () {

        // ******* API Code *******
        // Customer creates review
        // Route::middleware(['auth:api', 'role:customer'])->group(function () {
        //     Route::post('/bookings/{booking}/reviews', [BookingReviewController::class, 'store'])->name('reviews.store');
        // });

        // For DataTables AJAX loading
        Route::get('datatable', [BookingReviewController::class, 'datatable'])->name('datatable');

        // Show single review
        Route::get('{review}', [BookingReviewController::class, 'show'])->name('show');

        // Update status
        Route::patch('{review}/status', [BookingReviewController::class, 'updateStatus'])->name('updateStatus');


        Route::post('/{id}/status', [BookingReviewController::class, 'changeStatus'])->name('changeStatus');


        // For toggling status
        Route::patch('{id}/toggle-status', [BookingReviewController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        // Route::post('bulk-delete', [BookingReviewController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [BookingReviewController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('booking-reviews', BookingReviewController::class);


    // ==============================
    // Gallery Module Routes
    // ==============================

    Route::prefix('galleries')->name('galleries.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [GalleryController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('toggle-status');
        // For toggling featured
        Route::patch('/{id}/toggle-featured', [GalleryController::class, 'toggleFeatured'])->name('toggleFeatured');


        // Bulk actions
        Route::post('bulk-delete', [GalleryController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [GalleryController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('galleries', GalleryController::class);



    // ==============================
    // Deal Module Routes
    // ==============================
    Route::prefix('deals')->name('deals.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [DealController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [DealController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [DealController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [DealController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('deals', DealController::class);



    // ==============================
    // FAQ Module Routes
    // ==============================
    Route::prefix('faqs')->name('faqs.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [FaqController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [FaqController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [FaqController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [FaqController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('faqs', FaqController::class);


    // ==============================
    // Contact Messages Module Routes
    // ==============================
    Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [ContactMessageController::class, 'datatable'])->name('datatable');

        // For toggling status (pending/resolved/closed)
        Route::patch('{id}/toggle-status', [ContactMessageController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [ContactMessageController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [ContactMessageController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('contact-messages', ContactMessageController::class)->except(['create', 'store']);


    // ==============================
    // Testimonials Module Routes
    // ==============================
    Route::prefix('testimonials')->name('testimonials.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [TestimonialController::class, 'datatable'])->name('datatable');

        // For toggling status (pending/active/inactive)
        Route::patch('{id}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [TestimonialController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [TestimonialController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('testimonials', TestimonialController::class);






    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

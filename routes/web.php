<?php

use App\Http\Controllers\AddonController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceVariantController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
    // ==============================

    Route::prefix('bookings')->name('bookings.')->group(function () {
        // For DataTables AJAX loading
        Route::get('datatable', [BookingController::class, 'datatable'])->name('datatable');

        // For toggling status
        Route::patch('{id}/toggle-status', [BookingController::class, 'toggleStatus'])->name('toggle-status');

        // Bulk actions
        Route::post('bulk-delete', [BookingController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [BookingController::class, 'bulkStatus'])->name('bulkStatus');
    });

    // Resource Routes
    Route::resource('bookings', BookingController::class);






    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

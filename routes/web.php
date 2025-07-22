<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceVariantController;
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


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

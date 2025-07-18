<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
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

Route::middleware('auth')->group(function () {


    // Resource Routes (outside group for simplicity)
    Route::resource('services', ServiceController::class);

    Route::prefix('services')->name('services.')->group(function () {
        // Custom Routes
        Route::get('datatable', [ServiceController::class, 'datatable'])->name('datatable');
        Route::patch('{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('bulk-delete', [ServiceController::class, 'bulkDelete'])->name('bulkDelete');
        Route::post('bulk-status', [ServiceController::class, 'bulkStatus'])->name('bulkStatus');
    });






    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

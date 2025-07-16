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


Route::prefix('services')->name('services.')->group(function () {
    Route::get('/datatable', [ServiceController::class, 'datatable'])->name('datatable');

    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/create', [ServiceController::class, 'create'])->name('create');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
    Route::get('{slug}/edit', [ServiceController::class, 'edit'])->name('edit');

    Route::post('/store', [ServiceController::class, 'store'])->name('store');

    Route::put('/{id}', [ServiceController::class, 'update'])->name('update');


    Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('destroy');

    Route::patch('/{id}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('toggle-status');

});




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

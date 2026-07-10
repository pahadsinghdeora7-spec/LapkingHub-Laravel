<?php

use App\Http\Controllers\Admin\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.index');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::resource('brands', BrandController::class);
    Route::patch('brands/{brand}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('brands/{brand}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');
});

require __DIR__.'/auth.php';

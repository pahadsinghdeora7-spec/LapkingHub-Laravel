<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\LaptopModelController;
use App\Http\Controllers\Admin\SeriesController;
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
    Route::resource('categories', CategoryController::class)->withTrashed(['show']);
    Route::patch('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

    Route::resource('brands', BrandController::class);
    Route::patch('brands/{brand}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('brands/{brand}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');

    Route::resource('manufacturers', ManufacturerController::class);
    Route::resource('series', SeriesController::class)->withTrashed(['show']);
    Route::resource('laptop-models', LaptopModelController::class)->parameters(['laptop-models' => 'laptopModel'])->withTrashed(['show']);
    Route::patch('manufacturers/{manufacturer}/restore', [ManufacturerController::class, 'restore'])->name('manufacturers.restore');
    Route::delete('manufacturers/{manufacturer}/force-delete', [ManufacturerController::class, 'forceDelete'])->name('manufacturers.force-delete');
    Route::patch('series/{series}/restore', [SeriesController::class, 'restore'])->name('series.restore');
    Route::delete('series/{series}/force-delete', [SeriesController::class, 'forceDelete'])->name('series.force-delete');
    Route::patch('laptop-models/{laptopModel}/restore', [LaptopModelController::class, 'restore'])->name('laptop-models.restore');
    Route::delete('laptop-models/{laptopModel}/force-delete', [LaptopModelController::class, 'forceDelete'])->name('laptop-models.force-delete');
});

require __DIR__.'/auth.php';

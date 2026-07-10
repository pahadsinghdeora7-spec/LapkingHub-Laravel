<?php

use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompatibilityController;
use App\Http\Controllers\Admin\ManufacturerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\LaptopModelController;
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\ProductCompatibilityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products/{product}/compatible-laptop-models', [ProductCompatibilityController::class, 'show'])->name('products.compatible-laptop-models');


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
    Route::get('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::post('products/bulk', [ProductController::class, 'bulk'])->name('products.bulk');
    Route::resource('products', ProductController::class)->withTrashed(['show']);
    Route::get('products/{product}/images', [ProductImageController::class, 'index'])->name('products.images.index');
    Route::post('products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
    Route::put('products/{product}/images/{image}', [ProductImageController::class, 'update'])->name('products.images.update');
    Route::delete('products/{product}/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::patch('products/{product}/images/{image}/restore', [ProductImageController::class, 'restore'])->name('products.images.restore');
    Route::delete('products/{product}/images/{image}/force-delete', [ProductImageController::class, 'forceDelete'])->name('products.images.force-delete');
    Route::patch('products/{product}/images/{image}/primary', [ProductImageController::class, 'setPrimary'])->name('products.images.primary');
    Route::patch('products/{product}/images/sort', [ProductImageController::class, 'sort'])->name('products.images.sort');
    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    Route::resource('categories', CategoryController::class)->withTrashed(['show']);
    Route::patch('categories/{category}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{category}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');

    Route::resource('brands', BrandController::class);
    Route::patch('brands/{brand}/restore', [BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('brands/{brand}/force-delete', [BrandController::class, 'forceDelete'])->name('brands.force-delete');

    Route::get('products/{product}/compatibilities', [CompatibilityController::class, 'index'])->name('products.compatibilities.index');
    Route::post('products/{product}/compatibilities/bulk-assign', [CompatibilityController::class, 'bulkAssign'])->name('products.compatibilities.bulk-assign');
    Route::delete('products/{product}/compatibilities/bulk-remove', [CompatibilityController::class, 'bulkRemove'])->name('products.compatibilities.bulk-remove');

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

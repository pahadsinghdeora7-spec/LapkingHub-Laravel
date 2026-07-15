<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $featuredProducts = Product::query()
            ->with(['brand', 'category', 'primaryImage'])
            ->where('status', Product::STATUS_ACTIVE)
            ->where('is_active', true)
            ->latest('created_at')
            ->take(8)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(8)
            ->get();

        $brands = Brand::query()
            ->where('status', Brand::STATUS_ACTIVE)
            ->orderBy('name')
            ->take(10)
            ->get();

        return view('storefront.home', compact('featuredProducts', 'categories', 'brands'));
    }

    public function products(Request $request): View
    {
        $products = Product::query()
            ->with(['brand', 'category', 'primaryImage'])
            ->where('status', Product::STATUS_ACTIVE)
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('oem_part_number', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->string('category')))
            ->when($request->filled('brand'), fn ($query) => $query->where('brand_id', $request->string('brand')))
            ->orderByDesc('is_featured')
            ->latest('created_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        $brands = Brand::query()->where('status', Brand::STATUS_ACTIVE)->orderBy('name')->get(['id', 'name']);

        return view('storefront.products.index', compact('products', 'categories', 'brands'));
    }

    public function product(Product $product): View
    {
        abort_unless($product->status === Product::STATUS_ACTIVE && $product->is_active, 404);

        $product->load(['brand', 'category', 'images', 'primaryImage', 'laptopModels']);

        return view('storefront.products.show', compact('product'));
    }
}

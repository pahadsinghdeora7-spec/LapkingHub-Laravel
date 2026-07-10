<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductImageRequest;
use App\Http\Requests\Admin\UpdateProductImageRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductImageController extends Controller
{
    public function __construct(private readonly ProductImageService $images, private readonly ProductService $products) {}

    public function index(Request $request, Product $product): View
    {
        $this->authorize('viewAny', ProductImage::class);
        $product = $this->products->findForAdmin($product->id);
        return view('admin.product-images.index', ['product'=>$product, 'images'=>$this->images->listImages($product, $request->only(['search', 'trashed', 'per_page'])), 'filters'=>$request->all()]);
    }

    public function store(StoreProductImageRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('create', ProductImage::class);
        $this->images->upload($product, $request->validated(), $request->user()->id);
        return back()->with('success', 'Product image(s) uploaded successfully.');
    }

    public function update(UpdateProductImageRequest $request, Product $product, ProductImage $image): RedirectResponse
    {
        $this->authorize('update', $image); $this->ensureImageBelongsToProduct($product, $image);
        $this->images->update($image, $request->validated(), $request->user()->id);
        return back()->with('success', 'Product image updated successfully.');
    }

    public function destroy(Request $request, Product $product, ProductImage $image): RedirectResponse
    {
        $this->authorize('delete', $image); $this->ensureImageBelongsToProduct($product, $image);
        $this->images->delete($image, $request->user()->id);
        return back()->with('success', 'Product image moved to trash.');
    }

    public function restore(Request $request, Product $product, string $image): RedirectResponse
    {
        $image = ProductImage::withTrashed()->findOrFail($image); $this->authorize('restore', $image); $this->ensureImageBelongsToProduct($product, $image);
        $this->images->restore($image, $request->user()->id);
        return back()->with('success', 'Product image restored successfully.');
    }

    public function forceDelete(Request $request, Product $product, string $image): RedirectResponse
    {
        $image = ProductImage::withTrashed()->findOrFail($image); $this->authorize('forceDelete', $image); $this->ensureImageBelongsToProduct($product, $image);
        $this->images->forceDelete($image, $request->user()->id);
        return back()->with('success', 'Product image permanently deleted.');
    }

    public function setPrimary(Request $request, Product $product, ProductImage $image): RedirectResponse
    {
        $this->authorize('update', $image); $this->ensureImageBelongsToProduct($product, $image);
        $this->images->setPrimary($image, $request->user()->id);
        return back()->with('success', 'Primary image updated successfully.');
    }

    public function sort(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', ProductImage::class);
        $data = $request->validate(['image_ids' => ['required', 'array'], 'image_ids.*' => ['uuid']]);
        $this->images->reorder($product, $data['image_ids'], $request->user()->id);
        return back()->with('success', 'Product image sort order saved.');
    }

    private function ensureImageBelongsToProduct(Product $product, ProductImage $image): void
    {
        abort_unless($image->product_id === $product->id, 404);
    }
}

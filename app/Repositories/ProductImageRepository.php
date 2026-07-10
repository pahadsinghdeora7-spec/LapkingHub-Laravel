<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductImageRepository extends BaseRepository
{
    public function __construct(ProductImage $image) { parent::__construct($image); }

    public function upload(Product $product, array $data): ProductImage { return $product->images()->create($data); }
    public function update(ProductImage $image, array $data): ProductImage { $image->update($data); return $image->refresh(); }
    public function delete(ProductImage $image): void { $image->delete(); }
    public function restore(ProductImage $image): ProductImage { $image->restore(); return $image->refresh(); }
    public function forceDelete(ProductImage $image): void { $image->forceDelete(); }

    public function setPrimary(ProductImage $image, int $userId): ProductImage
    {
        ProductImage::query()->where('product_id', $image->product_id)->whereKeyNot($image->id)->update(['is_primary' => false, 'updated_by' => $userId]);
        $image->update(['is_primary' => true, 'updated_by' => $userId]);
        return $image->refresh();
    }

    public function reorder(Product $product, array $orderedIds, int $userId): void
    {
        foreach (array_values($orderedIds) as $index => $id) {
            ProductImage::query()->where('product_id', $product->id)->whereKey($id)->update(['sort_order' => $index + 1, 'updated_by' => $userId]);
        }
    }

    public function listImages(Product $product, array $filters = []): LengthAwarePaginator
    {
        $query = $product->images()->withTrashed()->with(['creator:id,name', 'updater:id,name']);
        $query->when($filters['search'] ?? null, fn ($query, $search) => $query->where(fn ($query) => $query->where('image_name', 'like', "%{$search}%")->orWhere('alt_text', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%")));
        match ($filters['trashed'] ?? null) { 'only' => $query->onlyTrashed(), 'with' => null, default => $query->withoutTrashed() };
        return $query->orderBy('sort_order')->orderByDesc('created_at')->paginate((int) ($filters['per_page'] ?? 12))->withQueryString();
    }

    public function activeImages(Product $product): Collection
    {
        return $product->images()->orderByDesc('is_primary')->orderBy('sort_order')->get();
    }
}

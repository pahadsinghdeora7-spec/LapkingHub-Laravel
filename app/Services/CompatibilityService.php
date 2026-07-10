<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductLaptopModel;
use App\Repositories\CompatibilityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CompatibilityService extends BaseService
{
    public function __construct(private readonly CompatibilityRepository $compatibilities) {}

    public function paginatedForProduct(Product $product, array $filters): LengthAwarePaginator { return $this->compatibilities->paginatedForProduct($product, $filters); }
    public function laptopModelSearch(array $filters): LengthAwarePaginator { return $this->compatibilities->laptopModelSearch($filters); }
    public function groupedForProduct(Product $product): Collection { return $this->compatibilities->groupedForProduct($product); }

    public function bulkAssign(Product $product, array $data, int $userId): int
    {
        $created = 0;
        foreach (array_unique($data['laptop_model_ids'] ?? []) as $laptopModelId) {
            if ($this->compatibilities->exists($product, $laptopModelId)) {
                continue;
            }
            $payload = Arr::only($data, ['compatibility_type', 'oem_part_number', 'notes', 'priority', 'status']);
            $payload += ['product_id' => $product->id, 'laptop_model_id' => $laptopModelId, 'created_by' => $userId, 'updated_by' => $userId];
            $this->compatibilities->create($payload);
            $created++;
        }
        if ($created === 0) {
            throw ValidationException::withMessages(['laptop_model_ids' => 'All selected laptop models are already assigned to this product.']);
        }
        $this->log($product, 'compatibility_bulk_assigned', "{$created} laptop model compatibility records assigned.", $userId, ['created' => $created]);
        return $created;
    }

    public function bulkRemove(Product $product, array $laptopModelIds, int $userId): int
    {
        $deleted = $this->compatibilities->deleteForProduct($product, $laptopModelIds);
        $this->log($product, 'compatibility_bulk_removed', "{$deleted} laptop model compatibility records removed.", $userId, ['deleted' => $deleted]);
        return $deleted;
    }

    private function log(Product $product, string $event, string $description, int $userId, array $properties = []): void
    {
        ActivityLog::query()->create(['user_id' => $userId, 'subject_type' => $product::class, 'subject_id' => $product->id, 'event' => $event, 'description' => $description, 'properties' => $properties + ['product_id' => $product->id, 'sku' => $product->sku], 'ip_address' => request()?->ip(), 'user_agent' => request()?->userAgent()]);
    }
}

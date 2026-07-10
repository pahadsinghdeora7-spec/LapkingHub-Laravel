<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductService extends BaseService
{
    public function __construct(private readonly ProductRepository $products) {}
    public function paginated(array $filters): LengthAwarePaginator { return $this->products->paginated($filters); }
    public function findForAdmin(string $id): Product { return $this->products->findForAdmin($id); }

    public function create(array $data, int $userId): Product
    {
        $parts = $data['alternate_part_numbers'] ?? [];
        $laptopModels = $data['laptop_model_ids'] ?? [];
        $payload = $this->payload($data, $userId, true);
        $product = $this->products->create($payload);
        $this->syncRelations($product, $parts, $laptopModels, $userId);
        $this->log($product, 'created', 'Product created.', $userId);
        return $product;
    }

    public function update(Product $product, array $data, int $userId): Product
    {
        $parts = $data['alternate_part_numbers'] ?? [];
        $laptopModels = $data['laptop_model_ids'] ?? [];
        $product = $this->products->update($product, $this->payload($data, $userId));
        $this->syncRelations($product, $parts, $laptopModels, $userId);
        $this->log($product, 'updated', 'Product updated.', $userId);
        return $product;
    }

    public function delete(Product $product, int $userId): void { $product->delete(); $this->log($product, 'deleted', 'Product soft deleted.', $userId); }
    public function restore(Product $product, int $userId): Product { $product->restore(); $this->log($product, 'restored', 'Product restored.', $userId); return $product->refresh(); }
    public function forceDelete(Product $product, int $userId): void { $this->log($product, 'force_deleted', 'Product permanently deleted.', $userId); $product->forceDelete(); }
    public function bulk(array $ids, string $action, int $userId): int { $count = $this->products->bulk($ids, $action); ActivityLog::query()->create(['user_id'=>$userId,'event'=>'bulk_'.$action,'description'=>'Bulk product action executed.','properties'=>['ids'=>$ids,'count'=>$count],'ip_address'=>request()?->ip(),'user_agent'=>request()?->userAgent()]); return $count; }

    private function payload(array $data, int $userId, bool $creating = false): array
    {
        $payload = Arr::only($data, ['brand_id','category_id','sku','name','slug','short_description','description','condition','warranty','oem_part_number','weight','dimensions','hsn_code','gst_rate','mrp','price','cost_price','minimum_order_quantity','stock_status','status','is_featured','is_trending','meta_title','meta_description','meta_keywords']);
        $payload['slug'] = Str::slug($payload['slug'] ?? $payload['name']);
        $payload['compare_at_price'] = $payload['mrp'] ?? null;
        $payload['is_active'] = ($payload['status'] ?? Product::STATUS_DRAFT) === Product::STATUS_ACTIVE;
        $payload['updated_by'] = $userId;
        if ($creating) { $payload['created_by'] = $userId; }
        return $payload;
    }

    private function syncRelations(Product $product, array $parts, array $laptopModels, int $userId): void
    {
        $product->alternatePartNumbers()->delete();
        $parts = collect($parts)->flatMap(fn ($part) => explode(',', (string) $part))->map(fn ($part) => trim($part))->filter()->unique();
        foreach ($parts as $part) { $product->alternatePartNumbers()->create(['part_number' => $part]); }
        $sync = [];
        foreach ($laptopModels as $id) { $sync[$id] = ['compatibility_type'=>'direct','status'=>'active','created_by'=>$userId,'updated_by'=>$userId]; }
        $product->laptopModels()->sync($sync);
    }

    private function log(Product $product, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create(['user_id'=>$userId,'subject_type'=>$product::class,'subject_id'=>$product->id,'event'=>$event,'description'=>$description,'properties'=>['sku'=>$product->sku,'name'=>$product->name],'ip_address'=>request()?->ip(),'user_agent'=>request()?->userAgent()]);
    }
}

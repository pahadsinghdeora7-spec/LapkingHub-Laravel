<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\ProductImageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProductImageService extends BaseService
{
    public function __construct(private readonly ProductImageRepository $images) {}

    public function listImages(Product $product, array $filters = []): LengthAwarePaginator { return $this->images->listImages($product, $filters); }

    public function upload(Product $product, array $data, int $userId): array
    {
        $created = [];
        foreach ($data['images'] as $file) {
            $payload = $this->payload($file, $data, $userId, true);
            $payload['sort_order'] = (int) ($data['sort_order'] ?? ($product->images()->withTrashed()->max('sort_order') + 1));
            $payload['is_primary'] = (bool) ($data['is_primary'] ?? ! $product->images()->exists());
            $image = $this->images->upload($product, $payload);
            if ($image->is_primary) $this->images->setPrimary($image, $userId);
            $this->log($image, 'created', 'Product image uploaded.', $userId);
            $created[] = $image;
        }
        return $created;
    }

    public function update(ProductImage $image, array $data, int $userId): ProductImage
    {
        $payload = Arr::only($data, ['alt_text', 'title', 'sort_order']);
        $payload['updated_by'] = $userId;
        if (isset($data['image'])) {
            Storage::disk(config('products.images.disk', 'public'))->delete($image->image_path);
            $payload += $this->payload($data['image'], $data, $userId, false);
        }
        $image = $this->images->update($image, $payload);
        $this->log($image, 'updated', 'Product image updated.', $userId);
        return $image;
    }

    public function delete(ProductImage $image, int $userId): void { $this->images->update($image, ['updated_by' => $userId]); $this->images->delete($image); $this->log($image, 'deleted', 'Product image soft deleted.', $userId); }
    public function restore(ProductImage $image, int $userId): ProductImage { $image = $this->images->restore($image); $this->images->update($image, ['updated_by' => $userId]); $this->log($image, 'restored', 'Product image restored.', $userId); return $image; }
    public function forceDelete(ProductImage $image, int $userId): void { Storage::disk(config('products.images.disk', 'public'))->delete($image->image_path); $this->log($image, 'force_deleted', 'Product image permanently deleted.', $userId); $this->images->forceDelete($image); }
    public function setPrimary(ProductImage $image, int $userId): ProductImage { $image = $this->images->setPrimary($image, $userId); $this->log($image, 'primary_set', 'Product primary image changed.', $userId); return $image; }
    public function reorder(Product $product, array $ids, int $userId): void { $this->images->reorder($product, $ids, $userId); ActivityLog::query()->create(['user_id'=>$userId,'subject_type'=>$product::class,'subject_id'=>$product->id,'event'=>'images_reordered','description'=>'Product images reordered.','properties'=>['image_ids'=>$ids],'ip_address'=>request()?->ip(),'user_agent'=>request()?->userAgent()]); }

    private function payload(UploadedFile $file, array $data, int $userId, bool $creating): array
    {
        [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];
        $path = $file->store(config('products.images.directory', 'products/gallery'), config('products.images.disk', 'public'));
        return ['image_path'=>$path,'image_name'=>$file->getClientOriginalName(),'alt_text'=>$data['alt_text'] ?? null,'title'=>$data['title'] ?? null,'image_size'=>$file->getSize(),'image_width'=>$width,'image_height'=>$height,'updated_by'=>$userId] + ($creating ? ['created_by'=>$userId] : []);
    }

    private function log(ProductImage $image, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create(['user_id'=>$userId,'subject_type'=>$image::class,'subject_id'=>$image->id,'event'=>$event,'description'=>$description,'properties'=>['product_id'=>$image->product_id,'image_path'=>$image->image_path],'ip_address'=>request()?->ip(),'user_agent'=>request()?->userAgent()]);
    }
}

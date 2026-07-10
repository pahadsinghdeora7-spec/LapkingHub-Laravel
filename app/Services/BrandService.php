<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandService extends BaseService
{
    public function __construct(private readonly BrandRepository $brands)
    {
    }

    public function paginated(array $filters): LengthAwarePaginator
    {
        return $this->brands->paginated($filters);
    }

    public function findForAdmin(string $id): Brand
    {
        return $this->brands->findForAdmin($id);
    }

    public function create(array $data, int $userId): Brand
    {
        $data = $this->preparePayload($data);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        $brand = $this->brands->create($data);
        $this->log($brand, 'created', 'Brand created.', $userId);

        return $brand;
    }

    public function update(Brand $brand, array $data, int $userId): Brand
    {
        $data = $this->preparePayload($data, $brand);
        $data['updated_by'] = $userId;

        $brand = $this->brands->update($brand, $data);
        $this->log($brand, 'updated', 'Brand updated.', $userId);

        return $brand;
    }

    public function delete(Brand $brand, int $userId): void
    {
        $brand->delete();
        $this->log($brand, 'deleted', 'Brand soft deleted.', $userId);
    }

    public function restore(Brand $brand, int $userId): Brand
    {
        $brand->restore();
        $this->log($brand, 'restored', 'Brand restored.', $userId);

        return $brand->refresh();
    }

    public function forceDelete(Brand $brand, int $userId): void
    {
        if ($brand->logo_path) {
            Storage::disk('public')->delete($brand->logo_path);
        }

        $this->log($brand, 'force_deleted', 'Brand permanently deleted.', $userId);
        $brand->forceDelete();
    }

    private function preparePayload(array $data, ?Brand $brand = null): array
    {
        $payload = Arr::only($data, ['name', 'slug', 'description', 'website', 'country', 'status', 'seo_title', 'seo_description']);
        $payload['country'] = isset($payload['country']) ? strtoupper($payload['country']) : null;
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name'], $brand?->id);

        if (($data['logo'] ?? null) instanceof UploadedFile) {
            if ($brand?->logo_path) {
                Storage::disk('public')->delete($brand->logo_path);
            }
            $payload['logo_path'] = $data['logo']->store('brands', 'public');
        }

        return $payload;
    }

    private function uniqueSlug(string $value, ?string $ignoreId = null): string
    {
        $base = Str::slug($value);
        $base = $base !== '' ? $base : Str::uuid()->toString();
        $slug = $base;
        $counter = 2;

        while (Brand::query()->withTrashed()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function log(Brand $brand, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId,
            'subject_type' => $brand::class,
            'subject_id' => $brand->id,
            'event' => $event,
            'description' => $description,
            'properties' => ['name' => $brand->name, 'slug' => $brand->slug],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}

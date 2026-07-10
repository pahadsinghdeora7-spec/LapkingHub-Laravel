<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Manufacturer;
use App\Repositories\ManufacturerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManufacturerService extends BaseService
{
    public function __construct(private readonly ManufacturerRepository $manufacturers)
    {
    }

    public function paginated(array $filters): LengthAwarePaginator
    {
        return $this->manufacturers->paginated($filters);
    }

    public function findForAdmin(string $id): Manufacturer
    {
        return $this->manufacturers->findForAdmin($id);
    }

    public function create(array $data, int $userId): Manufacturer
    {
        $data = $this->preparePayload($data);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        $manufacturer = $this->manufacturers->create($data);
        $this->log($manufacturer, 'created', 'Manufacturer created.', $userId);

        return $manufacturer;
    }

    public function update(Manufacturer $manufacturer, array $data, int $userId): Manufacturer
    {
        $data = $this->preparePayload($data, $manufacturer);
        $data['updated_by'] = $userId;

        $manufacturer = $this->manufacturers->update($manufacturer, $data);
        $this->log($manufacturer, 'updated', 'Manufacturer updated.', $userId);

        return $manufacturer;
    }

    public function delete(Manufacturer $manufacturer, int $userId): void
    {
        $manufacturer->delete();
        $this->log($manufacturer, 'deleted', 'Manufacturer soft deleted.', $userId);
    }

    public function restore(Manufacturer $manufacturer, int $userId): Manufacturer
    {
        $manufacturer->restore();
        $this->log($manufacturer, 'restored', 'Manufacturer restored.', $userId);

        return $manufacturer->refresh();
    }

    public function forceDelete(Manufacturer $manufacturer, int $userId): void
    {
        if ($manufacturer->logo_path) {
            Storage::disk('public')->delete($manufacturer->logo_path);
        }

        $this->log($manufacturer, 'force_deleted', 'Manufacturer permanently deleted.', $userId);
        $manufacturer->forceDelete();
    }

    private function preparePayload(array $data, ?Manufacturer $manufacturer = null): array
    {
        $payload = Arr::only($data, ['name', 'slug', 'description', 'country', 'status', 'seo_title', 'seo_description']);
        $payload['country'] = isset($payload['country']) ? strtoupper($payload['country']) : null;
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name'], $manufacturer?->id);

        if (($data['logo'] ?? null) instanceof UploadedFile) {
            if ($manufacturer?->logo_path) {
                Storage::disk('public')->delete($manufacturer->logo_path);
            }
            $payload['logo_path'] = $data['logo']->store('manufacturers', 'public');
        }

        return $payload;
    }

    private function uniqueSlug(string $value, ?string $ignoreId = null): string
    {
        $base = Str::slug($value);
        $base = $base !== '' ? $base : Str::uuid()->toString();
        $slug = $base;
        $counter = 2;

        while (Manufacturer::query()->withTrashed()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function log(Manufacturer $manufacturer, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId,
            'subject_type' => $manufacturer::class,
            'subject_id' => $manufacturer->id,
            'event' => $event,
            'description' => $description,
            'properties' => ['name' => $manufacturer->name, 'slug' => $manufacturer->slug],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}

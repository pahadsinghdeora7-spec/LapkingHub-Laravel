<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\LaptopModel;
use App\Repositories\LaptopModelRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LaptopModelService extends BaseService
{
    public function __construct(private readonly LaptopModelRepository $laptopModels) {}

    public function paginated(array $filters): LengthAwarePaginator { return $this->laptopModels->paginated($filters); }
    public function findForAdmin(string $id): LaptopModel { return $this->laptopModels->findForAdmin($id); }

    public function create(array $data, int $userId): LaptopModel
    {
        $data = $this->preparePayload($data);
        $data['created_by'] = $userId; $data['updated_by'] = $userId;
        $model = $this->laptopModels->create($data);
        $this->log($model, 'created', 'Laptop model created.', $userId);
        return $model;
    }

    public function update(LaptopModel $laptopModel, array $data, int $userId): LaptopModel
    {
        $data = $this->preparePayload($data, $laptopModel);
        $data['updated_by'] = $userId;
        $model = $this->laptopModels->update($laptopModel, $data);
        $this->log($model, 'updated', 'Laptop model updated.', $userId);
        return $model;
    }

    public function delete(LaptopModel $laptopModel, int $userId): void { $laptopModel->delete(); $this->log($laptopModel, 'deleted', 'Laptop model soft deleted.', $userId); }
    public function restore(LaptopModel $laptopModel, int $userId): LaptopModel { $laptopModel->restore(); $this->log($laptopModel, 'restored', 'Laptop model restored.', $userId); return $laptopModel->refresh(); }
    public function forceDelete(LaptopModel $laptopModel, int $userId): void { $this->log($laptopModel, 'force_deleted', 'Laptop model permanently deleted.', $userId); $laptopModel->forceDelete(); }

    private function preparePayload(array $data, ?LaptopModel $laptopModel = null): array
    {
        $payload = Arr::only($data, ['manufacturer_id', 'series_id', 'model_name', 'model_number', 'slug', 'release_year', 'description', 'status', 'seo_title', 'seo_description']);
        $payload['model_number'] = $payload['model_number'] ?? null;
        $payload['release_year'] = $payload['release_year'] ?? null;
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['model_name'], $payload['series_id'], $laptopModel?->id);
        return $payload;
    }

    private function uniqueSlug(string $value, string $seriesId, ?string $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::uuid()->toString();
        $slug = $base; $counter = 2;
        while (LaptopModel::query()->withTrashed()->where('series_id', $seriesId)->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$counter}"; $counter++;
        }
        return $slug;
    }

    private function log(LaptopModel $model, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId, 'subject_type' => $model::class, 'subject_id' => $model->id,
            'event' => $event, 'description' => $description,
            'properties' => ['model_name' => $model->model_name, 'slug' => $model->slug, 'manufacturer_id' => $model->manufacturer_id, 'series_id' => $model->series_id],
            'ip_address' => request()?->ip(), 'user_agent' => request()?->userAgent(),
        ]);
    }
}

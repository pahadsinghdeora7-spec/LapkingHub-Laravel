<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Series;
use App\Repositories\SeriesRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SeriesService extends BaseService
{
    public function __construct(private readonly SeriesRepository $series)
    {
    }

    public function paginated(array $filters): LengthAwarePaginator
    {
        return $this->series->paginated($filters);
    }

    public function findForAdmin(string $id): Series
    {
        return $this->series->findForAdmin($id);
    }

    public function create(array $data, int $userId): Series
    {
        $data = $this->preparePayload($data);
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;

        $series = $this->series->create($data);
        $this->log($series, 'created', 'Series created.', $userId);

        return $series;
    }

    public function update(Series $series, array $data, int $userId): Series
    {
        $data = $this->preparePayload($data, $series);
        $data['updated_by'] = $userId;

        $series = $this->series->update($series, $data);
        $this->log($series, 'updated', 'Series updated.', $userId);

        return $series;
    }

    public function delete(Series $series, int $userId): void
    {
        $series->delete();
        $this->log($series, 'deleted', 'Series soft deleted.', $userId);
    }

    public function restore(Series $series, int $userId): Series
    {
        $series->restore();
        $this->log($series, 'restored', 'Series restored.', $userId);

        return $series->refresh();
    }

    public function forceDelete(Series $series, int $userId): void
    {
        $this->log($series, 'force_deleted', 'Series permanently deleted.', $userId);
        $series->forceDelete();
    }

    private function preparePayload(array $data, ?Series $series = null): array
    {
        $payload = Arr::only($data, ['manufacturer_id', 'name', 'slug', 'description', 'status', 'seo_title', 'seo_description']);
        $payload['slug'] = $this->uniqueSlug($payload['slug'] ?? $payload['name'], $payload['manufacturer_id'], $series?->id);

        return $payload;
    }

    private function uniqueSlug(string $value, string $manufacturerId, ?string $ignoreId = null): string
    {
        $base = Str::slug($value);
        $base = $base !== '' ? $base : Str::uuid()->toString();
        $slug = $base;
        $counter = 2;

        while (Series::query()->withTrashed()->where('manufacturer_id', $manufacturerId)->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function log(Series $series, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId,
            'subject_type' => $series::class,
            'subject_id' => $series->id,
            'event' => $event,
            'description' => $description,
            'properties' => ['name' => $series->name, 'slug' => $series->slug, 'manufacturer_id' => $series->manufacturer_id],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}

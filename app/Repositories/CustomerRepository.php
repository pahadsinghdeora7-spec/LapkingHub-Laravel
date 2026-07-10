<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $customer) { parent::__construct($customer); }

    public function paginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->query()->with(['creator:id,name', 'updater:id,name'])->withCount('orders');
        $this->applyFilters($query, $filters);

        $sort = in_array($filters['sort'] ?? '', ['customer_code', 'business_name', 'customer_name', 'email', 'status', 'created_at'], true) ? $filters['sort'] : 'created_at';
        $direction = ($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sort, $direction)->paginate((int) ($filters['per_page'] ?? 15))->withQueryString();
    }

    public function findForAdmin(string $id): Customer
    {
        return $this->query()->withTrashed()->with(['creator:id,name', 'updater:id,name', 'addresses', 'orders'])->withCount('orders')->findOrFail($id);
    }

    public function create(array $data): Customer { return $this->query()->create($data); }
    public function update(Customer $customer, array $data): Customer { $customer->update($data); return $customer->refresh(); }
    public function delete(Customer $customer): void { $customer->delete(); }
    public function restore(Customer $customer): Customer { $customer->restore(); return $customer->refresh(); }

    public function existsDuplicate(string $email, ?string $phone = null, ?string $gstNumber = null, ?string $ignoreId = null): bool
    {
        return $this->query()->withTrashed()->where(function (Builder $query) use ($email, $phone, $gstNumber): void {
            $query->where('email', $email)
                ->when($phone, fn (Builder $query) => $query->orWhere('phone', $phone))
                ->when($gstNumber, fn (Builder $query) => $query->orWhere('gst_number', $gstNumber));
        })->when($ignoreId, fn (Builder $query) => $query->whereKeyNot($ignoreId))->exists();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function (Builder $query, string $search): void {
            $query->where(function (Builder $query) use ($search): void {
                $query->where('customer_code', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('gst_number', 'like', "%{$search}%");
            });
        });
        $query->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status));
        $query->when($filters['business_type'] ?? null, fn (Builder $query, string $type) => $query->where('business_type', $type));
        match ($filters['trashed'] ?? null) { 'with' => $query->withTrashed(), 'only' => $query->onlyTrashed(), default => null };
    }
}

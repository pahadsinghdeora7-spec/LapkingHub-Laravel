<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CustomerService extends BaseService
{
    public function __construct(private readonly CustomerRepository $customers) {}

    public function paginated(array $filters): LengthAwarePaginator { return $this->customers->paginated($filters); }
    public function findForAdmin(string $id): Customer { return $this->customers->findForAdmin($id); }

    public function create(array $data, int $userId): Customer
    {
        $data = $this->preparePayload($data);
        $this->preventDuplicate($data);
        $data['customer_code'] = $this->generateCustomerCode();
        $data['available_credit'] = $data['credit_limit'];
        $data['created_by'] = $userId;
        $data['updated_by'] = $userId;
        $customer = $this->customers->create($data);
        $this->log($customer, 'created', 'Customer created.', $userId);
        return $customer;
    }

    public function update(Customer $customer, array $data, int $userId): Customer
    {
        $data = $this->preparePayload($data);
        $this->preventDuplicate($data, $customer->id);
        $data['updated_by'] = $userId;
        if (array_key_exists('credit_limit', $data)) {
            $usedCredit = max(0, (float) $customer->credit_limit - (float) $customer->available_credit);
            $data['available_credit'] = max(0, (float) $data['credit_limit'] - $usedCredit);
        }
        $customer = $this->customers->update($customer, $data);
        $this->log($customer, 'updated', 'Customer updated.', $userId);
        return $customer;
    }

    public function delete(Customer $customer, int $userId): void { $this->customers->delete($customer); $this->log($customer, 'deleted', 'Customer soft deleted.', $userId); }
    public function restore(Customer $customer, int $userId): Customer { $customer = $this->customers->restore($customer); $this->log($customer, 'restored', 'Customer restored.', $userId); return $customer; }

    private function preparePayload(array $data): array
    {
        $payload = Arr::only($data, ['business_name','customer_name','email','phone','alternate_phone','gst_number','business_type','billing_address','shipping_address','city','state','country','pincode','credit_limit','status','notes']);
        $payload['email'] = strtolower($payload['email']);
        $payload['gst_number'] = isset($payload['gst_number']) ? strtoupper($payload['gst_number']) : null;
        $payload['country'] = $payload['country'] ?? 'India';
        $payload['credit_limit'] = (float) ($payload['credit_limit'] ?? 0);
        if ($payload['credit_limit'] < 0) {
            throw ValidationException::withMessages(['credit_limit' => 'Credit limit cannot be negative.']);
        }
        return $payload;
    }

    private function preventDuplicate(array $data, ?string $ignoreId = null): void
    {
        if ($this->customers->existsDuplicate($data['email'], $data['phone'] ?? null, $data['gst_number'] ?? null, $ignoreId)) {
            throw ValidationException::withMessages(['email' => 'A customer with this email, phone, or GST number already exists.']);
        }
    }

    private function generateCustomerCode(): string
    {
        do {
            $code = 'CUST-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Customer::query()->withTrashed()->where('customer_code', $code)->exists());
        return $code;
    }

    private function log(Customer $customer, string $event, string $description, int $userId): void
    {
        ActivityLog::query()->create([
            'user_id' => $userId,
            'subject_type' => $customer::class,
            'subject_id' => $customer->id,
            'event' => $event,
            'description' => $description,
            'properties' => ['customer_code' => $customer->customer_code, 'business_name' => $customer->business_name],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}

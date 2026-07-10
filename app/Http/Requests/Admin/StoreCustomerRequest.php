<?php

namespace App\Http\Requests\Admin;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->can('create', Customer::class); }
    public function rules(): array { return $this->customerRules(); }
    protected function customerRules(): array
    {
        return [
            'business_name' => ['required','string','max:255'], 'customer_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'], 'phone' => ['nullable','string','max:30'], 'alternate_phone' => ['nullable','string','max:30'],
            'gst_number' => ['nullable','string','max:30'], 'business_type' => ['nullable', Rule::in(Customer::businessTypes())],
            'billing_address' => ['nullable','string','max:5000'], 'shipping_address' => ['nullable','string','max:5000'],
            'city' => ['nullable','string','max:100'], 'state' => ['nullable','string','max:100'], 'country' => ['nullable','string','max:100'], 'pincode' => ['nullable','string','max:20'],
            'credit_limit' => ['required','numeric','min:0','max:9999999999.99'], 'status' => ['required', Rule::in(Customer::statuses())], 'notes' => ['nullable','string','max:5000'],
        ];
    }
}

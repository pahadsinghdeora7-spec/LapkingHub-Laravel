<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $product = $this->route('product');
        return $product instanceof Product ? $this->user()->can('update', $product) : $this->user()->can('create', Product::class);
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;
        return [
            'brand_id' => ['nullable', 'uuid', 'exists:brands,id'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)->withoutTrashed()],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash', Rule::unique('products', 'slug')->ignore($productId)->withoutTrashed()],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'condition' => ['required', Rule::in(Product::conditions())],
            'warranty' => ['nullable', 'string', 'max:255'],
            'oem_part_number' => ['nullable', 'string', 'max:255'],
            'alternate_part_numbers' => ['nullable', 'array'],
            'alternate_part_numbers.*' => ['nullable', 'string', 'max:255', 'distinct'],
            'laptop_model_ids' => ['nullable', 'array'],
            'laptop_model_ids.*' => ['uuid', 'exists:laptop_models,id'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'array'],
            'dimensions.length' => ['nullable', 'numeric', 'min:0'],
            'dimensions.width' => ['nullable', 'numeric', 'min:0'],
            'dimensions.height' => ['nullable', 'numeric', 'min:0'],
            'hsn_code' => ['nullable', 'string', 'max:50'],
            'gst_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'mrp' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'minimum_order_quantity' => ['required', 'integer', 'min:1'],
            'stock_status' => ['required', Rule::in(Product::stockStatuses())],
            'status' => ['required', Rule::in(Product::statuses())],
            'is_featured' => ['boolean'],
            'is_trending' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_featured' => $this->boolean('is_featured'), 'is_trending' => $this->boolean('is_trending')]);
    }
}

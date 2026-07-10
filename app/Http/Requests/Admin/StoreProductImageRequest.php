<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('create-product-images') ?? false; }

    public function rules(): array
    {
        $max = config('products.images.max_size_kb', 4096);
        $mimes = implode(',', config('products.images.mimes', ['jpg', 'jpeg', 'png', 'webp']));
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', "mimes:{$mimes}", "max:{$max}"],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_primary' => ['nullable', 'boolean'],
        ];
    }
}

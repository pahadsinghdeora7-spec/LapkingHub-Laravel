<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()?->can('edit-product-images') ?? false; }

    public function rules(): array
    {
        $max = config('products.images.max_size_kb', 4096);
        $mimes = implode(',', config('products.images.mimes', ['jpg', 'jpeg', 'png', 'webp']));
        return [
            'image' => ['nullable', 'image', "mimes:{$mimes}", "max:{$max}"],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}

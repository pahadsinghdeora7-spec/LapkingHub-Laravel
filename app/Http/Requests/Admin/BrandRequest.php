<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        $brand = $this->route('brand');

        return $brand instanceof Brand
            ? $this->user()->can('update', $brand)
            : $this->user()->can('create', Brand::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'url', 'max:255'],
            'country' => ['nullable', 'string', 'size:2'],
            'status' => ['required', Rule::in(Brand::statuses())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}

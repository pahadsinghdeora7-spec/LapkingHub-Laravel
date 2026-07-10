<?php

namespace App\Http\Requests\Admin;

use App\Models\Manufacturer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ManufacturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $manufacturer = $this->route('manufacturer');

        return $manufacturer instanceof Manufacturer
            ? $this->user()->can('update', $manufacturer)
            : $this->user()->can('create', Manufacturer::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'country' => ['nullable', 'string', 'size:2'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(Manufacturer::statuses())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}

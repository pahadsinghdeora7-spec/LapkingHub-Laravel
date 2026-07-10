<?php

namespace App\Http\Requests\Admin;

use App\Models\LaptopModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaptopModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        $model = $this->route('laptop_model');
        return $model instanceof LaptopModel ? $this->user()->can('update', $model) : $this->user()->can('create', LaptopModel::class);
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => ['required', 'uuid', 'exists:manufacturers,id'],
            'series_id' => ['required', 'uuid', Rule::exists('series', 'id')->where('manufacturer_id', $this->input('manufacturer_id'))],
            'model_name' => ['required', 'string', 'max:255'],
            'model_number' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'release_year' => ['nullable', 'integer', 'min:1980', 'max:'.((int) date('Y') + 1)],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(LaptopModel::statuses())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}

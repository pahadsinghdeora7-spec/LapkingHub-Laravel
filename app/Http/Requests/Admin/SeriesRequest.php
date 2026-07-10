<?php

namespace App\Http\Requests\Admin;

use App\Models\Series;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        $series = $this->route('series');

        return $series instanceof Series
            ? $this->user()->can('update', $series)
            : $this->user()->can('create', Series::class);
    }

    public function rules(): array
    {
        return [
            'manufacturer_id' => ['required', 'uuid', 'exists:manufacturers,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'alpha_dash'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(Series::statuses())],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaptopModel;
use App\Models\Manufacturer;
use App\Models\Product;
use App\Models\ProductLaptopModel;
use App\Models\Series;
use App\Services\CompatibilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompatibilityController extends Controller
{
    public function __construct(private readonly CompatibilityService $compatibilities) {}

    public function index(Request $request, Product $product): View
    {
        $this->authorize('view-product-compatibility', $product);
        return view('admin.compatibilities.index', [
            'product' => $product,
            'compatibilities' => $this->compatibilities->paginatedForProduct($product, $request->only(['status', 'compatibility_type', 'manufacturer_id', 'series_id', 'laptop_model_id', 'per_page'])),
            'laptopModels' => $this->compatibilities->laptopModelSearch($request->only(['search', 'manufacturer_id', 'series_id', 'laptop_model_id', 'model_per_page'])),
            'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']),
            'seriesOptions' => Series::query()->orderBy('name')->get(['id', 'name', 'manufacturer_id']),
            'selectedLaptopModel' => $request->filled('laptop_model_id') ? LaptopModel::query()->find($request->string('laptop_model_id')) : null,
            'types' => ProductLaptopModel::compatibilityTypes(),
            'statuses' => ProductLaptopModel::statuses(),
            'filters' => $request->all(),
        ]);
    }

    public function bulkAssign(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('assign-product-compatibility', $product);
        $data = $request->validate([
            'laptop_model_ids' => ['required', 'array', 'min:1'],
            'laptop_model_ids.*' => ['required', 'uuid', Rule::exists('laptop_models', 'id')],
            'compatibility_type' => ['required', Rule::in(ProductLaptopModel::compatibilityTypes())],
            'oem_part_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', 'integer', 'min:0', 'max:999999'],
            'status' => ['required', Rule::in(ProductLaptopModel::statuses())],
        ]);
        $created = $this->compatibilities->bulkAssign($product, $data, $request->user()->id);
        return back()->with('success', "{$created} compatible laptop model(s) assigned.");
    }

    public function bulkRemove(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('remove-product-compatibility', $product);
        $data = $request->validate(['laptop_model_ids' => ['required', 'array', 'min:1'], 'laptop_model_ids.*' => ['required', 'uuid']]);
        $deleted = $this->compatibilities->bulkRemove($product, $data['laptop_model_ids'], $request->user()->id);
        return back()->with('success', "{$deleted} compatibility record(s) removed.");
    }
}

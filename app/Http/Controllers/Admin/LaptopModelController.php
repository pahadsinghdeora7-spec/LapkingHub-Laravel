<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaptopModelRequest;
use App\Models\LaptopModel;
use App\Models\Manufacturer;
use App\Models\Series;
use App\Services\LaptopModelService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaptopModelController extends Controller
{
    public function __construct(private readonly LaptopModelService $laptopModels) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', LaptopModel::class);
        return view('admin.laptop-models.index', [
            'laptopModels' => $this->laptopModels->paginated($request->only(['search', 'status', 'manufacturer_id', 'series_id', 'release_year', 'trashed', 'sort', 'direction', 'per_page'])),
            'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']),
            'seriesOptions' => Series::query()->orderBy('name')->get(['id', 'name', 'manufacturer_id']),
            'statuses' => LaptopModel::statuses(), 'filters' => $request->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', LaptopModel::class);
        return view('admin.laptop-models.create', ['laptopModel' => new LaptopModel(), 'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']), 'seriesOptions' => Series::query()->orderBy('name')->get(['id', 'name', 'manufacturer_id']), 'statuses' => LaptopModel::statuses()]);
    }

    public function store(LaptopModelRequest $request): RedirectResponse
    {
        $model = $this->laptopModels->create($request->validated(), $request->user()->id);
        return redirect()->route('admin.laptop-models.show', $model)->with('success', 'Laptop model created successfully.');
    }

    public function show(LaptopModel $laptopModel): View
    {
        $this->authorize('view', $laptopModel);
        $laptopModel->load(['manufacturer:id,name', 'series:id,name', 'creator:id,name', 'updater:id,name']);
        return view('admin.laptop-models.show', ['laptopModel' => $laptopModel]);
    }

    public function edit(LaptopModel $laptopModel): View
    {
        $this->authorize('update', $laptopModel);
        return view('admin.laptop-models.edit', ['laptopModel' => $laptopModel, 'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']), 'seriesOptions' => Series::query()->orderBy('name')->get(['id', 'name', 'manufacturer_id']), 'statuses' => LaptopModel::statuses()]);
    }

    public function update(LaptopModelRequest $request, LaptopModel $laptopModel): RedirectResponse
    {
        $model = $this->laptopModels->update($laptopModel, $request->validated(), $request->user()->id);
        return redirect()->route('admin.laptop-models.show', $model)->with('success', 'Laptop model updated successfully.');
    }

    public function destroy(Request $request, LaptopModel $laptopModel): RedirectResponse
    {
        $this->authorize('delete', $laptopModel); $this->laptopModels->delete($laptopModel, $request->user()->id);
        return redirect()->route('admin.laptop-models.index')->with('success', 'Laptop model moved to trash.');
    }

    public function restore(Request $request, string $laptopModel): RedirectResponse
    {
        $model = $this->laptopModels->findForAdmin($laptopModel); $this->authorize('restore', $model); $this->laptopModels->restore($model, $request->user()->id);
        return redirect()->route('admin.laptop-models.index', ['trashed' => 'with'])->with('success', 'Laptop model restored successfully.');
    }

    public function forceDelete(Request $request, string $laptopModel): RedirectResponse
    {
        $model = $this->laptopModels->findForAdmin($laptopModel); $this->authorize('forceDelete', $model); $this->laptopModels->forceDelete($model, $request->user()->id);
        return redirect()->route('admin.laptop-models.index', ['trashed' => 'only'])->with('success', 'Laptop model permanently deleted.');
    }
}

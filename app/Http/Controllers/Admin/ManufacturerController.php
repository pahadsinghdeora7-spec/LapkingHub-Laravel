<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ManufacturerRequest;
use App\Http\Resources\Admin\ManufacturerResource;
use App\Models\Manufacturer;
use App\Services\ManufacturerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    public function __construct(private readonly ManufacturerService $manufacturers)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Manufacturer::class);

        return view('admin.manufacturers.index', [
            'manufacturers' => $this->manufacturers->paginated($request->only(['search', 'status', 'country', 'trashed', 'sort', 'direction', 'per_page'])),
            'statuses' => Manufacturer::statuses(),
            'filters' => $request->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Manufacturer::class);

        return view('admin.manufacturers.create', ['manufacturer' => new Manufacturer(), 'statuses' => Manufacturer::statuses()]);
    }

    public function store(ManufacturerRequest $request): RedirectResponse
    {
        $manufacturer = $this->manufacturers->create($request->validated(), $request->user()->id);

        return redirect()->route('admin.manufacturers.show', $manufacturer)->with('success', 'Manufacturer created successfully.');
    }

    public function show(Request $request, Manufacturer $manufacturer): View|ManufacturerResource
    {
        $this->authorize('view', $manufacturer);
        $manufacturer->load(['creator:id,name', 'updater:id,name']);

        if ($request->wantsJson()) {
            return new ManufacturerResource($manufacturer);
        }

        return view('admin.manufacturers.show', ['manufacturer' => $manufacturer]);
    }

    public function edit(Manufacturer $manufacturer): View
    {
        $this->authorize('update', $manufacturer);

        return view('admin.manufacturers.edit', ['manufacturer' => $manufacturer, 'statuses' => Manufacturer::statuses()]);
    }

    public function update(ManufacturerRequest $request, Manufacturer $manufacturer): RedirectResponse
    {
        $manufacturer = $this->manufacturers->update($manufacturer, $request->validated(), $request->user()->id);

        return redirect()->route('admin.manufacturers.show', $manufacturer)->with('success', 'Manufacturer updated successfully.');
    }

    public function destroy(Request $request, Manufacturer $manufacturer): RedirectResponse
    {
        $this->authorize('delete', $manufacturer);
        $this->manufacturers->delete($manufacturer, $request->user()->id);

        return redirect()->route('admin.manufacturers.index')->with('success', 'Manufacturer moved to trash.');
    }

    public function restore(Request $request, string $manufacturer): RedirectResponse
    {
        $manufacturer = $this->manufacturers->findForAdmin($manufacturer);
        $this->authorize('restore', $manufacturer);
        $this->manufacturers->restore($manufacturer, $request->user()->id);

        return redirect()->route('admin.manufacturers.index', ['trashed' => 'with'])->with('success', 'Manufacturer restored successfully.');
    }

    public function forceDelete(Request $request, string $manufacturer): RedirectResponse
    {
        $manufacturer = $this->manufacturers->findForAdmin($manufacturer);
        $this->authorize('forceDelete', $manufacturer);
        $this->manufacturers->forceDelete($manufacturer, $request->user()->id);

        return redirect()->route('admin.manufacturers.index', ['trashed' => 'only'])->with('success', 'Manufacturer permanently deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function __construct(private readonly BrandService $brands)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Brand::class);

        return view('admin.brands.index', [
            'brands' => $this->brands->paginated($request->only(['search', 'status', 'country', 'trashed', 'sort', 'direction', 'per_page'])),
            'statuses' => Brand::statuses(),
            'filters' => $request->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Brand::class);

        return view('admin.brands.create', ['brand' => new Brand(), 'statuses' => Brand::statuses()]);
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        $brand = $this->brands->create($request->validated(), $request->user()->id);

        return redirect()->route('admin.brands.show', $brand)->with('success', 'Brand created successfully.');
    }

    public function show(Request $request, Brand $brand): View|BrandResource
    {
        $this->authorize('view', $brand);
        $brand->load(['creator:id,name', 'updater:id,name']);

        if ($request->wantsJson()) {
            return new BrandResource($brand);
        }

        return view('admin.brands.show', ['brand' => $brand]);
    }

    public function edit(Brand $brand): View
    {
        $this->authorize('update', $brand);

        return view('admin.brands.edit', ['brand' => $brand, 'statuses' => Brand::statuses()]);
    }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        $brand = $this->brands->update($brand, $request->validated(), $request->user()->id);

        return redirect()->route('admin.brands.show', $brand)->with('success', 'Brand updated successfully.');
    }

    public function destroy(Request $request, Brand $brand): RedirectResponse
    {
        $this->authorize('delete', $brand);
        $this->brands->delete($brand, $request->user()->id);

        return redirect()->route('admin.brands.index')->with('success', 'Brand moved to trash.');
    }

    public function restore(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->brands->findForAdmin($brand);
        $this->authorize('restore', $brand);
        $this->brands->restore($brand, $request->user()->id);

        return redirect()->route('admin.brands.index', ['trashed' => 'with'])->with('success', 'Brand restored successfully.');
    }

    public function forceDelete(Request $request, string $brand): RedirectResponse
    {
        $brand = $this->brands->findForAdmin($brand);
        $this->authorize('forceDelete', $brand);
        $this->brands->forceDelete($brand, $request->user()->id);

        return redirect()->route('admin.brands.index', ['trashed' => 'only'])->with('success', 'Brand permanently deleted.');
    }
}

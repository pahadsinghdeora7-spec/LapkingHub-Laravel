<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SeriesRequest;
use App\Models\Manufacturer;
use App\Models\Series;
use App\Services\SeriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeriesController extends Controller
{
    public function __construct(private readonly SeriesService $series)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Series::class);

        return view('admin.series.index', [
            'series' => $this->series->paginated($request->only(['search', 'status', 'manufacturer_id', 'trashed', 'sort', 'direction', 'per_page'])),
            'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']),
            'statuses' => Series::statuses(),
            'filters' => $request->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Series::class);

        return view('admin.series.create', ['series' => new Series(), 'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']), 'statuses' => Series::statuses()]);
    }

    public function store(SeriesRequest $request): RedirectResponse
    {
        $series = $this->series->create($request->validated(), $request->user()->id);

        return redirect()->route('admin.series.show', $series)->with('success', 'Series created successfully.');
    }

    public function show(Series $series): View
    {
        $this->authorize('view', $series);
        $series->load(['manufacturer:id,name', 'creator:id,name', 'updater:id,name']);

        return view('admin.series.show', ['series' => $series]);
    }

    public function edit(Series $series): View
    {
        $this->authorize('update', $series);

        return view('admin.series.edit', ['series' => $series, 'manufacturers' => Manufacturer::query()->orderBy('name')->get(['id', 'name']), 'statuses' => Series::statuses()]);
    }

    public function update(SeriesRequest $request, Series $series): RedirectResponse
    {
        $series = $this->series->update($series, $request->validated(), $request->user()->id);

        return redirect()->route('admin.series.show', $series)->with('success', 'Series updated successfully.');
    }

    public function destroy(Request $request, Series $series): RedirectResponse
    {
        $this->authorize('delete', $series);
        $this->series->delete($series, $request->user()->id);

        return redirect()->route('admin.series.index')->with('success', 'Series moved to trash.');
    }

    public function restore(Request $request, string $series): RedirectResponse
    {
        $series = $this->series->findForAdmin($series);
        $this->authorize('restore', $series);
        $this->series->restore($series, $request->user()->id);

        return redirect()->route('admin.series.index', ['trashed' => 'with'])->with('success', 'Series restored successfully.');
    }

    public function forceDelete(Request $request, string $series): RedirectResponse
    {
        $series = $this->series->findForAdmin($series);
        $this->authorize('forceDelete', $series);
        $this->series->forceDelete($series, $request->user()->id);

        return redirect()->route('admin.series.index', ['trashed' => 'only'])->with('success', 'Series permanently deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categories) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Category::class);
        return view('admin.categories.index', [
            'categories' => $this->categories->paginated($request->only(['search', 'status', 'parent_id', 'trashed', 'sort', 'direction', 'per_page'])),
            'tree' => $this->categories->tree(($request->input('trashed') ?? '') !== ''),
            'parentOptions' => $this->categories->options(),
            'filters' => $request->all(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);
        return view('admin.categories.create', ['category' => new Category(['is_active' => true]), 'parentOptions' => $this->categories->options()]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = $this->categories->create($request->validated(), $request->user()->id);
        return redirect()->route('admin.categories.show', $category)->with('success', 'Category created successfully.');
    }

    public function show(Category $category): View
    {
        $this->authorize('view', $category);
        $category->load(['parent:id,name', 'children:id,parent_id,name,slug,is_active,sort_order', 'creator:id,name', 'updater:id,name']);
        return view('admin.categories.show', ['category' => $category]);
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);
        return view('admin.categories.edit', ['category' => $category, 'parentOptions' => $this->categories->options($category->id)]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category = $this->categories->update($category, $request->validated(), $request->user()->id);
        return redirect()->route('admin.categories.show', $category)->with('success', 'Category updated successfully.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);
        $this->categories->delete($category, $request->user()->id);
        return redirect()->route('admin.categories.index')->with('success', 'Category moved to trash.');
    }

    public function restore(Request $request, string $category): RedirectResponse
    {
        $category = $this->categories->findForAdmin($category);
        $this->authorize('restore', $category);
        $this->categories->restore($category, $request->user()->id);
        return redirect()->route('admin.categories.index', ['trashed' => 'with'])->with('success', 'Category restored successfully.');
    }

    public function forceDelete(Request $request, string $category): RedirectResponse
    {
        $category = $this->categories->findForAdmin($category);
        $this->authorize('forceDelete', $category);
        $this->categories->forceDelete($category, $request->user()->id);
        return redirect()->route('admin.categories.index', ['trashed' => 'only'])->with('success', 'Category permanently deleted.');
    }
}

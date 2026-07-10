<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\LaptopModel;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $products) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);
        return view('admin.products.index', ['products'=>$this->products->paginated($request->only(['search','brand_id','category_id','condition','stock_status','status','trashed','sort','direction','per_page'])),'brands'=>Brand::orderBy('name')->get(['id','name']),'categories'=>Category::orderBy('name')->get(['id','name']),'conditions'=>Product::conditions(),'stockStatuses'=>Product::stockStatuses(),'statuses'=>Product::statuses(),'filters'=>$request->all()]);
    }
    public function create(): View { $this->authorize('create', Product::class); return view('admin.products.create', $this->formData(new Product())); }
    public function store(ProductRequest $request): RedirectResponse { $product = $this->products->create($request->validated(), $request->user()->id); return redirect()->route('admin.products.show', $product)->with('success', 'Product created successfully.'); }
    public function show(Request $request, Product $product): View|ProductResource { $this->authorize('view', $product); $product = $this->products->findForAdmin($product->id); if ($request->wantsJson()) return new ProductResource($product); return view('admin.products.show', ['product'=>$product]); }
    public function edit(Product $product): View { $this->authorize('update', $product); return view('admin.products.edit', $this->formData($this->products->findForAdmin($product->id))); }
    public function update(ProductRequest $request, Product $product): RedirectResponse { $product = $this->products->update($product, $request->validated(), $request->user()->id); return redirect()->route('admin.products.show', $product)->with('success', 'Product updated successfully.'); }
    public function destroy(Request $request, Product $product): RedirectResponse { $this->authorize('delete', $product); $this->products->delete($product, $request->user()->id); return redirect()->route('admin.products.index')->with('success', 'Product moved to trash.'); }
    public function restore(Request $request, string $product): RedirectResponse { $product = $this->products->findForAdmin($product); $this->authorize('restore', $product); $this->products->restore($product, $request->user()->id); return redirect()->route('admin.products.index', ['trashed'=>'with'])->with('success', 'Product restored successfully.'); }
    public function forceDelete(Request $request, string $product): RedirectResponse { $product = $this->products->findForAdmin($product); $this->authorize('forceDelete', $product); $this->products->forceDelete($product, $request->user()->id); return redirect()->route('admin.products.index', ['trashed'=>'only'])->with('success', 'Product permanently deleted.'); }
    public function bulk(Request $request): RedirectResponse { $this->authorize('delete', Product::class); $data = $request->validate(['action'=>['required','in:delete,restore,force_delete,activate,deactivate'],'ids'=>['required','array'],'ids.*'=>['uuid']]); $count = $this->products->bulk($data['ids'], $data['action'], $request->user()->id); return back()->with('success', "Bulk action applied to {$count} product(s)."); }
    public function import(): View { $this->authorize('create', Product::class); return view('admin.products.import'); }
    public function export(): View { $this->authorize('viewAny', Product::class); return view('admin.products.export'); }

    private function formData(Product $product): array
    {
        return ['product'=>$product,'brands'=>Brand::orderBy('name')->get(['id','name']),'categories'=>Category::orderBy('name')->get(['id','name']),'laptopModels'=>LaptopModel::with('manufacturer:id,name')->orderBy('name')->get(['id','manufacturer_id','name','model_number']),'conditions'=>Product::conditions(),'stockStatuses'=>Product::stockStatuses(),'statuses'=>Product::statuses()];
    }
}

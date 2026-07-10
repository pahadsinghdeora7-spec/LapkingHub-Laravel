<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryRequest;
use App\Http\Requests\Admin\StockAdjustmentRequest;
use App\Models\Inventory;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}
    public function index(Request $request): View { $this->authorize('viewAny', Inventory::class); return view('admin.inventory.index', ['inventories'=>$this->inventoryService->paginated($request->only(['search','status','warehouse','sort','direction','per_page'])),'statuses'=>Inventory::statuses(),'filters'=>$request->all()]); }
    public function create(): View { $this->authorize('create', Inventory::class); return view('admin.inventory.create', ['inventory'=>new Inventory(), 'products'=>Product::orderBy('name')->get(['id','name','sku'])]); }
    public function store(InventoryRequest $request): RedirectResponse { $inventory=$this->inventoryService->create($request->validated(), $request->user()->id); return redirect()->route('admin.inventory.show',$inventory)->with('success','Inventory created successfully.'); }
    public function show(Inventory $inventory): View { $this->authorize('view', $inventory); return view('admin.inventory.show', ['inventory'=>$inventory->load('product'),'movements'=>$this->inventoryService->movements(['inventory_id'=>$inventory->id])]); }
    public function edit(Inventory $inventory): View { $this->authorize('update', $inventory); return view('admin.inventory.edit', ['inventory'=>$inventory, 'products'=>Product::orderBy('name')->get(['id','name','sku'])]); }
    public function update(InventoryRequest $request, Inventory $inventory): RedirectResponse { $inventory=$this->inventoryService->update($inventory,$request->validated(),$request->user()->id); return redirect()->route('admin.inventory.show',$inventory)->with('success','Inventory updated successfully.'); }
    public function adjust(StockAdjustmentRequest $request, Inventory $inventory): RedirectResponse { $data=$request->validated(); match($data['movement_type']) { 'increase'=>$this->inventoryService->increaseStock($inventory,$data['quantity'],$request->user()->id,$data['remarks'] ?? null), 'decrease'=>$this->inventoryService->decreaseStock($inventory,$data['quantity'],$request->user()->id,$data['remarks'] ?? null), 'reserve'=>$this->inventoryService->reserveStock($inventory,$data['quantity'],$request->user()->id,$data['remarks'] ?? null), 'release'=>$this->inventoryService->releaseStock($inventory,$data['quantity'],$request->user()->id,$data['remarks'] ?? null), 'adjust'=>$this->inventoryService->adjustStock($inventory,$data['quantity'],$request->user()->id,$data['remarks'] ?? null) }; return back()->with('success','Stock adjusted successfully.'); }
    public function lowStock(): View { $this->authorize('viewAny', Inventory::class); return view('admin.inventory.low-stock', ['inventories'=>$this->inventoryService->lowStock()]); }
}

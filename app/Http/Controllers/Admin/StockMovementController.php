<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;
class StockMovementController extends Controller
{
    public function __construct(private readonly InventoryService $inventoryService) {}
    public function index(Request $request): View { $this->authorize('viewAny', StockMovement::class); return view('admin.stock-movements.index', ['movements'=>$this->inventoryService->movements($request->only(['inventory_id','movement_type','per_page'])),'filters'=>$request->all()]); }
}

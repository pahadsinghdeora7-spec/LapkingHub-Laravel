<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customers) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Customer::class);
        return view('admin.customers.index', [
            'customers' => $this->customers->paginated($request->only(['search','status','business_type','trashed','sort','direction','per_page'])),
            'statuses' => Customer::statuses(), 'businessTypes' => Customer::businessTypes(), 'filters' => $request->all(),
        ]);
    }

    public function create(): View { $this->authorize('create', Customer::class); return view('admin.customers.create', ['customer' => new Customer(['status' => Customer::STATUS_ACTIVE, 'credit_limit' => 0]), 'statuses' => Customer::statuses(), 'businessTypes' => Customer::businessTypes()]); }
    public function store(StoreCustomerRequest $request): RedirectResponse { $customer = $this->customers->create($request->validated(), $request->user()->id); return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer created successfully.'); }
    public function show(Customer $customer): View { $this->authorize('view', $customer); $customer->load(['creator:id,name','updater:id,name','addresses','orders']); return view('admin.customers.show', ['customer' => $customer]); }
    public function edit(Customer $customer): View { $this->authorize('update', $customer); return view('admin.customers.edit', ['customer' => $customer, 'statuses' => Customer::statuses(), 'businessTypes' => Customer::businessTypes()]); }
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse { $customer = $this->customers->update($customer, $request->validated(), $request->user()->id); return redirect()->route('admin.customers.show', $customer)->with('success', 'Customer updated successfully.'); }
    public function destroy(Request $request, Customer $customer): RedirectResponse { $this->authorize('delete', $customer); $this->customers->delete($customer, $request->user()->id); return redirect()->route('admin.customers.index')->with('success', 'Customer moved to trash.'); }
    public function restore(Request $request, string $customer): RedirectResponse { $customer = $this->customers->findForAdmin($customer); $this->authorize('restore', $customer); $this->customers->restore($customer, $request->user()->id); return redirect()->route('admin.customers.index', ['trashed' => 'with'])->with('success', 'Customer restored successfully.'); }
}

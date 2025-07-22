<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        return view('admin.customer.index');
    }

    public function getDatatableData()
    {
        return $this->customerRepo->getDatatableData();
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:15',
        ]);

        $this->customerRepo->create($validated);

        return redirect()->route('admin.customer.index')->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = $this->customerRepo->find($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:15',
        ]);

        $this->customerRepo->update($id, $validated);

        return redirect()->route('admin.customer.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $this->customerRepo->delete($id);

        return response()->json(['message' => 'Customer deleted successfully.']);
    }

    public function toggleStatus($id)
    {
        $customer = $this->customerRepo->toggleStatus($id);

        return response()->json(['status' => $customer->status, 'message' => 'Status updated.']);
    }

    public function bulkDelete(Request $request)
    {
        $this->customerRepo->bulkDelete($request->ids);
        return response()->json(['message' => 'Selected customers deleted.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->customerRepo->bulkStatus($request->ids, $request->status);
        return response()->json(['message' => 'Status updated for selected customers.']);
    }
}

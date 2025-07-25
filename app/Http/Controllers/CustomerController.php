<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use App\Repositories\Customer\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            return $this->customerRepo->getDatatableData();
        }

        return abort(403);
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('customers', $filename, 'public');
        }

        $this->customerRepo->create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

     /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $customer = $this->customerRepo->findBySlug($slug);

        if (!$customer) {
            return redirect()->route('customers.index')->with('error', 'Customer not found.');
        }

        return view('admin.customer.show', compact('customer'));
    }

    public function edit($slug)
    {
        $user = $this->customerRepo->findBySlug($slug);
        
        if (!$user) {
            return redirect()->route('customers.index')->with('error', 'Customer not found');
        }
        return view('admin.customer.edit', compact('user'));
    }

    public function update(UpdateCustomerRequest $request, $id = null)
    {
        $validated = $request->validated();

         $customer = $this->customerRepo->find($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($customer->image && Storage::disk('public')->exists($customer->image)) {
                Storage::disk('public')->delete($customer->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('customers', $filename, 'public');
        }

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

         return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $customer->status,
            'badge' => $customer->status_badge,
        ]);
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

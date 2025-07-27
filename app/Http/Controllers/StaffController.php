<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\StoreStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Interfaces\StaffRepositoryInterface;
use App\Models\PaymentMethod;
use App\Models\Shift;
use App\Models\Staff;
use App\Models\User;
use App\Repositories\Staff\StaffRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    protected $staffRepo;

    public function __construct(StaffRepositoryInterface $staffRepo)
    {
        $this->staffRepo = $staffRepo;
    }

    // ✅ Show the datatable via AJAX
    public function datatable(Request $request)
    {
        return $this->staffRepo->getDatatableData();
    }

    // ✅ Show all staff
    public function index()
    {
        $staff = $this->staffRepo->all();
        return view('admin.staff.index', compact('staff'));
    }

    // ✅ Show create form
    public function create()
    {
        $shifts =  Shift::get(); // get all active records
        $payment_methods = PaymentMethod::get(); // get active payment methods
        $staff_roles = Role::get(); //get active roles

        return view('admin.staff.create', compact('shifts', 'payment_methods', 'staff_roles'));
    }

    // ✅ Store new staff
    public function store(StoreStaffRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('staff', $filename, 'public');
        }

        $this->staffRepo->create($validated);
        return redirect()->route('staff.index')->with('success', 'Staff created successfully.');
    }

    public function show($slug)
    {
        $user = $this->staffRepo->findBySlug($slug);

        if (!$user) {
            return redirect()->route('staff.index')->with('error', 'Staff not found.');
        }

        // return $user;

        return view('admin.staff.show', compact('user'));
    }

    // ✅ Show edit form
    public function edit($slug)
    {
        $user = $this->staffRepo->findBySlug($slug);

        if (!$user) {
            return redirect()->route('staff.index')->with('error', 'Staff not found');
        }

        $shifts =  Shift::get(); // get all active records
        $payment_methods = PaymentMethod::get(); // get active payment methods
        $staff_roles = Role::get(); //get active roles

        // return $user->staff;

        return view('admin.staff.edit', compact('user', 'shifts', 'payment_methods', 'staff_roles'));
        // return view('admin.staff.create', compact('user','shifts', 'payment_methods', 'staff_roles'));
    }

    // ✅ Update staff
    public function update(UpdateStaffRequest $request, $id = null)
    {
        $validated = $request->validated();

        $staff = $this->staffRepo->find($id);
        // dd($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($staff->image && Storage::disk('public')->exists($staff->image)) {
                Storage::disk('public')->delete($staff->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('staff', $filename, 'public');
        }

        $this->staffRepo->update($id, $validated);
        return redirect()->route('staff.index')->with('success', 'Staff updated successfully.');
    }

    // ✅ Delete single staff
    public function destroy($id)
    {
        $this->staffRepo->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Staff deleted successfully.',
        ]);
    }

    // ✅ Toggle status (active/inactive)
    public function toggleStatus($id)
    {
        $staff = $this->staffRepo->toggleStatus($id);
        return response()->json(['status' => $staff->status]);
    }

    // ✅ Bulk delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $this->staffRepo->bulkDelete($ids);
        return response()->json(['message' => 'Selected staff deleted successfully.']);
    }

    // ✅ Bulk update status
    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        $this->staffRepo->bulkStatus($ids, $status);
        return response()->json(['message' => 'Status updated successfully.']);
    }
}

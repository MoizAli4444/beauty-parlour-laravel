<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use App\Models\Expense;
use App\Repositories\Expense\ExpenseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    protected $repository;

    public function __construct(ExpenseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['expense_type', 'payment_method', 'date_from', 'date_to']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403, 'Unauthorized action.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.expenses.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $validated = $request->validated();

        // Handle receipt upload if present
        if ($request->hasFile('receipt_file')) {
            $file = $request->file('receipt_file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['receipt_path'] = $file->storeAs('expenses', $filename, 'public');
        }

        $this->repository->create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $expense = $this->repository->find($id);

        if (!$expense) {
            return redirect()->route('expenses.index')->with('error', 'Expense record not found.');
        }

        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, $id = null)
    {
        $validated = $request->validated();

        $expense = $this->repository->find($id);

        // Handle receipt upload if present
        if ($request->hasFile('receipt_file')) {
            // Delete old receipt if it exists
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            $file = $request->file('receipt_file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['receipt_path'] = $file->storeAs('expenses', $filename, 'public');
        }

        $this->repository->update($id, $validated);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Expense deleted successfully.',
        ]);
    }


    public function bulkDelete(Request $request)
    {
        $this->repository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected expenses deleted successfully.']);
    }
}

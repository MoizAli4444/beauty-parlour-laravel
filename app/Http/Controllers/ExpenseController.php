<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Repositories\Expense\ExpenseRepositoryInterface;
use Illuminate\Http\Request;

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
            return $this->repository->getDatatableData();
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.expense.index');
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
    public function show(Expense $expense)
    {
        //
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
    public function destroy(Expense $expense)
    {
        //
    }
}

<?php

namespace App\Repositories\Expense;

use App\Models\Expense;

use App\Repositories\Expense\ExpenseRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;

class ExpenseRepository implements ExpenseRepositoryInterface
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    use TracksUser;

    public function getDatatableData(array $filters)
    {
        try {
            $query = Expense::with('moderator')->latest();

            // ✅ Filters
            if (!empty($filters['expense_type'])) {
                $query->where('expense_type', 'like', '%' . $filters['expense_type'] . '%');
            }

            if (!empty($filters['payment_method'])) {
                $query->where('payment_method', $filters['payment_method']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('expense_type', fn($row) => e($row->expense_type))

                ->addColumn('amount', fn($row) => number_format($row->amount, 2) . ' PKR')

                ->addColumn('payment_method', fn($row) => ucfirst($row->payment_method))

                ->addColumn('moderator', function ($row) {
                    if ($row->moderator) {
                        return class_basename($row->moderator_type) . ' - ' . ($row->moderator->name ?? 'N/A');
                    }
                    return 'N/A';
                })

                ->addColumn('receipt', function ($row) {
                    if (!$row->receipt_path) {
                        return 'N/A';
                    }

                    if (Storage::disk('public')->exists($row->receipt_path)) {
                        $url = asset('storage/' . $row->receipt_path);
                        return '<a href="' . $url . '" target="_blank">View</a>';
                    }

                    return 'N/A';
                })


                ->addColumn('notes', function ($row) {
                    return strlen($row->notes) > 50
                        ? substr($row->notes, 0, 50) . '...'
                        : ($row->notes ?? 'N/A');
                })

                ->editColumn(
                    'date',
                    fn($row) =>
                    $row->date ? \Carbon\Carbon::parse($row->date)->format('d M Y') : 'N/A'
                )

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.expenses.action', ['expense' => $row])->render()
                )

                    ->rawColumns(['checkbox', 'receipt', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return Expense::latest()->get();
    }

    public function find($id)
    {
        return Expense::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Expense::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update($id, array $data)
    {
        $expense = Expense::findOrFail($id);

        $expense->update($data);

        return $expense;
    }


    public function delete($id)
    {
        $expense = Expense::findOrFail($id);

        // Delete receipt if exists
        if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        return $expense->delete(); // soft delete
    }


    public function bulkDelete(array $ids)
    {
        $expenses = Expense::whereIn('id', $ids)->get();

        foreach ($expenses as $expense) {
            // Delete receipt file if it exists
            if ($expense->receipt_path && Storage::disk('public')->exists($expense->receipt_path)) {
                Storage::disk('public')->delete($expense->receipt_path);
            }

            // Soft delete the record
            $expense->delete();
        }

        return true;
    }
}

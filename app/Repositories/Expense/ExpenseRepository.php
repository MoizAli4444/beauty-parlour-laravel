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

            if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                $query->whereBetween('date', [$filters['date_from'], $filters['date_to']]);
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

                ->rawColumns(['checkbox', 'action'])
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
        $testimonial = Expense::findOrFail($id);

        // Track who updated
        $data = $this->addUpdatedBy($data);

        // If a new file is uploaded (extra file handling if needed)
        if (isset($data['file'])) {
            $file = $data['file'];

            // Store file in public/uploads/testimonials
            $path = $file->store('uploads/testimonials', 'public');

            $data['file_path']  = $path;
            $data['file_size']  = $file->getSize();
            $data['media_type'] = str_starts_with($file->getMimeType(), 'video')
                ? 'video'
                : 'image';

            unset($data['file']); // avoid saving raw file object
        }

        $testimonial->update($data);

        return $testimonial;
    }


    public function delete($id)
    {
        $testimonial = Expense::findOrFail($id);

        if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
            Storage::disk('public')->delete($testimonial->image);
        }

        return $testimonial->delete(); // uses softDeletes
    }


    public function toggleStatus($id)
    {
        $testimonial = Expense::findOrFail($id);

        switch ($testimonial->status) {
            case Expense::STATUS_ACTIVE:
                $testimonial->status = Expense::STATUS_INACTIVE;
                break;

            case Expense::STATUS_INACTIVE:
                $testimonial->status = Expense::STATUS_ACTIVE;
                break;

            case Expense::STATUS_PENDING:
            default:
                $testimonial->status = Expense::STATUS_ACTIVE; // promote pending to active by default
                break;
        }

        $testimonial->save();

        return $testimonial;
    }


    public function toggleFeatured($id)
    {
        $testimonial = Expense::findOrFail($id);
        $testimonial->featured = !$testimonial->featured;
        $testimonial->save();

        return $testimonial;
    }



    public function bulkDelete(array $ids)
    {
        $testimonials = Expense::whereIn('id', $ids)->get();

        foreach ($testimonials as $testimonial) {
            if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $testimonial->delete();
        }

        return true;
    }


    public function bulkStatus(array $ids, string $status)
    {
        return Expense::whereIn('id', $ids)->update(['status' => $status]);
    }
}

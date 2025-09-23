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
            $query = Expense::latest();

            // ✅ Filters
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['name'])) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('name', fn($row) => e($row->name))

                ->addColumn('designation', fn($row) => $row->designation ?? 'N/A')

                ->addColumn('testimonial', function ($row) {
                    return strlen($row->testimonial) > 80 ? substr($row->testimonial, 0, 80) . '...' : $row->testimonial;
                })

                ->addColumn('image', function ($row) {
                    if (!$row->image) {
                        // Case 1: No image uploaded
                        return 'N/A';
                    }

                    if (Storage::disk('public')->exists($row->image)) {
                        // Case 2: Uploaded image exists
                        $image = asset('storage/' . $row->image);
                    } else {
                        // Case 3: Uploaded but missing → fallback image
                        $image = asset('storage/default.png'); // put your placeholder here
                    }

                    return '<img src="' . $image . '" 
                        class="img-thumbnail js-media-preview" 
                        style="max-width: 60px; cursor:pointer;"
                        data-url="' . $image . '" 
                        data-type="image">';
                    })


                ->editColumn('status', function ($row) {
                    return $row->status_badge; // accessor on model
                })

                ->editColumn(
                    'created_at',
                    fn($row) => $row->created_at->format('d M Y')
                )

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.testimonials.action', ['testimonial' => $row])->render()
                )

                ->rawColumns(['checkbox', 'image', 'status', 'action'])
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

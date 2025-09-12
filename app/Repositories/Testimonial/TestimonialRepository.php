<?php

namespace App\Repositories\Testimonial;

use App\Models\Testimonial;

use App\Repositories\Testimonial\TestimonialRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;

class TestimonialRepository implements TestimonialRepositoryInterface
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
            $query = Testimonial::latest();

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
                    if ($row->image) {
                        return '<img src="' . asset('storage/' . $row->image) . '" 
                            class="img-thumbnail js-media-preview" 
                            style="max-width: 60px; cursor:pointer;"
                            data-url="' . asset('storage/' . $row->image) . '" 
                            data-type="image">';
                    }
                    return 'N/A';
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
        return Testimonial::latest()->get();
    }

    public function find($id)
    {
        return Testimonial::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Testimonial::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        return Testimonial::create($data);
    }



    public function update($id, array $data)
    {
        $testimonial = Testimonial::findOrFail($id);

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
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
            Storage::disk('public')->delete($testimonial->image);
        }

        return $testimonial->delete(); // uses softDeletes
    }


    public function toggleStatus($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        switch ($testimonial->status) {
            case Testimonial::STATUS_ACTIVE:
                $testimonial->status = Testimonial::STATUS_INACTIVE;
                break;

            case Testimonial::STATUS_INACTIVE:
                $testimonial->status = Testimonial::STATUS_ACTIVE;
                break;

            case Testimonial::STATUS_PENDING:
            default:
                $testimonial->status = Testimonial::STATUS_ACTIVE; // promote pending to active by default
                break;
        }

        $testimonial->save();

        return $testimonial;
    }


    public function toggleFeatured($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->featured = !$testimonial->featured;
        $testimonial->save();

        return $testimonial;
    }



    public function bulkDelete(array $ids)
    {
        $testimonials = Testimonial::whereIn('id', $ids)->get();

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
        return Testimonial::whereIn('id', $ids)->update(['status' => $status]);
    }
}

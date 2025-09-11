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
            $query = Testimonial::with('service')->latest();

            // ✅ Filters
            if (!empty($filters['service_id'])) {
                $query->where('service_id', $filters['service_id']);
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['media_type'])) {
                $query->where('media_type', $filters['media_type']);
            }

            if (!empty($filters['featured'])) {
                $query->where('featured', $filters['featured']);
            }

            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn(
                    'service',
                    fn($row) =>
                    $row->service ? $row->service->name : 'N/A'
                )

                ->addColumn('title', fn($row) => $row->title ?? 'N/A')



                ->addColumn('media_preview', function ($row) {
                    if ($row->media_type === 'image') {
                        return '<i class="bi bi-image text-primary fs-3 js-media-preview"
                    style="cursor:pointer"
                    data-url="' . asset('storage/' . $row->file_path) . '" 
                    data-type="image"></i>';
                    }

                    if ($row->media_type === 'video') {
                        return '<i class="bi bi-play-btn text-danger fs-3 js-media-preview"
                    style="cursor:pointer"
                    data-url="' . asset('storage/' . $row->file_path) . '" 
                    data-type="video"></i>';
                    }

                    return 'N/A';
                })

                ->addColumn('file_size', function ($row) {
                    if (!is_numeric($row->file_size)) {
                        return 'N/A';
                    }

                    $sizeKB = $row->file_size / 1024;

                    if ($sizeKB < 1024) {
                        return number_format($sizeKB, 2) . ' KB';
                    } else {
                        $sizeMB = $sizeKB / 1024;
                        return number_format($sizeMB, 2) . ' MB';
                    }
                })


                ->editColumn('status', function ($row) {
                    // dd($row->status);
                    return $row->status_badge; // uses model accessor
                })


                ->editColumn('featured', function ($row) {
                    // dd($row->status);
                    return $row->featured_badge; // uses model accessor
                })


                ->editColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at->format('d M Y')
                )

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.galleries.action', ['testimonial' => $row])->render()
                )

                ->rawColumns(['checkbox', 'media_preview', 'status', 'featured', 'file_size', 'action'])
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
        $data = $this->addCreatedBy($data);


        if (isset($data['file'])) {
            $file = $data['file'];

            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['file_path'] = $file->storeAs('uploads/testimonial', $filename, 'public');
            $data['file_size'] = $file->getSize();
            // ✅ Detect file type automatically
            $mimeType = $file->getMimeType(); // e.g. image/jpeg, video/mp4
            if (str_starts_with($mimeType, 'image/')) {
                $data['media_type'] = 'image';
            } elseif (str_starts_with($mimeType, 'video/')) {
                $data['media_type'] = 'video';
            } else {
                $data['media_type'] = 'other'; // fallback
            }

            unset($data['file']); // don’t try to insert file object into DB
        }

        return Testimonial::create($data);
    }


    public function update($id, array $data)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Track who updated
        $data = $this->addUpdatedBy($data);

        // If a new file is uploaded
        if (isset($data['file'])) {
            $file = $data['file'];

            // Store file in public/uploads/testimonial
            $path = $file->store('uploads/testimonial', 'public');

            // Update fields
            $data['file_path']  = $path;
            $data['file_size']  = $file->getSize();
            $data['media_type'] = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';

            unset($data['file']); // don’t try to save file object into DB
        }

        $testimonial->update($data);

        return $testimonial;
    }


    public function delete($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->file_path && Storage::disk('public')->exists($testimonial->file_path)) {
            Storage::disk('public')->delete($testimonial->file_path);
        }

        return $testimonial->delete(); // uses softDeletes
    }


    public function toggleStatus($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->status = $testimonial->status === Testimonial::STATUS_ACTIVE
            ? Testimonial::STATUS_INACTIVE
            : Testimonial::STATUS_ACTIVE;

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
        $galleries = Testimonial::whereIn('id', $ids)->get();

        foreach ($galleries as $testimonial) {
            if ($testimonial->file_path && Storage::disk('public')->exists($testimonial->file_path)) {
                Storage::disk('public')->delete($testimonial->file_path);
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

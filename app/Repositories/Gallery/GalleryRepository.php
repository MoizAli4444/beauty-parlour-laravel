<?php

namespace App\Repositories\Gallery;

use App\Models\Gallery;

use App\Repositories\Gallery\GalleryRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;

class GalleryRepository implements GalleryRepositoryInterface
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
            $query = Gallery::with('service')->latest();

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

                // ->addColumn(
                //     'file_size',
                //     fn($row) =>
                //     $row->file_size ? number_format($row->file_size / 1024, 2) . ' KB' : 'N/A'
                // )

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
                    view('admin.galleries.action', ['gallery' => $row])->render()
                )

                ->rawColumns(['checkbox', 'media_preview', 'status', 'featured', 'file_size', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        return Gallery::latest()->get();
    }

    public function find($id)
    {
        return Gallery::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Gallery::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);


        if (isset($data['file'])) {
            $file = $data['file'];

            $filename = time() . '.' . $file->getClientOriginalExtension();
            $data['file_path'] = $file->storeAs('uploads/gallery', $filename, 'public');
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

        return Gallery::create($data);
    }


    public function update($id, array $data)
    {
        $gallery = Gallery::findOrFail($id);

        // Track who updated
        $data = $this->addUpdatedBy($data);

        // If a new file is uploaded
        if (isset($data['file'])) {
            $file = $data['file'];

            // Store file in public/uploads/gallery
            $path = $file->store('uploads/gallery', 'public');

            // Update fields
            $data['file_path']  = $path;
            $data['file_size']  = $file->getSize();
            $data['media_type'] = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';

            unset($data['file']); // don’t try to save file object into DB
        }

        $gallery->update($data);

        return $gallery;
    }


    public function delete($id)
    {
        $gallery = Gallery::findOrFail($id);

        if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        return $gallery->delete(); // uses softDeletes
    }


    public function toggleStatus($id)
    {
        $gallery = Gallery::findOrFail($id);

        $gallery->status = $gallery->status === Gallery::STATUS_ACTIVE
            ? Gallery::STATUS_INACTIVE
            : Gallery::STATUS_ACTIVE;

        $gallery->save();

        return $gallery;
    }

    public function toggleFeatured($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->featured = !$gallery->featured;
        $gallery->save();

        return $gallery;
    }



    public function bulkDelete(array $ids)
    {
        $galleries = Gallery::whereIn('id', $ids)->get();

        foreach ($galleries as $gallery) {
            if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                Storage::disk('public')->delete($gallery->file_path);
            }
            $gallery->delete();
        }

        return true;
    }


    public function bulkStatus(array $ids, string $status)
    {
        return Gallery::whereIn('id', $ids)->update(['status' => $status]);
    }
}

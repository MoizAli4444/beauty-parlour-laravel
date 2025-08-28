<?php

namespace App\Repositories\Gallery;

use App\Models\Gallery;

use App\Repositories\Gallery\GalleryRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;

class GalleryRepository implements GalleryRepositoryInterface
{
    //  public function store(array $data)
    // {
    //     if (isset($data['file'])) {
    //         $file = $data['file'];
    //         $path = $file->store('uploads/gallery', 'public');

    //         $data['file_path'] = $path;
    //         $data['file_size'] = $file->getSize();
    //         unset($data['file']); // don’t try to insert file object into DB
    //     }

    //     return $this->repo->create($data);
    // }

    // public function update($id, array $data)
    // {
    //     $gallery = $this->repo->find($id);

    //     if (isset($data['file'])) {
    //         // Delete old file if exists
    //         if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
    //             Storage::disk('public')->delete($gallery->file_path);
    //         }

    //         $file = $data['file'];
    //         $path = $file->store('uploads/gallery', 'public');

    //         $data['file_path'] = $path;
    //         $data['file_size'] = $file->getSize();
    //         unset($data['file']);
    //     }

    //     return $this->repo->update($gallery, $data);
    // }

    // public function destroy($id)
    // {
    //     $gallery = $this->repo->find($id);

    //     if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
    //         Storage::disk('public')->delete($gallery->file_path);
    //     }

    //     return $this->repo->delete($gallery);
    // }

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
                        return '<img src="' . asset('storage/' . $row->file_path) . '" width="60" height="60" class="rounded">';
                    }
                    if ($row->media_type === 'video') {
                        return '<video width="80" height="60" controls>
                                <source src="' . asset('storage/' . $row->file_path) . '" type="video/mp4">
                            </video>';
                    }
                    return 'N/A';
                })

                ->addColumn(
                    'file_size',
                    fn($row) =>
                    $row->file_size ? number_format($row->file_size / 1024, 2) . ' KB' : 'N/A'
                )

                ->editColumn('status', function ($row) {
                    $color = $row->status === 'active' ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($row->status) . '</span>';
                })

                ->addColumn(
                    'featured',
                    fn($row) =>
                    $row->featured ? '<span class="badge bg-info">Yes</span>' : 'No'
                )

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

                ->rawColumns(['checkbox', 'media_preview', 'status', 'featured', 'action'])
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
            $path = $file->store('uploads/gallery', 'public');

            $data['file_path'] = $path;
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
        $addon = Gallery::findOrFail($id);
        $addon->status = $addon->status === 'active' ? 'inactive' : 'active';
        $addon->save();

        return $addon;
    }

    public function bulkDelete(array $ids)
    {
        return Gallery::whereIn('id', $ids)->delete();
    }

    public function bulkStatus(array $ids, string $status)
    {
        return Gallery::whereIn('id', $ids)->update(['status' => $status]);
    }
}

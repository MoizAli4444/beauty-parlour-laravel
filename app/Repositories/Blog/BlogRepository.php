<?php

namespace App\Repositories\Blog;

use App\Models\BlogPost;
use App\Repositories\Blog\BlogRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\TracksUser;
use Illuminate\Support\Facades\Storage;

class BlogRepository implements BlogRepositoryInterface
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
            $query = BlogPost::with(['author:id,name', 'service:id,name'])->latest();

            // ✅ Filters
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['title'])) {
                $query->where('title', 'like', '%' . $filters['title'] . '%');
            }

            if (!empty($filters['service_id'])) {
                $query->where('service_id', $filters['service_id']);
            }

            // ✅ DataTable response
            return DataTables::of($query)

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('id', fn($row) => $row->id)

                ->addColumn('title', fn($row) => e($row->title))

                ->addColumn('excerpt', function ($row) {
                    return strlen($row->excerpt) > 80
                        ? substr($row->excerpt, 0, 80) . '...'
                        : ($row->excerpt ?? 'N/A');
                })

                ->addColumn('author', fn($row) => $row->author->name ?? 'N/A')

                ->addColumn('service', fn($row) => $row->service->name ?? 'N/A')

                ->addColumn('image', function ($row) {
                    if (!$row->image) {
                        return 'N/A';
                    }

                    if (Storage::disk('public')->exists($row->image)) {
                        $image = asset('storage/' . $row->image);
                    } else {
                        $image = asset('storage/default.png');
                    }

                    return '<img src="' . $image . '" 
                    class="img-thumbnail js-media-preview" 
                    style="max-width: 60px; cursor:pointer;"
                    data-url="' . $image . '" 
                    data-type="image">';
                })

                ->editColumn('status', fn($row) => $row->status_badge) // accessor on model

                ->editColumn(
                    'published_at',
                    fn($row) =>
                    $row->published_at ? $row->published_at->format('d M Y H:i') : 'N/A'
                )

                ->editColumn('views', fn($row) => number_format($row->views))

                ->editColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at->format('d M Y')
                )

                ->addColumn(
                    'action',
                    fn($row) =>
                    view('admin.blogs.action', ['blog' => $row])->render()
                )

                ->rawColumns(['checkbox', 'image', 'status', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function all()
    {
        return BlogPost::latest()->get();
    }

    public function find($id)
    {
        return BlogPost::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return BlogPost::where('slug', $slug)->first();
    }


    public function create(array $data)
    {
        $data = $this->addCreatedBy($data);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['image']->store('uploads/blogs', 'public');
            $data['image'] = $path;
        }

        if (($data['status'] ?? null) === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return BlogPost::create($data);
    }



    public function update($id, array $data)
    {
        $blog = BlogPost::findOrFail($id);

        $data = $this->addUpdatedBy($data);

        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $path = $data['image']->store('uploads/blogs', 'public');
            $data['image'] = $path;
        }

        if (($data['status'] ?? null) === 'published' && empty($blog->published_at)) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return $blog;
    }




    // public function delete($id)
    // {
    //     $testimonial = BlogPost::findOrFail($id);

    //     if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
    //         Storage::disk('public')->delete($testimonial->image);
    //     }

    //     return $testimonial->delete(); // uses softDeletes
    // }

    public function delete($id)
    {
        $blog = BlogPost::findOrFail($id);

        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        return $blog->delete();
    }


    public function toggleStatus($id)
    {
        $blog = BlogPost::findOrFail($id);

        $blog->status = $blog->status === BlogPost::STATUS_PUBLISHED
            ? BlogPost::STATUS_DRAFT
            : BlogPost::STATUS_PUBLISHED;

        if ($blog->status === BlogPost::STATUS_PUBLISHED && empty($blog->published_at)) {
            $blog->published_at = now();
        }

        $blog->save();

        return $blog;
    }


    public function bulkDelete(array $ids)
    {
        $blogs = BlogPost::whereIn('id', $ids)->get();

        foreach ($blogs as $blog) {
            // Delete image if exists
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $blog->delete();
        }

        return true;
    }


    public function bulkStatus(array $ids, string $status)
    {
        return BlogPost::whereIn('id', $ids)->update(['status' => $status]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Blog\StoreBlogPostRequest;
use App\Http\Requests\Blog\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Repositories\Blog\BlogRepositoryInterface;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    protected $repository;

    public function __construct(BlogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['status', 'title', 'service_id']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blogs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogPostRequest $request)
    {
        $validated = $request->validated();

        $this->repository->create($validated);

        return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog = $this->repository->findBySlug($slug);

        if (!$blog) {
            return redirect()->route('blogs.index')->with('error', 'Blog not found.');
        }

        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $blog = $this->repository->findBySlug($slug);

        if (!$blog) {
            return redirect()->route('blogs.index')->with('error', 'blog not found');
        }

        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogPostRequest $request, $id)
    {
        $validated = $request->validated();
        $this->repository->update($id, $validated);

        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $testimonial = $this->repository->toggleStatus($id);

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $testimonial->status,
            'badge' => $testimonial->status_badge,
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $this->repository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected blogs deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->repository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

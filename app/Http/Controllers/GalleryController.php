<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gallery\StoreGalleryRequest;
use App\Models\Gallery;
use App\Models\ServiceVariant;
use Illuminate\Http\Request;
use App\Repositories\Gallery\GalleryRepositoryInterface;

class GalleryController extends Controller
{
    protected $repository;

    public function __construct(GalleryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            $filters = $request->only(['service_id', 'status', 'media_type', 'featured']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.galleries.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = ServiceVariant::active()->get();
        return view('admin.galleries.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file');
        }

        $this->repository->create($validated);

        return redirect()->route('galleries.index')->with('success', 'Gallery created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $gallery = $this->repository->findBySlug($slug);

        if (!$gallery) {
            return redirect()->route('galleries.index')->with('error', 'Gallery record not found.');
        }

        return view('admin.galleries.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $gallery = $this->repository->findBySlug($slug);

        if (!$gallery) {
            return redirect()->route('galleries.index')->with('error', 'Gallery not found');
        }

        $services = ServiceVariant::active()->get(); // assuming you have an `active()` scope

        return view('admin.galleries.edit', compact('gallery', 'services'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $offer = $this->repository->toggleStatus($id);

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $offer->status,
            'badge' => $offer->status_badge,
        ]);
    }

    public function toggleFeatured($id)
    {
        $gallery = $this->repository->toggleFeatured($id);

        return response()->json([
            'status' => true,
            'message' => 'Featured status updated successfully.',
            'new_featured' => $gallery->featured,
            'badge' => $gallery->featured_badge, // 👈 accessor
        ]);
    }


    public function bulkDelete(Request $request)
    {
        $this->repository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected offers deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->repository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

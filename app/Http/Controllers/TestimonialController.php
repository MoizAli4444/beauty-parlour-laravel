<?php

namespace App\Http\Controllers;

use App\Http\Requests\Testimonials\StoreTestimonialRequest;
use App\Http\Requests\Testimonials\UpdateTestimonialRequest;
use App\Models\Testimonial;
use App\Repositories\Testimonial\TestimonialRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    protected $repository;

    public function __construct(TestimonialRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['expense_type', 'payment_method', 'date_from', 'date_to']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403, 'Unauthorized action.');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.testimonials.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        $validated = $request->validated();

        // Handle image upload if present
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('testimonials', $filename, 'public');
        }

        $this->repository->create($validated);

        return redirect()->route('testimonials.index')
            ->with('success', 'Testimonial created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $testimonial = $this->repository->find($id);

        if (!$testimonial) {
            return redirect()->route('testimonials.index')->with('error', 'Testimonial not found.');
        }

        return view('admin.testimonials.show', compact('testimonial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $testimonial = $this->repository->find($id);

        if (!$testimonial) {
            return redirect()->route('testimonials.index')->with('error', 'testimonial not found');
        }

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestimonialRequest $request, $id = null)
    {
        $validated = $request->validated();

        $testimonial = $this->repository->find($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($testimonial->image && Storage::disk('public')->exists($testimonial->image)) {
                Storage::disk('public')->delete($testimonial->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('testimonials', $filename, 'public');
        }

        $this->repository->update($id, $validated);

        return redirect()
            ->route('testimonials.index')
            ->with('success', 'Testimonial updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Addon deleted successfully.',
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

        return response()->json(['message' => 'Selected addons deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->repository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

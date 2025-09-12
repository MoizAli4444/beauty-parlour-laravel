<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Repositories\Testimonial\TestimonialRepositoryInterface;
use Illuminate\Http\Request;

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
            $filters = $request->only(['status', 'name']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403);
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
    public function store(StoreAddonRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('addons', $filename, 'public');
        }

        $this->repository->create($validated);

        return redirect()->route('addons.index')->with('success', 'Addon created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $addon = $this->addonRepository->findBySlug($slug);

        if (!$addon) {
            return redirect()->route('addons.index')->with('error', 'Addon not found.');
        }

        return view('admin.addon.show', compact('addon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $addon = $this->addonRepository->findBySlug($slug);

        if (!$addon) {
            return redirect()->route('addons.index')->with('error', 'Addon not found');
        }

        return view('admin.addon.edit', compact('addon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddonRequest $request, $id = null)
    {
        $validated = $request->validated();

        $addon = $this->addonRepository->find($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($addon->image && Storage::disk('public')->exists($addon->image)) {
                Storage::disk('public')->delete($addon->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('addons', $filename, 'public');
        }

        $this->addonRepository->update($id, $validated);

        return redirect()->route('addons.index')->with('success', 'Addon updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->addonRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Addon deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $addon = $this->addonRepository->toggleStatus($id);

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $addon->status,
            'badge' => $addon->status_badge,
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

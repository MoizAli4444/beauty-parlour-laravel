<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            return $this->serviceRepository->getDatatableData();
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.service.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.service.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('services', $filename, 'public');
        }

        $this->serviceRepository->create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $service = $this->serviceRepository->findBySlug($slug);

        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Service not found.');
        }

        return view('admin.service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $service = $this->serviceRepository->findBySlug($slug);

        if (!$service) {
            return redirect()->route('services.index')->with('error', 'Service not found');
        }

        return view('admin.service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateServiceRequest $request, $id = null)
    {
        $validated = $request->validated();

        $service = $this->serviceRepository->find($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('services', $filename, 'public');
        }

        $this->serviceRepository->update($id, $validated);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->serviceRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $service = Service::findOrFail($id);

        $service->status = $service->status === 'active' ? 'inactive' : 'active';
        $service->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $service->status,
            'badge' => $service->status_badge
        ]);
    }
}

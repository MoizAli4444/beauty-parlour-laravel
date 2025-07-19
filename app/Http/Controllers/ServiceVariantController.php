<?php

namespace App\Http\Controllers;

use App\Interfaces\ServiceVariantRepositoryInterface;
use App\Models\Service;
use App\Models\ServiceVariant;
use Illuminate\Http\Request;

class ServiceVariantController extends Controller
{
    protected $serviceVariantRepo;

    public function __construct(ServiceVariantRepositoryInterface $serviceVariantRepo)
    {
        $this->serviceVariantRepo = $serviceVariantRepo;
    }


    public function datatable(Request $request)
    {
        // dd("call");
        if ($request->ajax()) {
            return $this->serviceVariantRepo->getDatatableData();
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.service-variants.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::get();
        return view('admin.service-variants.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceVariant $serviceVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceVariant $serviceVariant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceVariant $serviceVariant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceVariant $serviceVariant)
    {
        //
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

    public function bulkDelete(Request $request)
    {
        Service::whereIn('id', $request->ids)->delete();
        return response()->json(['message' => 'Selected services deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        Service::whereIn('id', $request->ids)->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated']);
    }
}

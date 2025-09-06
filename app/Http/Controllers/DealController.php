<?php

namespace App\Http\Controllers;

use App\Http\Requests\Deal\StoreDealRequest;
use App\Models\Deal;
use App\Models\ServiceVariant;
use App\Repositories\Deal\DealRepositoryInterface;
use Illuminate\Http\Request;

class DealController extends Controller
{
    protected $repository;

    public function __construct(DealRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['status', 'start_date', 'end_date']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403, 'Unauthorized action.');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.deals.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = ServiceVariant::active()->get();
        return view('admin.deals.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDealRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file');
        }

        $this->repository->create($validated);

        return redirect()->route('deals.index')->with('success', 'Deal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $deal = $this->repository->findBySlug($slug);

        if (!$deal) {
            return redirect()->route('deals.index')->with('error', 'Deal record not found.');
        }

        return view('admin.deals.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deal $deal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deal $deal)
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
            'message' => 'Deal deleted successfully.',
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


    public function bulkDelete(Request $request)
    {
        $this->repository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected deals deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->repository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

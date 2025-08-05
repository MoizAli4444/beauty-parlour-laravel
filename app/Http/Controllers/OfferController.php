<?php

namespace App\Http\Controllers;

use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Interfaces\OfferRepositoryInterface;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    protected $offerRepository;

    public function __construct(OfferRepositoryInterface $offerRepository)
    {
        $this->offerRepository = $offerRepository;
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            return $this->offerRepository->getDatatableData();
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.offer.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.offer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('offers', $filename, 'public');
        }

        $this->offerRepository->create($validated);

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $offer = $this->offerRepository->findBySlug($slug);

        if (!$offer) {
            return redirect()->route('offers.index')->with('error', 'Offer not found.');
        }

        return view('admin.offer.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($slug)
    {
        $offer = $this->offerRepository->findBySlug($slug);

        if (!$offer) {
            return redirect()->route('offers.index')->with('error', 'Offer not found');
        }

        return view('admin.offer.edit', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(UpdateOfferRequest $request, $id = null)
    {
        $validated = $request->validated();

        $offer = $this->offerRepository->find($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($offer->image && Storage::disk('public')->exists($offer->image)) {
                Storage::disk('public')->delete($offer->image);
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $validated['image'] = $file->storeAs('offers', $filename, 'public');
        }

        $this->offerRepository->update($id, $validated);

        return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy($id)
    {
        $this->offerRepository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Offer deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $offer = $this->offerRepository->toggleStatus($id);

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $offer->status,
            'badge' => $offer->status_badge,
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $this->offerRepository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected offers deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->offerRepository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

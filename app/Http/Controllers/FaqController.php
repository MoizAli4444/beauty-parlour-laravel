<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use App\Repositories\Faq\FaqRepositoryInterface;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    protected $repository;

    public function __construct(FaqRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            return $this->repository->getDatatableData();
        }

        return abort(403);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.faqs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
        $validated = $request->validated();

        $this->repository->create($validated);

        return redirect()->route('faqs.index')->with('success', 'Faq created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $faq = $this->repository->findBySlug($slug);

        if (!$faq) {
            return redirect()->route('faqs.index')->with('error', 'Faq not found');
        }

        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, $id)
    {
        
        // Find the FAQ in the repository
        $faq = $this->repository->find($id);
        
        if (!$faq) {
            return redirect()->route('faqs.index')->with('error', 'FAQ not found.');
        }
        
        // Validate input via FaqRequest
        $validated = $request->validated();

        // Update the FAQ
        $this->repository->update($id, $validated);

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json([
            'status' => true,
            'message' => 'Faq deleted successfully.',
        ]);
    }

    public function toggleStatus($id)
    {
        $faq = $this->repository->toggleStatus($id);

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.',
            'new_status' => $faq->status,
            'badge' => $faq->status_badge,
        ]);
    }


    public function bulkDelete(Request $request)
    {
        $this->repository->bulkDelete($request->ids);

        return response()->json(['message' => 'Selected faqs deleted successfully.']);
    }

    public function bulkStatus(Request $request)
    {
        $this->repository->bulkStatus($request->ids, $request->status);

        return response()->json(['message' => 'Status updated']);
    }
}

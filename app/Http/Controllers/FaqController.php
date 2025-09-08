<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        //
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
    public function edit(Faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        //
    }
}

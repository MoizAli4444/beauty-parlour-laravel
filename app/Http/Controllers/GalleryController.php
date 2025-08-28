<?php

namespace App\Http\Controllers;

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
        return view('admin.galleries.create',compact('services'));
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
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
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
    public function destroy(Gallery $gallery)
    {
        //
    }
}

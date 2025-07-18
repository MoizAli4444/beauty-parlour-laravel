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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.service-variant.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::get();
        return view('admin.service-variant.create',compact('services'));
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
}

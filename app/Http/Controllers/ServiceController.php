<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Interfaces\ServiceRepositoryInterface;

class ServiceController extends Controller
{
    protected $serviceRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = $this->serviceRepository->all();
        return $services;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.service.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateServiceRequest $request)
    {

        // $this->serviceRepository->create($request->all());
        // return redirect()->route('services.index')->with('success', 'Service created!');


        //
        // dd($request);
        $validated = $request->validated();

        // handle file upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        // Service::create($validated);
        $this->serviceRepository->create($validated);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(CreateServiceRequest $request, $id = null)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('services', 'public');
        }

        $request->update($validated); // recheck this logic

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }


    // public function edit($id)
    // {
    //     $service = $this->serviceRepository->find($id);
    //     return view('admin.services.edit', compact('service'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $this->serviceRepository->update($id, $request->all());
    //     return redirect()->route('services.index')->with('success', 'Service updated!');
    // }

    // public function destroy($id)
    // {
    //     $this->serviceRepository->delete($id);
    //     return redirect()->route('services.index')->with('success', 'Service deleted!');
    // }
}

<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Repositories\ContactMessage\ContactMessageRepositoryInterface;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    protected $repository;

    public function __construct(ContactMessageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['status', 'priority', 'start_date', 'end_date']);
            return $this->repository->getDatatableData($filters);
        }

        return abort(403);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.contact-messages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show($slug)
    {
        $message = $this->repository->find($slug);

        if (!$message) {
            return redirect()->route('contact-messages.index')->with('error', 'Message record not found.');
        }

        return view('admin.contact-messages.show', compact('message'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        //
    }
}

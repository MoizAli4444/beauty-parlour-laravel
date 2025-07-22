<?php

namespace App\Http\Controllers;

use App\Http\Requests\Staff\StoreStaffRequest;
use App\Models\Staff;
use App\Models\User;
use App\Repositories\Staff\StaffRepository;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    //     php artisan make:interface Repositories/StaffRepositoryInterface
    // php artisan make:class Repositories/StaffRepository

    // php artisan make:interface Repositories/CustomerRepositoryInterface
    // php artisan make:class Repositories/CustomerRepository


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreStaffRequest $request, StaffRepository $repository)
    {
        $repository->create($request->validated());

        return redirect()->back()->with('success', 'Staff created successfully!');
    }

    // $user = User::create([
    //     'name' => $request->name,
    //     'email' => $request->email,
    //     'password' => bcrypt($request->password),
    // ]);

    // $user->assignRole('receptionist'); // or 'admin', 'staff', 'customer'



    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        //
    }
}

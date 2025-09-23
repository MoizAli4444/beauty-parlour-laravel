<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Repositories\Expense\ExpenseRepositoryInterface;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $repository;

    public function __construct(ExpenseRepositoryInterface $repository)
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
        return view('admin.expense.index');
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
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}

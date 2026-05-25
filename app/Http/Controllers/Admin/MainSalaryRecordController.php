<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceMonthlyCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainSalaryRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2(FinanceMonthlyCalendar::class, ['*'], ['company_id' => $company_id], 'finance_yr', 'desc', 'id', 'asc', 12);
        return view('admin.mainSalaryRecord.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mainSalaryRecord.create');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

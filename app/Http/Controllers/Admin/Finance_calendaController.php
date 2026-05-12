<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance_calendarsRequest;
use App\Models\Finance_calendar;
use Illuminate\Http\Request;

class Finance_calendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $finance_calendars = Finance_calendar::orderBy('finance_yr', 'desc')->paginate(PAGEINATION_COUNTER);
        return view('admin.finance_calendar.index', compact('finance_calendars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.finance_calendar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Finance_calendarsRequest $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Finance_calendar $finance_calendar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finance_calendar $finance_calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Finance_calendar $finance_calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance_calendar $finance_calendar)
    {
        //
    }
}

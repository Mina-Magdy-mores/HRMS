<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodGroup;
use App\Models\Branche;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Governorate;
use App\Models\JobsCategory;
use App\Models\Nationality;
use App\Models\Qualification;
use App\Models\Religion;
use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $employees = getColsWhereP(
            Employee::class,
            [
                'addedBy',
                'updatedBy',
                'resignation',
                'religion',
                'qualification',
                'nationality',
                'job',
                'shiftType',
                'department',
                'bloodGroup',
                'branch',
                'country',
                'governorate',
                'city'
            ],
            ['*'],
            ['company_id' => $company_id],
            'id',
            'asc',
            PAGEINATION_COUNTER
        );
        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company_id = Auth::user()->company_id;
        $religions = get_cols_where(Religion::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $qualifications = get_cols_where(Qualification::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $resignations = get_cols_where(Resignation::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $nationalities = get_cols_where(Nationality::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $bloodGroups = get_cols_where(BloodGroup::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $countries = get_cols_where(Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);
        $cities = get_cols_where(City::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1]);

        return view('admin.employees.create', compact(
            'religions',
            'qualifications',
            'resignations',
            'jobs',
            'departments',
            'nationalities',
            'branches',
            'bloodGroups',
            'countries',
            'governorates',
            'cities'
        ));
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
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}

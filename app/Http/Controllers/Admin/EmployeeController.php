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
use App\Http\Requests\EmployeeRequest;
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
    public function getDetails($id)
    {
        $employee = Employee::with([
            'bloodGroup',
            'country',
            'governorate',
            'city',
            'religion',
            'qualification',
            'job',
            'department',
            'nationality',
            'shiftType',
            'branch',
            'resignation',
            'addedBy',
            'updatedBy'
        ])->findOrFail($id);

        return view('admin.employees.modal_details', compact('employee'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company_id = Auth::user()->company_id;
        $religions = get_cols_where(Religion::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $qualifications = get_cols_where(Qualification::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $resignations = get_cols_where(Resignation::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $nationalities = get_cols_where(Nationality::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $bloodGroups = get_cols_where(BloodGroup::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $countries = get_cols_where(Country::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $cities = get_cols_where(City::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');

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
    public function store(EmployeeRequest $request)
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

    public function getGovernorateList(Request $request)
    {
        $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['country_id' => $request->country_id, 'status' => 1], 'id', 'asc');
        return view('admin.employees.governorate_list', compact('governorates'));
    }
    public function getCitiesList(Request $request)
    {
        $cities = get_cols_where(City::class, ['id', 'name'], ['governorate_id' => $request->governorate_id, 'status' => 1], 'id', 'asc');
        return view('admin.employees.cities_list', compact('cities'));
    }

}

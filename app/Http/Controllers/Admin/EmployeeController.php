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
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\DrivingLicenseType;
use App\Models\Language;
use App\Models\MilitaryStatus;
use App\Models\ShiftsType;
use Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'militaryStatus',
            'resignation',
            'addedBy',
            'updatedBy',
            'drivingLicenseType',
            'language'
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
        $military_statuses = get_cols_where(MilitaryStatus::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $driving_license_types = get_cols_where(DrivingLicenseType::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $shiftTypes = get_cols_where(ShiftsType::class, ['id', 'type', 'start_time', 'end_time', 'total_hours'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $languages = get_cols_where(Language::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');

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
            'cities',
            'military_statuses',
            'driving_license_types',
            'shiftTypes',
            'languages'

        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        try {
            $company_id = Auth::user()->company_id;
            $checkIfExist = getColsWhereRow(Employee::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if (!empty($checkIfExist)) {
                return redirect()->back()->with(['error' => 'هذا الموظف موجود بالفعل'])->withInput();
            }
            $last_employee = get_cols_where_row_orderby(Employee::class, ['employee_code'], ['company_id' => $company_id], 'id', 'desc');
            if (!empty($last_employee)) {
                $employee_code = $last_employee->employee_code + 1;
            } else {
                $employee_code = 1;
            }
            $validatedData = $request->validated();
            if (!empty($request->salary)) {
                $validatedData['payment_per_day'] = $request->salary / 30; // Assuming 30 days in a month
            }
            if ($request->has('image')) {
                $validatedData['image'] = uploadImage('employees/profile', $request->file('image'));
            }
            if ($request->has('cv')) {
                $validatedData['cv'] = uploadImage('employees/cv', $request->file('cv'));
            }
            $validatedData['hire_date_day_month_year'] = $request->hire_date;
            $validatedData['employee_code'] = $employee_code;
            $validatedData['company_id'] = $company_id;
            $validatedData['added_by'] = Auth::id();
            $flag = insert(Employee::class, $validatedData);
            if ($flag) {
                return redirect()->route('admin.employees.index')->with(['success' => 'تم إضافة الموظف بنجاح']);
            }
            return redirect()->back()->with(['error' => 'حدث خطأ أثناء إضافة الموظف'])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'عفوا حدث خطأ ما ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $company_id = Auth::user()->company_id;
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
        }
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
        $military_statuses = get_cols_where(MilitaryStatus::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $driving_license_types = get_cols_where(DrivingLicenseType::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $shiftTypes = get_cols_where(ShiftsType::class, ['id', 'type', 'start_time', 'end_time', 'total_hours'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $languages = get_cols_where(Language::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');

        return view('admin.employees.update', compact(
            'employee',
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
            'cities',
            'military_statuses',
            'driving_license_types',
            'shiftTypes',
            'languages'

        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        try {
            $company_id = Auth::user()->company_id;
            $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
            if (!$employee) {
                return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
            }
            $validatedData = $request->validated();
            $validatedData['company_id'] = $company_id;
            $validatedData['updated_by'] = Auth::id();
            if (!empty($request->salary) && $request->salary != $employee->salary) {
                $validatedData['payment_per_day'] = $request->salary / 30;
            }
            if ($request->hasFile('image')) {
                if (!empty($employee->image) && Storage::exists($employee->image)) {
                    Storage::delete($employee->image);
                }

                $validatedData['image'] = uploadImage(
                    'employees/profile',
                    $request->file('image')
                );
            }
            if ($request->hasFile('cv')) {
                if (!empty($employee->cv) && Storage::exists($employee->cv)) {
                    Storage::delete($employee->cv);
                }
                $validatedData['cv'] = uploadImage('employees/cv', $request->file('cv'));
            }

            $employee->update($validatedData);
            return redirect()->route('admin.employees.index')->with(['success' => 'تم تحديث بيانات الموظف بنجاح']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => 'حدث خطأ أثناء تحديث بيانات الموظف ' . $e->getMessage()])->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $company_id = Auth::user()->company_id;
        $employee = getColsWhereRow(Employee::class, ['id', 'employment_status'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
        }
        if ($employee->employment_status == 1) {
            return redirect()->route('admin.employees.index')->with(['error' => 'لا يمكن حذف الموظف لأنه لديه حالة توظيف نشطة']);
        }
        $employee->delete();
        return redirect()->route('admin.employees.index')->with(['success' => 'تم حذف الموظف بنجاح']);
    }

    public function getGovernorateList(Request $request)
    {
        if ($request->ajax()) {
            $governorates = get_cols_where(Governorate::class, ['id', 'name'], ['country_id' => $request->country_id, 'status' => 1], 'id', 'asc');
            $selected_governorate_id = $request->selected_governorate_id;
            return view('admin.employees.governorate_list', compact('governorates', 'selected_governorate_id'));
        }
    }
    public function getCitiesList(Request $request)
    {
        if ($request->ajax()) {
            $cities = get_cols_where(City::class, ['id', 'name'], ['governorate_id' => $request->governorate_id, 'status' => 1], 'id', 'asc');
            $selected_city_id = $request->selected_city_id;
            return view('admin.employees.cities_list', compact('cities', 'selected_city_id'));
        }
    }

}

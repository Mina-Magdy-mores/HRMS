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
use App\Http\Requests\FileRequest;
use App\Models\DrivingLicenseType;
use App\Models\File;
use App\Models\Language;
use App\Models\MilitaryStatus;
use App\Models\ShiftsType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        return view('admin.employees.index', compact('employees', 'branches', 'departments', 'jobs'));
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
            'language',
            'files',
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
        dd($request->all());
        try {
            $company_id = Auth::user()->company_id;
            $checkIfExist = getColsWhereRow(Employee::class, ['id'], ['company_id' => $company_id, 'name' => $request->name]);
            if (!empty($checkIfExist)) {
                return redirect()->back()->with(['error' => 'هذا الموظف موجود بالفعل'])->withInput();
            }
            $last_employee = get_cols_where_row_orderby(Employee::class, ['employee_code'], ['company_id' => $company_id], 'employee_code', 'desc');
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
                if (!empty($employee->image)) {
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
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
        }
        if ($employee->employment_status == 1) {
            return redirect()->route('admin.employees.index')->with(['error' => 'لا يمكن حذف الموظف لأنه لديه حالة توظيف نشطة']);
        }
        DB::transaction(function () use ($employee) {
            if (!empty($employee->image)) {
                Storage::delete($employee->image);
            }
            if (!empty($employee->cv)) {
                Storage::delete($employee->cv);
            }
            $employee->delete();
        });
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

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $fingerprint_code = $request->fingerprint_code;
            $employee_code = $request->employee_code;
            $name = $request->name;
            $branch_id = $request->branch_id;
            $department_id = $request->department_id;
            $job_id = $request->job_id;
            $employment_status = $request->employment_status;
            $payment_method = $request->payment_method;
            $gender = $request->gender;
            $code_type = $request->code_type;
            if ($code_type == '') {
                $field1 = "id";
                $operator1 = ">=";
                $value1 = 0;
            } else {

                if ($code_type == 'fingerprint_code') {

                    if (empty($fingerprint_code)) {
                        $field1 = "id";
                        $operator1 = ">=";
                        $value1 = 0;
                    } else {
                        $field1 = "fingerprint_code";
                        $operator1 = "=";
                        $value1 = $fingerprint_code;
                    }
                } else if ($code_type == 'employee_code') {

                    if (empty($employee_code)) {
                        $field1 = "id";
                        $operator1 = ">=";
                        $value1 = 0;
                    } else {
                        $field1 = "employee_code";
                        $operator1 = "=";
                        $value1 = $employee_code;
                    }
                } else {

                    $field1 = "id";
                    $operator1 = ">=";
                    $value1 = 0;
                }
            }

            if (empty($name)) {
                $field2 = "id";
                $operator2 = ">=";
                $value2 = 0;
            } else {
                $field2 = "name";
                $operator2 = "like";
                $value2 = "%{$name}%";
            }

            if (empty($branch_id)) {
                $field3 = "id";
                $operator3 = ">=";
                $value3 = 0;
            } else {
                $field3 = "branch_id";
                $operator3 = "=";
                $value3 = $branch_id;
            }
            if (empty($department_id)) {
                $field4 = "id";
                $operator4 = ">=";
                $value4 = 0;
            } else {
                $field4 = "department_id";
                $operator4 = "=";
                $value4 = $department_id;
            }
            if (empty($job_id)) {
                $field5 = "id";
                $operator5 = ">=";
                $value5 = 0;
            } else {
                $field5 = "job_id";
                $operator5 = "=";
                $value5 = $job_id;
            }
            if ($employment_status == '') {
                $field6 = "id";
                $operator6 = ">=";
                $value6 = 0;
            } else if ($employment_status == 0 || $employment_status == 1) {
                $field6 = "employment_status";
                $operator6 = "=";
                $value6 = $employment_status;
            }
            if ($payment_method == '') {
                $field7 = "id";
                $operator7 = ">=";
                $value7 = 0;
            } else {
                $field7 = "payment_method";
                $operator7 = "=";
                $value7 = $payment_method;
            }
            if ($gender == '') {
                $field8 = "id";
                $operator8 = ">=";
                $value8 = 0;
            } else {
                $field8 = "gender";
                $operator8 = "=";
                $value8 = $gender;
            }


            $where = [
                [$field1, $operator1, $value1],
                [$field2, $operator2, $value2],
                [$field3, $operator3, $value3],
                [$field4, $operator4, $value4],
                [$field5, $operator5, $value5],
                [$field6, $operator6, $value6],
                [$field7, $operator7, $value7],
                [$field8, $operator8, $value8],
            ];
            $employees = getColsWhereP(Employee::class, [
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
            ], ['*'], $where, 'id', 'asc', PAGEINATION_COUNTER);
            return view('admin.employees.ajaxSearch', compact('employees'));
        }
    }
    public function download($id, $type, $file = null)
    {
        $company_id = Auth::user()->company_id;
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
        }

        if ($type === 'image' && $employee->image) {
            return response()->download(storage_path('app/public/' . $employee->image));
        } elseif ($type === 'cv' && $employee->cv) {
            return response()->download(storage_path('app/public/' . $employee->cv));
        } elseif ($type === 'file' && $file) {
            $file = getColsWhereRow(File::class, ['path'], ['id' => $file, 'employee_id' => $id, 'company_id' => $company_id]);
            if ($file) {
                return response()->download(storage_path('app/public/' . $file->path));
            }
        } else {
            return redirect()->route('admin.employees.index')->with(['error' => 'الملف غير موجود']);
        }
    }
    public function deleteFile($id, $employee_id)
    {
        $company_id = Auth::user()->company_id;
        $file = getColsWhereRow(File::class, ['*'], ['employee_id' => $employee_id, 'company_id' => $company_id, 'id' => $id]);
        if (!$file) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الملف غير موجود']);
        }
        Storage::delete($file->path);
        destroy($file);
        return redirect()->route('admin.employees.index')->with(['success' => 'تم حذف الملف بنجاح']);
    }
    public function addFile(FileRequest $request, $id)
    {
        $company_id = Auth::user()->company_id;
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الموظف غير موجود']);
        }
        $checkIfExists = getColsWhereRow(File::class, ['*'], ['name' => $request->name, 'employee_id' => $id, 'company_id' => $company_id]);
        if ($checkIfExists) {
            return redirect()->route('admin.employees.index')->with(['error' => 'الملف موجود بالفعل']);
        }
        try {
            $validatedData = $request->only(['name']);
            if ($request->has('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('employees/files', $fileName);
                $validatedData['path'] = $path;
                $validatedData['employee_id'] = $id;
                $validatedData['company_id'] = $company_id;
                $validatedData['added_by'] = Auth::id();
                insert(File::class, $validatedData);
                return redirect()->route('admin.employees.index')->with(['success' => 'تم إضافة الملف بنجاح']);
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.employees.index')->with(['error' => 'حدث خطأ أثناء إضافة الملف ' . $e->getMessage()]);
        }
    }
}

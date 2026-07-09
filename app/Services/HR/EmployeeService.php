<?php

namespace App\Services\HR;

use App\Services\BaseService;
use App\Models\Employee;
use App\Models\ShiftsType;
use App\Models\EmployeeSalaryArchive;
use App\Models\EmployeeFixedAllowance;
use App\Models\MainSalaryEmployee;
use App\Services\Finance\SalaryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EmployeeService extends BaseService
{
    public function __construct()
    {
        $this->setModel(Employee::class);
    }

    /**
     * Create a new employee with related rules and file uploads.
     */
    public function createEmployee($data)
    {
        $company_id = $this->getCompanyId();
        
        $checkIfExist = getColsWhereRow(Employee::class, ['id'], ['company_id' => $company_id, 'name' => $data['name']]);
        if (!empty($checkIfExist)) {
            throw new \Exception('هذا الموظف موجود بالفعل');
        }

        $last_employee = get_cols_where_row_orderby(Employee::class, ['employee_code'], ['company_id' => $company_id], 'employee_code', 'desc');
        $employee_code = !empty($last_employee) ? $last_employee->employee_code + 1 : 1;

        if (isset($data['salary']) && $data['salary'] !== '') {
            $data['payment_per_day'] = $data['salary'] > 0 ? $data['salary'] / 30 : 0;
        } else {
            $data['payment_per_day'] = null;
        }

        if (isset($data['image_file'])) {
            $data['image'] = uploadImage('employees/profile', $data['image_file']);
        }
        if (isset($data['cv_file'])) {
            $data['cv'] = uploadImage('employees/cv', $data['cv_file']);
        }

        $data['hire_date_day_month_year'] = $data['hire_date'] ?? null;
        $data['employee_code'] = $employee_code;
        $data['company_id'] = $company_id;
        $data['added_by'] = $this->getUserId();

        if (isset($data['fixed_shift']) && $data['fixed_shift'] == 1) {
            $shiftData = getColsWhereRow(ShiftsType::class, ['total_hours'], ['company_id' => $company_id, 'id' => $data['shift_type_id']]);
            if (!empty($shiftData)) {
                $data['daily_work_hours'] = $shiftData['total_hours'];
            } else {
                throw new \Exception('عفواا لم يتم تحديد الشفت ');
            }
        } else {
            $data['shift_type_id'] = null;
        }

        return insert(Employee::class, $data);
    }

    /**
     * Update employee details, log changes, and update salary variables.
     */
    public function updateEmployee($id, $data)
    {
        $company_id = $this->getCompanyId();
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            throw new \Exception('الموظف غير موجود');
        }

        $data['company_id'] = $company_id;
        $data['updated_by'] = $this->getUserId();

        if (isset($data['salary'])) {
            if ($data['salary'] !== null && $data['salary'] !== '') {
                $data['payment_per_day'] = $data['salary'] > 0 ? $data['salary'] / 30 : 0;
            } else {
                $data['payment_per_day'] = null;
            }
        }

        if (isset($data['image_file'])) {
            if (!empty($employee->image)) {
                Storage::delete($employee->image);
            }
            $data['image'] = uploadImage('employees/profile', $data['image_file']);
        }
        if (isset($data['cv_file'])) {
            if (!empty($employee->cv) && Storage::exists($employee->cv)) {
                Storage::delete($employee->cv);
            }
            $data['cv'] = uploadImage('employees/cv', $data['cv_file']);
        }

        DB::transaction(function () use ($employee, $data, $id, $company_id) {
            $oldSalary = $employee->salary;
            $isSalaryChanged = !empty($oldSalary) && !empty($data['salary']) && $data['salary'] != $oldSalary;

            if (isset($data['fixed_shift']) && $data['fixed_shift'] == 1) {
                $shiftData = getColsWhereRow(ShiftsType::class, ['total_hours'], ['company_id' => $company_id, 'id' => $data['shift_type_id']]);
                if (!empty($shiftData)) {
                    $data['daily_work_hours'] = $shiftData['total_hours'];
                } else {
                    throw new \Exception('عفواا لم يتم تحديد الشفت ');
                }
            } else {
                $data['shift_type_id'] = null;
            }

            $employee->update($data);

            if ($isSalaryChanged) {
                insert(EmployeeSalaryArchive::class, [
                    'employee_id' => $id,
                    'amount' => $oldSalary,
                    'company_id' => $company_id,
                    'added_by' => $this->getUserId(),
                    'updated_by' => $this->getUserId(),
                ]);
            }

            if (isset($data['fixed_allowance']) && $data['fixed_allowance'] == 0) {
                EmployeeFixedAllowance::where('employee_id', $id)->where('company_id', $company_id)->delete();
            }

            $main_salary_employee = getColsWhereRow(MainSalaryEmployee::class, ['id'], ['employee_id' => $id, 'company_id' => $company_id, 'is_archived' => 0]);
            if (!empty($main_salary_employee)) {
                app(SalaryService::class)->recalculateMainSalary($main_salary_employee->id);
            }
        });

        return $employee;
    }

    /**
     * Delete employee record and check related constraints.
     */
    public function deleteEmployee($id)
    {
        $company_id = $this->getCompanyId();
        $employee = getColsWhereRow(Employee::class, ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$employee) {
            throw new \Exception('الموظف غير موجود');
        }
        if ($employee->employment_status == 1) {
            throw new \Exception('لا يمكن حذف الموظف لأنه لديه حالة توظيف نشطة');
        }
        if ($employee->mainSalaryEmployee->count() > 0) {
            throw new \Exception('لا يمكن حذف الموظف لأنه لديه بيانات رواتب');
        }
        if ($employee->mainSalaryEmployeePLoans->count() > 0) {
            throw new \Exception('لا يمكن حذف الموظف لأنه لديه بيانات سلف');
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

        return true;
    }
}

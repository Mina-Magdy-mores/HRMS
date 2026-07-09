<?php

namespace App\Services\Finance;

use App\Services\BaseService;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\DB;
use App\Traits\GeneralTrait;

class SalaryRecordService extends BaseService
{
    use GeneralTrait;

    public function __construct()
    {
        $this->setModel(FinanceMonthlyCalendar::class);
    }

    public function openMonth($id, $startDate, $endDate, $userId)
    {
        $company_id = $this->getCompanyId();
        $financeMonthlyCalendar = getColsWhere(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['id' => $id, 'company_id' => $company_id]);
        if (!$financeMonthlyCalendar) {
            throw new \Exception('عذراً، الشهر المالى غير موجود');
        }
        if ($financeMonthlyCalendar->financeCalendar->status != 1) {
            throw new \Exception('عذراً، السنه المالية مغلقه');
        }
        if ($financeMonthlyCalendar->status == 1) {
            throw new \Exception('عذراً، الشهر المالى مفتوح بالفعل');
        }
        if ($financeMonthlyCalendar->status == 2) {
            throw new \Exception('عذراً، الشهر المالى مغلق و مؤرشف من قبل');
        }

        $total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
        if ($total_opened_months > 0) {
            throw new \Exception('عذراً، يوجد شهر المالى مفتوح بالفعل حاليا');
        }
        $total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $financeMonthlyCalendar->finance_yr])->where('month_id', '<', $financeMonthlyCalendar->month_id)->count();
        if ($total_prev_months_waiting_to_open > 0) {
            throw new \Exception('عذراً، يوجد أشهر مالية سابقة معلقة و فى انتظار للفتح');
        }

        DB::transaction(function () use ($financeMonthlyCalendar, $company_id, $startDate, $endDate, $userId) {
            $dataToUpdate = [
                'status' => 1,
                'start_date_for_calculation' => $startDate,
                'end_date_for_calculation' => $endDate,
                'updated_by' => $userId
            ];
            $flag = update($financeMonthlyCalendar, $dataToUpdate);
            if (!$flag) {
                throw new \Exception('عذراً، حدث خطأ أثناء فتح الشهر المالى');
            }

            $all_employees = get_cols_where(Employee::class, ['*'], ['company_id' => $company_id, 'employment_status' => 1], 'employee_code', 'asc');
            if ($all_employees) {
                foreach ($all_employees as $employee) {
                    $this->calculate_employees_vacations_balance($employee->id);

                    $dataToInsert = [];
                    $dataToInsert['finance_monthly_calendar_id'] = $financeMonthlyCalendar->id;
                    $dataToInsert['employee_id'] = $employee->id;
                    $dataToInsert['company_id'] = $company_id;
                    $checkIfExists = get_count_where(MainSalaryEmployee::class, $dataToInsert);
                    if ($checkIfExists == 0) {
                        $dataToInsert['employee_name'] = $employee->name;
                        $dataToInsert['employee_per_day_salary'] = $employee->payment_per_day;
                        $dataToInsert['sensitive'] = $employee->has_sensitive_data;
                        $dataToInsert['employee_status'] = $employee->employment_status;
                        $dataToInsert['employee_branch_id'] = $employee->branch_id;
                        $dataToInsert['employee_department_id'] = $employee->department_id;
                        $dataToInsert['employee_job_id'] = $employee->job_id;
                        $dataToInsert['employee_salary'] = $employee->salary;
                        
                        $employee_rollover_amount = get_cols_where_row_orderby(MainSalaryEmployee::class,
                            ['employee_net_salary_after_close_for_roll_over'], 
                            ['employee_id' => $employee->id, 'company_id' => $company_id, 'is_archived' => 1], 
                            'id', 
                            'desc'
                        );
                        if (!empty($employee_rollover_amount)) {
                            $dataToInsert['employee_rollover_amount'] = $employee_rollover_amount->employee_net_salary_after_close_for_roll_over;
                        } else {
                            $dataToInsert['employee_rollover_amount'] = 0;
                        }
                        
                        $dataToInsert['year_and_month'] = $financeMonthlyCalendar->year_and_month;
                        $dataToInsert['financial_year'] = $financeMonthlyCalendar->finance_yr;
                        $dataToInsert['payment_method'] = $employee->payment_method;
                        $dataToInsert['added_by'] = $userId;
                        
                        $insert = insert(MainSalaryEmployee::class, $dataToInsert, true);
                        if (!empty($insert)) {
                            $this->recalculate_main_salary($insert->id);
                        } else {
                            throw new \Exception('عذراً، حدث خطأ أثناء إدخال بيانات الموظف: الماليه ');
                        }
                    }
                }
            }
        });
    }
}
<?php 

namespace App\Traits;

use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeeAbsence;
use App\Models\MainSalaryEmployeeAddition;
use App\Models\MainSalaryEmployeeAllowance;
use App\Models\MainSalaryEmployeeBonus;
use App\Models\MainSalaryEmployeeDeduction;
use App\Models\MainSalaryEmployeeDeductionType;
use App\Models\MainSalaryEmployeeLoan;
use Illuminate\Support\Facades\Auth;

trait GeneralTrait
{
    function recalculate_main_salary($main_salary_employee_id) {
        $company_code = Auth::user()->company_code;
        $main_salary_employee = getColsWhereRow(MainSalaryEmployee::class, ['*'], ['id' => $main_salary_employee_id, 'company_code' => $company_code, 'is_archived' => 0]);

       if(!empty($main_salary_employee)){
        $employee = getColsWhereRow(Employee::class, ['payment_per_day','motivation_amount','social_insurance_amount','medical_insurance_amount','salary'], 
        ['id'=> $main_salary_employee['employee_id'], 'company_code' => $company_code]);


        if(!empty($employee)) {
            $main_salary_employee_deductions =  getColsWhereRow(MainSalaryEmployeeDeduction::class, 
            ['id','days_amount','total'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);

            $main_salary_employee_absence =  getColsWhereRow(MainSalaryEmployeeAbsence::class, 
            ['id','days_amount','total'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);
            
            $main_salary_employee_additions =  getColsWhereRow(MainSalaryEmployeeAddition::class, 
            ['id','days_amount','total'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);

            $main_salary_employee_deduction_type =  getColsWhereRow(MainSalaryEmployeeDeductionType::class, 
            ['id','amount'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);
            
            $main_salary_employee_bonus =  getColsWhereRow(MainSalaryEmployeeBonus::class, 
            ['id','amount'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);
            
            $main_salary_employee_allowance =  getColsWhereRow(MainSalaryEmployeeAllowance::class, 
            ['id','amount'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);
            
            $main_salary_employee_loans =  getColsWhereRow(MainSalaryEmployeeLoan::class, 
            ['id','amount'], ['main_salary_employee_id' => $main_salary_employee_id, 'company_code' => $company_code]);

            $dataToUpdate['salary'] = $employee['salary'] ?? 0;
            $dataToUpdate['payment_per_day'] = $employee['payment_per_day'] ?? 0;
            $dataToUpdate['motivation_amount'] = $employee['motivation_amount'] ?? 0;
            $dataToUpdate['social_insurance_amount'] = $employee['social_insurance_amount'] ?? 0;
            $dataToUpdate['medical_insurance_amount'] = $employee['medical_insurance_amount'] ?? 0;

            $dataToUpdate['employee_deductions_days_counter'] = $main_salary_employee_deductions['days_amount'] ?? 0;
            $dataToUpdate['employee_deductions_payment_total'] = $main_salary_employee_deductions['total'] ?? 0;

            $dataToUpdate['employee_absences_days_counter'] = $main_salary_employee_absence['days_amount'] ?? 0;
            $dataToUpdate['employee_absences_payment_total'] = $main_salary_employee_absence['total'] ?? 0;

            $dataToUpdate['employee_additions_days_counter'] = $main_salary_employee_additions['days_amount'] ?? 0;
            $dataToUpdate['employee_additions_payment_total'] = $main_salary_employee_additions['total'] ?? 0;
            
            $dataToUpdate['employee_total_deduction_type'] = $main_salary_employee_deduction_type['amount'] ?? 0;
            
            $dataToUpdate['employee_total_bonus'] = $main_salary_employee_bonus['amount'] ?? 0;
            
            $dataToUpdate['employee_total_allowance'] = $main_salary_employee_allowance['amount'] ?? 0;
            
            $dataToUpdate['monthly_loan_amount'] = $main_salary_employee_loans['amount'] ?? 0;
            
            $dataToUpdate['permanent_loan_amount'] = $main_salary_employee_p_loans['amount'] ?? 0;



            
            
        }
       }
    }
}
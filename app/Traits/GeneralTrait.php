<?php

namespace App\Traits;

use App\Services\Finance\SalaryService;
use App\Services\HR\VacationService;

trait GeneralTrait
{
    /**
     * Recalculate main salary details (Proxy to SalaryService).
     */
    public function recalculate_main_salary($main_salary_employee_id)
    {
        app(SalaryService::class)->recalculateMainSalary($main_salary_employee_id);
    }

    /**
     * Calculate vacation balance for employee (Proxy to VacationService).
     */
    public function calculate_employees_vacations_balance($id)
    {
        app(VacationService::class)->calculateEmployeesVacationsBalance($id);
    }

    /**
     * Reupdate vacation carryover balance (Proxy to VacationService).
     */
    public function reupdate_vacation($id)
    {
        app(VacationService::class)->reupdateVacation($id);
    }

    /**
     * Pull fingerprint variables to salary (Proxy to SalaryService).
     */
    public function pullFingerprintVariablesToSalary($employee_id, $finance_monthly_calendar_id, $company_id)
    {
        app(SalaryService::class)->pullFingerprintVariablesToSalary($employee_id, $finance_monthly_calendar_id, $company_id);
    }

    /**
     * Pull fingerprint variables for all calendar items (Proxy to SalaryService).
     */
    public function pullFingerprintVariablesToSalaryForCalendar($finance_monthly_calendar_id, $company_id)
    {
        app(SalaryService::class)->pullFingerprintVariablesToSalaryForCalendar($finance_monthly_calendar_id, $company_id);
    }
}

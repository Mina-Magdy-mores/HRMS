<?php

namespace App\Services\Attendance;

use App\Services\BaseService;
use App\Models\Employee;
use App\Models\AdminPanelSetting;
use App\Models\FinanceMonthlyCalendar;
use App\Models\AttendanceDeparture;
use App\Models\AttendanceDepartureAction;
use App\Models\AttendanceDepartureActionsExcel;
use App\Models\Occasion;
use App\Models\ShiftsType;
use App\Models\DeductionType;
use App\Models\VacationType;
use App\Models\MainSalaryEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AttendanceDepartureImport;
use App\Services\Finance\SalaryService;

class AttendanceService extends BaseService
{
    public function __construct()
    {
        $this->setModel(AttendanceDeparture::class);
    }

    /**
     * Recalculate employee month attendance records.
     */
    public function recalculateEmployeeMonth($employeeId, $calendarId, $companyId)
    {
        $employee = Employee::find($employeeId);
        if (empty($employee)) {
            return;
        }

        $settings = AdminPanelSetting::where('company_id', $companyId)->first();

        // Fetch all records chronologically
        $records = AttendanceDeparture::where([
            'employee_id' => $employeeId,
            'company_id' => $companyId,
            'finance_monthly_calendar_id' => $calendarId
        ])->orderBy('day_of_finger_print', 'asc')->get();

        $delayOrEarlyDaysSoFar = 0;
        $absenceDaysSoFar = 0;

        foreach ($records as $record) {
            $record->recalculate($employee, $companyId, $settings, $delayOrEarlyDaysSoFar, $absenceDaysSoFar);
        }
    }

    /**
     * Import Excel fingerprint spreadsheet.
     */
    public function importFingerprint($financeMonthlyCalendar, $excelFile)
    {
        $company_id = $this->getCompanyId();
        
        Excel::import(new AttendanceDepartureImport($financeMonthlyCalendar), $excelFile);

        $adminSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        if ($adminSetting && $adminSetting->is_allowed_to_pull_salary_variables_from_fingerprint == 1) {
            app(SalaryService::class)->pullFingerprintVariablesToSalaryForCalendar($financeMonthlyCalendar->id, $company_id);
        }
    }
}

<?php

namespace App\Imports;

use App\Models\AdminPanelSetting;
use App\Models\AttendanceDeparture;
use App\Models\AttendanceDepartureAction;
use App\Models\AttendanceDepartureActionsExcel;
use App\Models\Employee;
use App\Models\FinanceMonthlyCalendar;
use App\Models\MainSalaryEmployee;
use App\Models\ShiftsType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AttendanceDepartureImport implements ToCollection
{
    private int $finance_monthly_calendar_id;
    private FinanceMonthlyCalendar $financeMonthlyCalendar;

    public function __construct(FinanceMonthlyCalendar $financeMonthlyCalendar)
    {
        $this->finance_monthly_calendar_id = $financeMonthlyCalendar->id;
        $this->financeMonthlyCalendar = $financeMonthlyCalendar;
    }
    public function collection(Collection $rows)
    {
        $company_id = Auth::user()->company_id;
        foreach ($rows as $row) {
            $dateTimeAction = null;
            if (!empty($row[3])) {
                if (is_numeric($row[3])) {
                    try {
                        $dateTimeAction = Date::excelToDateTimeObject($row[3])->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        $dateTimeAction = null;
                    }
                } else {
                    $normalizedDate = str_replace('/', '-', $row[3]);
                    $timestamp = strtotime($normalizedDate);
                    if ($timestamp !== false) {
                        $dateTimeAction = date('Y-m-d H:i:s', $timestamp);
                    }
                }
            }

            // check for the financeMonthlyCalendar start and end date
            $dateForCheck = date('Y-m-d', strtotime($dateTimeAction));
            if ($dateForCheck < $this->financeMonthlyCalendar->start_date_for_calculation || $dateForCheck > $this->financeMonthlyCalendar->end_date_for_calculation) {
                continue;
            }
            $type = null;
            if (!empty($row[4])) {
                if ($row[4] == "C/In") {
                    $type = 1;
                } elseif ($row[4] == "C/Out") {
                    $type = 2;
                }
            }

            $employee = getColsWhereRow(new Employee(), ['id', 'fixed_shift', 'shift_type_id', 'daily_work_hours','branch_id','employment_status'], [
                'company_id' => $company_id,
                'employee_code' => $row[2]
            ]);
            if (!empty($employee)) {
                $checkExisitsBefore = getColsWhereRow(new AttendanceDepartureActionsExcel(), ['id'], [
                    'company_id' => $company_id,
                    'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                    'dateTimeAction' => $dateTimeAction,
                    'employee_id' => $employee['id'],
                    'type' => $type
                ]);
                       

                if (empty($checkExisitsBefore)) {
                    $checkExisitsMainSalaryEmployee  = getColsWhereRow(new MainSalaryEmployee(), ['id'], [
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id
                    ]);
                    if (!empty($checkExisitsMainSalaryEmployee)) {
                        $dataToInsert['main_salary_employee_id'] = $checkExisitsMainSalaryEmployee['id'];
                    }
                    $dataToInsert['company_id'] = $company_id;
                    $dataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                    $dataToInsert['dateTimeAction'] = $dateTimeAction;
                    $dataToInsert['employee_id'] = $employee['id'] ?? null;
                    $dataToInsert['type'] = $type ?? null;
                    $dataToInsert['added_by'] = Auth::id();
                    $dataToInsert['notes'] = 'من خلال ملف Excel';
                    $attendanceDepartureActionsExcel = insert(AttendanceDepartureActionsExcel::class,$dataToInsert,true);
                    //check the shift type first

                    if ($employee['fixed_shift'] == 1) {
                        $shiftData = getColsWhereRow(new ShiftsType(), ['id', 'start_time', 'end_time', 'total_hours'], [
                            'company_id' => $company_id,
                            'id' => $employee['shift_type_id']
                        ]);
                        if (empty($shiftData)) {
                            continue;
                        } else {
                            $total_hours = $shiftData['total_hours'] ?? null;
                        }
                    } else {
                        if ($employee['daily_work_hours'] == 0 || empty($employee['daily_work_hours']) || $employee['daily_work_hours'] == null) {
                            continue;
                        } else {
                            $total_hours = $employee['daily_work_hours'] ?? null;
                        }
                    }
                    //check if there is an empty record
                    $checkfor_empty_record = getColsWhereRow(new AttendanceDeparture(), ['id', 'status_move'], [
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                        'day_of_finger_print' => date('Y-m-d', strtotime($dateTimeAction)),
                        'checkInDateTime' => null,
                    ]);
                    if (!empty($checkfor_empty_record)) {
                        if ($type == 1) {
                            $checkfor_empty_record->destroy();
                        }
                    }
                    //get last record
                    $last = AttendanceDeparture::select('*')->where([
                        'company_id' => $company_id,
                        'employee_id' => $employee['id'],
                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                    ])
                        ->where('checkInDateTime', '!=', null)
                        ->where('checkInDateTime', '<=', date('Y-m-d', strtotime($dateTimeAction)))
                        ->orderBy('id', 'desc')->first();
                    if (!empty($last)) {

                    } else {
                        // will consider it as the first finger print in the current opened finance_monthly_calendar_id
                        // will prepare the insert array and will consider it as check in
                        $dataToInsert2['status_move'] = 1;
                        $dataToInsert2['shift_hours'] = $total_hours ?? $employee['daily_work_hours'];
                        $dataToInsert2['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                        $dataToInsert2['employee_id'] = $employee['id'];
                        $dataToInsert2['checkInDate'] = date('Y-m-d', strtotime($dateTimeAction));
                        $dataToInsert2['checkInTime'] = date('H:i:s', strtotime($dateTimeAction));
                        $dataToInsert2['checkInDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                        $dataToInsert2['company_id'] = $company_id;
                        $dataToInsert2['added_by'] = Auth::id();
                        $dataToInsert2['notes'] = 'من خلال ملف Excel';
                        if ($employee['fixed_shift'] == 1) {
                            $admin_panel_settings = AdminPanelSetting::select('*')
                                ->where('company_id', $company_id)->first();
                            if ($shiftData['start_time'] < $dataToInsert2['checkInTime']) {
                                $diffInSeconds = strtotime($dataToInsert2['checkInTime']) - strtotime($shiftData['start_time']);
                                $diffInMinutes = $diffInSeconds / 60;
                                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                if ($fromMinutesIntoDecimalNumber > $admin_panel_settings->after_minute_calculate_delay) {
                                    $dataToInsert2['attendance_delay'] = $fromMinutesIntoDecimalNumber;
                                    $counterCutQuarterDay = get_count_where(new AttendanceDeparture(), [
                                        'company_id' => $company_id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'cutting_days' => .25
                                    ]);
                                    $counterCutHalfDay = get_count_where(new AttendanceDeparture(), [
                                        'company_id' => $company_id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'cutting_days' => .5
                                    ]);
                                    $counterCutFullDay = get_count_where(new AttendanceDeparture(), [
                                        'company_id' => $company_id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'cutting_days' => 1
                                    ]);
                                    if($counterCutFullDay>= $admin_panel_settings->after_days_allday_day_cut){
                                        $dataToInsert2['cutting_days'] = 1;
                                    }else{
                                        if($counterCutHalfDay>= $admin_panel_settings->after_days_half_day_cut){
                                            $dataToInsert2['cutting_days'] = .5;
                                        }else{
                                            if($counterCutQuarterDay>= $admin_panel_settings->after_minute_quarter_day_cut){
                                                $dataToInsert2['cutting_days'] = .25;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $dataToInsert2['year_and_month'] = $this->financeMonthlyCalendar->year_and_month;
                        $dataToInsert2['employee_branch_id'] = $employee->branch_id;
                        $dataToInsert2['employee_status'] = $employee->employment_status;
                        $dataToInsert2['day_of_finger_print'] = date('Y-m-d', strtotime($dateTimeAction));
                            $mainSalaryEmployee = getColsWhereRow(new MainSalaryEmployee(), ['id'], [
                            'employee_id' => $employee['id'],
                            'company_id'=> $company_id,
                            'is_archived'=> 0,
                        ]);
                        if(!empty($mainSalaryEmployee)){
                            $dataToInsert2['main_salary_employee_id'] = $mainSalaryEmployee->id;
                        }
                        $flagInsertParent = insert(new AttendanceDeparture(), $dataToInsert2,true);
                        if($flagInsertParent){
                            $ActionDataToInsert['attendances_departure_id'] = $flagInsertParent->id;
                            $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                            $ActionDataToInsert['employee_id'] = $employee['id'];
                            $ActionDataToInsert['dateTimeAction'] = date('Y-m-d', strtotime($dateTimeAction));
                            $ActionDataToInsert['company_id'] = $company_id;
                            $ActionDataToInsert['type'] = $type;
                            $ActionDataToInsert['is_active_with_parent'] = '1';
                            $ActionDataToInsert['added_method'] = '1';
                            $ActionDataToInsert['is_active_with_parent'] = '0';
                            $ActionDataToInsert['company_id'] = $company_id;
                            $ActionDataToInsert['added_by'] = Auth::id();
                            $ActionDataToInsert['notes'] = 'من خلال ملف Excel';
                            $ActionDataToInsert['attendance_departure_actions_excel_id'] = $attendanceDepartureActionsExcel['id'];
                            insert(new AttendanceDepartureAction(), $ActionDataToInsert);

                            
                        }
                        
                    }
                }
            }
        }
    }
}

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
        $admin_panel_settings = AdminPanelSetting::select('*')
            ->where('id', $company_id)->first();

        // Sort rows chronologically by dateTimeAction to ensure correct processing order
        $sortedRows = $rows->filter(function($row) {
            return !empty($row[3]) && !empty($row[2]);
        })->sortBy(function($row) {
            if (is_numeric($row[3])) {
                try {
                    return Date::excelToDateTimeObject($row[3])->getTimestamp();
                } catch (\Exception $e) {
                    return 0;
                }
            } else {
                $normalizedDate = str_replace('/', '-', $row[3]);
                $timestamp = strtotime($normalizedDate);
                return $timestamp !== false ? $timestamp : 0;
            }
        });

        foreach ($sortedRows as $row) {
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

            if (empty($dateTimeAction)) {
                continue;
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

            $employee = getColsWhereRow(new Employee(), ['id', 'fixed_shift', 'shift_type_id', 'daily_work_hours', 'branch_id', 'employment_status'], [
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
                    
                    $dataToInsert = [];
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
                    $attendanceDepartureActionsExcel = insert(AttendanceDepartureActionsExcel::class, $dataToInsert, true);

                    //check the shift type first
                    $shiftHours = null;
                    $shiftData = null;
                    if ($employee['fixed_shift'] == 1) {
                        $shiftData = getColsWhereRow(new ShiftsType(), ['id', 'start_time', 'end_time', 'total_hours'], [
                            'company_id' => $company_id,
                            'id' => $employee['shift_type_id']
                        ]);
                        if (!empty($shiftData)) {
                            $shiftHours = $shiftData['total_hours'] ?? null;
                        }
                    } else {
                        if ($employee['daily_work_hours'] > 0) {
                            $shiftHours = $employee['daily_work_hours'] ?? null;
                        }
                    }

                    if ($shiftHours === null) {
                        continue;
                    }
                    $logicalType = $type;
                    if ($type !== null) {
                        // Find the last record with check-in <= $dateTimeAction
                        $last = AttendanceDeparture::select('*')->where([
                            'company_id' => $company_id,
                            'employee_id' => $employee['id'],
                            'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                        ])
                            ->where('checkInDateTime', '!=', null)
                            ->where('checkInDateTime', '<=', $dateTimeAction)
                            ->orderBy('id', 'desc')->first();

                        if (empty($last)) {
                            if ($type === 2 && date('H:i:s', strtotime($dateTimeAction)) < '12:00:00') {
                                $logicalType = 2;
                            } else {
                                $logicalType = 1;
                            }
                        } else {
                            $diffInSeconds = strtotime($dateTimeAction) - strtotime($last->checkInDateTime);
                            $diffInHours = $diffInSeconds / 3600;
                            $pairingWindow = $shiftHours + ($admin_panel_settings->after_shift_max_extra_hours ?? 0);

                            if ($last->checkOutDateTime === null) {
                                if ($diffInHours <= $pairingWindow) {
                                    $logicalType = 2;
                                } else {
                                    if ($type === 2 && date('H:i:s', strtotime($dateTimeAction)) < '12:00:00') {
                                        $logicalType = 2;
                                    } else {
                                        $logicalType = 1;
                                    }
                                }
                            } else {
                                // Last record is closed. Check if it's a duplicate check-out.
                                $is_duplicate_checkout = false;
                                if ($type === 2) {
                                    $diffCheckoutSeconds = abs(strtotime($dateTimeAction) - strtotime($last->checkOutDateTime));
                                    $diffCheckoutMinutes = $diffCheckoutSeconds / 60;
                                    if ($diffCheckoutMinutes <= $admin_panel_settings->after_mins_neglect) {
                                        $is_duplicate_checkout = true;
                                    }
                                }
                                if ($is_duplicate_checkout) {
                                    $logicalType = 2;
                                } else {
                                    if ($type === 2 && date('H:i:s', strtotime($dateTimeAction)) < '12:00:00') {
                                        $logicalType = 2;
                                    } else {
                                        $logicalType = 1;
                                    }
                                }
                            }
                        }
                    }

                    if ($logicalType === 1) {
                        // ----------------------------------------------------
                        // PROCESS CHECK-IN (type == 1)
                        // ----------------------------------------------------
                        // 1. Determine Target Date (day_of_finger_print)
                        $targetDate = date('Y-m-d', strtotime($dateTimeAction));
                        if ($employee['fixed_shift'] == 1 && !empty($shiftData)) {
                            $timeActionTS = strtotime($dateTimeAction);
                            $dateStr = date('Y-m-d', $timeActionTS);
                            $datePrev = date('Y-m-d', strtotime($dateStr . ' -1 day'));
                            $dateCurr = $dateStr;
                            $dateNext = date('Y-m-d', strtotime($dateStr . ' +1 day'));

                            $diffPrev = abs($timeActionTS - strtotime($datePrev . ' ' . $shiftData['start_time']));
                            $diffCurr = abs($timeActionTS - strtotime($dateCurr . ' ' . $shiftData['start_time']));
                            $diffNext = abs($timeActionTS - strtotime($dateNext . ' ' . $shiftData['start_time']));

                            $minDiff = min($diffPrev, $diffCurr, $diffNext);
                            if ($minDiff == $diffPrev) {
                                $targetDate = $datePrev;
                            } elseif ($minDiff == $diffNext) {
                                $targetDate = $dateNext;
                            } else {
                                $targetDate = $dateCurr;
                            }
                        }

                        // 2. Check if a record exists for this target date
                        $exists = AttendanceDeparture::where([
                            'company_id' => $company_id,
                            'employee_id' => $employee['id'],
                            'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                            'day_of_finger_print' => $targetDate,
                        ])->first();

                        if (!empty($exists)) {
                            if ($exists->checkInDateTime == null) {
                                // Update existing empty/absence record with check-in info
                                $dataToUpdate = [
                                    'checkInDate' => date('Y-m-d', strtotime($dateTimeAction)),
                                    'checkInTime' => date('H:i:s', strtotime($dateTimeAction)),
                                    'checkInDateTime' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                    'status_move' => 1,
                                    'absence_hours' => 0,
                                    'cutting_days' => 0,
                                    'notes' => 'من خلال ملف Excel',
                                    'vacation_id' => 0,
                                    'occasion_id' => null,
                                ];

                                // Calculate delay if fixed shift
                                if ($employee['fixed_shift'] == 1 && !empty($shiftData)) {
                                    $shiftStartDateTime = $targetDate . ' ' . $shiftData['start_time'];
                                    if (strtotime($dateTimeAction) > strtotime($shiftStartDateTime)) {
                                        $diffInSeconds = strtotime($dateTimeAction) - strtotime($shiftStartDateTime);
                                        $diffInMinutes = $diffInSeconds / 60;
                                        $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                        if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_delay) {
                                            $dataToUpdate['attendance_delay'] = $fromMinutesIntoDecimalNumber;
                                            
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
                                            if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                                $dataToUpdate['cutting_days'] = 1;
                                            } else {
                                                if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                    $dataToUpdate['cutting_days'] = .5;
                                                } else {
                                                    if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                        $dataToUpdate['cutting_days'] = .25;
                                                    } else {
                                                        $dataToUpdate['cutting_days'] = 0;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $exists->update($dataToUpdate);

                                // Create active movement action
                                $ActionDataToInsert = [
                                    'attendances_departure_id' => $exists->id,
                                    'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                    'employee_id' => $employee['id'],
                                    'dateTimeAction' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                    'company_id' => $company_id,
                                    'type' => 1,
                                    'is_active_with_parent' => '1',
                                    'added_method' => '1',
                                    'added_by' => Auth::id(),
                                    'notes' => 'من خلال ملف Excel',
                                    'attendance_departure_actions_excel_id' => $attendanceDepartureActionsExcel['id'],
                                ];
                                insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                            } else {
                                // A check-in already exists. Check if this new check-in is a duplicate
                                $diffInSeconds = abs(strtotime($dateTimeAction) - strtotime($exists->checkInDateTime));
                                $diffInMinutes = $diffInSeconds / 60;
                                if ($diffInMinutes > $admin_panel_settings->after_mins_neglect) {
                                    // Insert as unapproved duplicate check-in action
                                    $ActionDataToInsert = [
                                        'attendances_departure_id' => $exists->id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'dateTimeAction' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                        'company_id' => $company_id,
                                        'type' => 1,
                                        'is_active_with_parent' => '0',
                                        'added_method' => '1',
                                        'added_by' => Auth::id(),
                                        'notes' => 'من خلال ملف Excel (بصمة مكررة)',
                                        'attendance_departure_actions_excel_id' => $attendanceDepartureActionsExcel['id'],
                                    ];
                                    insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                }
                            }
                        } else {
                            // No record exists for $targetDate, create a new one
                            $dataToInsert2 = [
                                'status_move' => 1,
                                'shift_hours' => $shiftHours,
                                'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                'employee_id' => $employee['id'],
                                'checkInDate' => date('Y-m-d', strtotime($dateTimeAction)),
                                'checkInTime' => date('H:i:s', strtotime($dateTimeAction)),
                                'checkInDateTime' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                'company_id' => $company_id,
                                'added_by' => Auth::id(),
                                'notes' => 'من خلال ملف Excel',
                                'year_and_month' => $this->financeMonthlyCalendar->year_and_month,
                                'employee_branch_id' => $employee->branch_id,
                                'employee_status' => $employee->employment_status,
                                'day_of_finger_print' => $targetDate,
                            ];

                            if ($employee['fixed_shift'] == 1 && !empty($shiftData)) {
                                $shiftStartDateTime = $targetDate . ' ' . $shiftData['start_time'];
                                if (strtotime($dateTimeAction) > strtotime($shiftStartDateTime)) {
                                    $diffInSeconds = strtotime($dateTimeAction) - strtotime($shiftStartDateTime);
                                    $diffInMinutes = $diffInSeconds / 60;
                                    $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                    if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_delay) {
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
                                        if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                            $dataToInsert2['cutting_days'] = 1;
                                        } else {
                                            if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                $dataToInsert2['cutting_days'] = .5;
                                            } else {
                                                if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                    $dataToInsert2['cutting_days'] = .25;
                                                } else {
                                                    $dataToInsert2['cutting_days'] = 0;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (!empty($checkExisitsMainSalaryEmployee)) {
                                $dataToInsert2['main_salary_employee_id'] = $checkExisitsMainSalaryEmployee['id'];
                            }

                            $flagInsertParent = insert(new AttendanceDeparture(), $dataToInsert2, true);
                            if ($flagInsertParent) {
                                $ActionDataToInsert = [
                                    'attendances_departure_id' => $flagInsertParent->id,
                                    'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                    'employee_id' => $employee['id'],
                                    'dateTimeAction' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                    'company_id' => $company_id,
                                    'type' => 1,
                                    'is_active_with_parent' => '1',
                                    'added_method' => '1',
                                    'is_action_made_on_employee' => '0',
                                    'added_by' => Auth::id(),
                                    'notes' => 'من خلال ملف Excel',
                                    'attendance_departure_actions_excel_id' => $attendanceDepartureActionsExcel['id'],
                                ];
                                insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                            }
                        }
                    } elseif ($logicalType === 2) {
                        // ----------------------------------------------------
                        // PROCESS CHECK-OUT (type == 2)
                        // ----------------------------------------------------
                        // Find the last record with check-in <= $dateTimeAction
                        $last = AttendanceDeparture::select('*')->where([
                            'company_id' => $company_id,
                            'employee_id' => $employee['id'],
                            'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                        ])
                            ->where('checkInDateTime', '!=', null)
                            ->where('checkInDateTime', '<=', $dateTimeAction)
                            ->orderBy('id', 'desc')->first();

                        if (!empty($last)) {
                            $lastAttendance = $last->checkInDateTime;
                            $diffInSeconds = strtotime($dateTimeAction) - strtotime($lastAttendance);
                            $diffInMinutes = $diffInSeconds / 60;
                            $diffInHours = $diffInSeconds / 3600;
                            $diffInHours = number_format($diffInHours, 2, '.', '');
                            $diffInMinutes = number_format($diffInMinutes, 2, '.', '');
                            if ($diffInHours < 0) $diffInHours = $diffInHours * -1;
                            if ($diffInMinutes < 0) $diffInMinutes = $diffInMinutes * -1;

                            // Check if this is a duplicate check-out
                            $is_duplicate_checkout = false;
                            if ($last->checkOutDateTime !== null) {
                                $diffCheckoutSeconds = abs(strtotime($dateTimeAction) - strtotime($last->checkOutDateTime));
                                $diffCheckoutMinutes = $diffCheckoutSeconds / 60;
                                if ($diffCheckoutMinutes <= $admin_panel_settings->after_mins_neglect) {
                                    $is_duplicate_checkout = true;
                                }
                            }

                            if (!$is_duplicate_checkout && $diffInMinutes > $admin_panel_settings->after_mins_neglect) {
                                $pairingWindow = $shiftHours + ($admin_panel_settings->after_shift_max_extra_hours ?? 0);
                                $should_update_parent = true;
                                if ($last->checkOutDateTime !== null && $diffInHours > $pairingWindow) {
                                    $should_update_parent = false;
                                }

                                if ($should_update_parent) {
                                    // Update check-out of the last record
                                    $dataToUpdate = [
                                        'checkOutDateTime' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                        'checkOutTime' => date('H:i:s', strtotime($dateTimeAction)),
                                        'checkOutDate' => date('Y-m-d', strtotime($dateTimeAction)),
                                        'total_hours' => $diffInHours,
                                        'vacation_id' => 0,
                                    ];

                                    if ($diffInHours < $shiftHours) {
                                        $dataToUpdate['overtime_hours'] = 0;
                                        $dataToUpdate['absence_hours'] = $shiftHours - $diffInHours;
                                    } else {
                                        $dataToUpdate['overtime_hours'] = $diffInHours - $shiftHours;
                                        $dataToUpdate['absence_hours'] = 0;
                                    }

                                    if ($employee['fixed_shift'] == 1 && !empty($shiftData)) {
                                        $targetDate = $last->day_of_finger_print;
                                        $shiftEndDate = $targetDate;
                                        if ($shiftData['end_time'] < $shiftData['start_time']) {
                                            $shiftEndDate = date('Y-m-d', strtotime($targetDate . ' +1 day'));
                                        }
                                        $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData['end_time'];
                                        if (strtotime($shiftEndDateTime) > strtotime($dateTimeAction)) {
                                            $diffSeconds = strtotime($shiftEndDateTime) - strtotime($dateTimeAction);
                                            $diffMins = $diffSeconds / 60;
                                            $fromMinutesDecimal = number_format($diffMins, 2, '.', '');
                                            if ($fromMinutesDecimal >= $admin_panel_settings->after_minute_calculate_early_departure) {
                                                $dataToUpdate['early_departure'] = $fromMinutesDecimal;
                                            }
                                        }
                                    }

                                    $last->update($dataToUpdate);

                                    // Update existing active check-out actions on this record to be inactive
                                    $ActionDataToUpdate['is_active_with_parent'] = '0';
                                    updateWhere(AttendanceDepartureAction::class, $ActionDataToUpdate, [
                                        'company_id' => $company_id,
                                        'is_active_with_parent' => '1',
                                        'type' => 2,
                                        'attendances_departure_id' => $last->id
                                    ]);

                                    // Insert new active check-out action
                                    $ActionDataToInsert = [
                                        'attendances_departure_id' => $last->id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'dateTimeAction' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                        'company_id' => $company_id,
                                        'type' => 2,
                                        'is_active_with_parent' => '1',
                                        'added_method' => '1',
                                        'added_by' => Auth::id(),
                                        'notes' => 'من خلال ملف Excel',
                                        'attendance_departure_actions_excel_id' => $attendanceDepartureActionsExcel['id'],
                                    ];
                                    insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                } else {
                                    // Last record is closed and new action is outside pairing window.
                                    // Insert new inactive check-out action (unapproved/orphan)
                                    $ActionDataToInsert = [
                                        'attendances_departure_id' => $last->id,
                                        'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                                        'employee_id' => $employee['id'],
                                        'dateTimeAction' => date('Y-m-d H:i:s', strtotime($dateTimeAction)),
                                        'company_id' => $company_id,
                                        'type' => 2,
                                        'is_active_with_parent' => '0',
                                        'added_method' => '1',
                                        'added_by' => Auth::id(),
                                        'notes' => 'من خلال ملف Excel (خارج نافذة الشيفت)',
                                        'attendance_departure_actions_excel_id' => $attendanceDepartureActionsExcel['id'],
                                    ];
                                    insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                }
                            }
                        }
                    } else {
                        // ----------------------------------------------------
                        // FALLBACK: ORIGINAL INTERVAL-BASED LOGIC
                        // ----------------------------------------------------
                        //check if there is an empty record
                        $checkfor_empty_record = getColsWhereRow(new AttendanceDeparture(), ['id', 'status_move'], [
                            'company_id' => $company_id,
                            'employee_id' => $employee['id'],
                            'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                            'day_of_finger_print' => date('Y-m-d', strtotime($dateTimeAction)),
                            'checkInDateTime' => null,
                        ]);
                        if (!empty($checkfor_empty_record)) {
                            $checkfor_empty_record->delete();
                        }
                        //get last record
                        $last = AttendanceDeparture::select('*')->where([
                            'company_id' => $company_id,
                            'employee_id' => $employee['id'],
                            'finance_monthly_calendar_id' => $this->finance_monthly_calendar_id,
                        ])
                            ->where('checkInDateTime', '!=', null)
                            ->where('checkInDateTime', '<=', $dateTimeAction)
                            ->orderBy('id', 'desc')->first();
                        if (!empty($last)) {
                            $lastAttendance = $last->checkInDateTime;
                            $diffInSeconds = strtotime($dateTimeAction) - strtotime($lastAttendance);
                            $diffInMinutes = $diffInSeconds / 60;
                            $diffInMinutes = number_format($diffInMinutes, 2, '.', '');
                            $diffInHours = $diffInSeconds / 3600;
                            $diffInHours = number_format($diffInHours, 2, '.', '');
                            if ($diffInHours < 0) $diffInHours = $diffInHours * -1;
                            if ($diffInMinutes < 0) $diffInMinutes = $diffInMinutes * -1;
                            //check if the last record is the same current record
                            if ($last->checkInDate == date('Y-m-d', strtotime($dateTimeAction))) {
                                if ($diffInMinutes > $admin_panel_settings->after_mins_neglect) {
                                    $dataToUpdate['checkOutDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                    $dataToUpdate['checkOutTime'] = date('H:i:s', strtotime($dateTimeAction));
                                    $dataToUpdate['checkOutDate'] = date('Y-m-d', strtotime($dateTimeAction));
                                    $dataToUpdate['total_hours'] = $diffInHours;
                                    if ($diffInHours < $shiftHours) {
                                        $dataToUpdate['overtime_hours'] = 0;
                                        $dataToUpdate['absence_hours'] = $shiftHours - $diffInHours;
                                    }
                                    if ($diffInHours > $shiftHours) {
                                        $dataToUpdate['overtime_hours'] = $diffInHours - $shiftHours;
                                        $dataToUpdate['absence_hours'] = 0;
                                    }

                                    if ($employee['fixed_shift'] == 1) {

                                           $targetDate = $last->day_of_finger_print;
                                         $shiftEndDate = $targetDate;
                                         if ($shiftData['end_time'] < $shiftData['start_time']) {
                                             $shiftEndDate = date('Y-m-d', strtotime($targetDate . ' +1 day'));
                                         }
                                         $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData['end_time'];
                                         if (strtotime($shiftEndDateTime) > strtotime($dateTimeAction)) {
                                             $diffInSeconds = strtotime($shiftEndDateTime) - strtotime($dateTimeAction);
                                             $diffInMinutes = $diffInSeconds / 60;
                                             $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                             if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_early_departure) {
                                                 $dataToUpdate['early_departure'] = $fromMinutesIntoDecimalNumber;
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
                                                if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                                    $dataToUpdate['cutting_days'] += 1;
                                                } else {
                                                    if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                        $dataToUpdate['cutting_days'] += .5;
                                                    } else {
                                                        if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                            $dataToUpdate['cutting_days'] += .25;
                                                        } else {
                                                            // $dataToUpdate['cutting_days'] = 0;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    $dataToUpdate['vacation_id'] = 0;
                                    $flagUpdateParent = updateWhere(AttendanceDeparture::class, $dataToUpdate, [
                                        'id' => $last->id,
                                        'company_id' => $company_id,

                                    ]);
                                    if ($flagUpdateParent) {
                                        $ActionDataToInsert['attendances_departure_id'] = $last->id;
                                        $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                        $ActionDataToInsert['employee_id'] = $employee['id'];
                                        $ActionDataToInsert['dateTimeAction'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                        $ActionDataToInsert['company_id'] = $company_id;
                                        $ActionDataToInsert['type'] = $type;
                                        $ActionDataToInsert['is_active_with_parent'] = '1';
                                        $ActionDataToInsert['added_method'] = '1';
                                        $ActionDataToInsert['company_id'] = $company_id;
                                        $ActionDataToInsert['added_by'] = Auth::id();
                                        $ActionDataToInsert['notes'] = 'من خلال ملف Excel';
                                        $ActionDataToInsert['attendance_departure_actions_excel_id'] = $attendanceDepartureActionsExcel['id'];
                                        $ActionDataToUpdate['is_active_with_parent'] = '0';
                                        updateWhere(AttendanceDepartureAction::class, $ActionDataToUpdate, [
                                            'company_id' => $company_id,
                                            'is_active_with_parent' => '1',
                                            'type' => $type,
                                            'attendances_departure_id' => $last->id
                                        ]);
                                        insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                    }
                                }
                            } else {
                                // different records
                                if ($diffInHours <= $shiftHours) {
                                    ///////////////////////////
                                    if ($diffInMinutes > $admin_panel_settings->after_mins_neglect) {
                                        $dataToUpdate['checkOutDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                        $dataToUpdate['checkOutTime'] = date('H:i:s', strtotime($dateTimeAction));
                                        $dataToUpdate['checkOutDate'] = date('Y-m-d', strtotime($dateTimeAction));
                                        $dataToUpdate['total_hours'] = $diffInHours;
                                        if ($diffInHours < $shiftHours) {
                                            $dataToUpdate['overtime_hours'] = 0;
                                            $dataToUpdate['absence_hours'] = $shiftHours - $diffInHours;
                                        }
                                        if ($diffInHours > $shiftHours) {
                                            $dataToUpdate['overtime_hours'] = $diffInHours - $shiftHours;
                                            $dataToUpdate['absence_hours'] = 0;
                                        }
                                        if ($employee['fixed_shift'] == 1) {

                                         $targetDate = $last->day_of_finger_print;
                                         $shiftEndDate = $targetDate;
                                         if ($shiftData['end_time'] < $shiftData['start_time']) {
                                             $shiftEndDate = date('Y-m-d', strtotime($targetDate . ' +1 day'));
                                         }
                                         $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData['end_time'];
                                         if (strtotime($shiftEndDateTime) > strtotime($dateTimeAction)) {
                                             $diffInSeconds = strtotime($shiftEndDateTime) - strtotime($dateTimeAction);
                                             $diffInMinutes = $diffInSeconds / 60;
                                             $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                             if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_early_departure) {
                                                $dataToUpdate['early_departure'] = $fromMinutesIntoDecimalNumber;
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
                                                    if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                                        $dataToUpdate['cutting_days'] += 1;
                                                    } else {
                                                        if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                            $dataToUpdate['cutting_days'] += .5;
                                                        } else {
                                                            if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                                $dataToUpdate['cutting_days'] += .25;
                                                            } else {
                                                                // $dataToUpdate['cutting_days'] = 0;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        $dataToUpdate['vacation_id'] = 0;
                                        $flagUpdateParent = updateWhere(AttendanceDeparture::class, $dataToUpdate, [
                                            'id' => $last->id,
                                            'company_id' => $company_id,

                                        ]);
                                        if ($flagUpdateParent) {
                                            $ActionDataToInsert['attendances_departure_id'] = $last->id;
                                            $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                            $ActionDataToInsert['employee_id'] = $employee['id'];
                                            $ActionDataToInsert['dateTimeAction'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                            $ActionDataToInsert['company_id'] = $company_id;
                                            $ActionDataToInsert['type'] = $type;
                                            $ActionDataToInsert['is_active_with_parent'] = '1';
                                            $ActionDataToInsert['added_method'] = '1';
                                            $ActionDataToInsert['company_id'] = $company_id;
                                            $ActionDataToInsert['added_by'] = Auth::id();
                                            $ActionDataToInsert['notes'] = 'من خلال ملف Excel';
                                            $ActionDataToInsert['attendance_departure_actions_excel_id'] = $attendanceDepartureActionsExcel['id'];
                                            $ActionDataToUpdate['is_active_with_parent'] = '0';
                                            updateWhere(AttendanceDepartureAction::class, $ActionDataToUpdate, [
                                                'company_id' => $company_id,
                                                'is_active_with_parent' => '1',
                                                'type' => $type,
                                                'attendances_departure_id' => $last->id
                                            ]);
                                            insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                        }
                                    }
                                    ///////////////////////////
                                } else {
                                    if (($diffInHours - $shiftHours) <= $admin_panel_settings->after_shift_max_extra_hours) {
                                        if ($diffInMinutes > $admin_panel_settings->after_mins_neglect) {
                                            $dataToUpdate['checkOutDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                            $dataToUpdate['checkOutTime'] = date('H:i:s', strtotime($dateTimeAction));
                                            $dataToUpdate['checkOutDate'] = date('Y-m-d', strtotime($dateTimeAction));
                                            $dataToUpdate['total_hours'] = $diffInHours;
                                            if ($diffInHours < $shiftHours) {
                                                $dataToUpdate['overtime_hours'] = 0;
                                                $dataToUpdate['absence_hours'] = $shiftHours - $diffInHours;
                                            }
                                            if ($diffInHours > $shiftHours) {
                                                $dataToUpdate['overtime_hours'] = $diffInHours - $shiftHours;
                                                $dataToUpdate['absence_hours'] = 0;
                                            }
                                            if ($employee['fixed_shift'] == 1) {

                                                   $targetDate = $last->day_of_finger_print;
                                         $shiftEndDate = $targetDate;
                                         if ($shiftData['end_time'] < $shiftData['start_time']) {
                                             $shiftEndDate = date('Y-m-d', strtotime($targetDate . ' +1 day'));
                                         }
                                         $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData['end_time'];
                                         if (strtotime($shiftEndDateTime) > strtotime($dateTimeAction)) {
                                             $diffInSeconds = strtotime($shiftEndDateTime) - strtotime($dateTimeAction);
                                             $diffInMinutes = $diffInSeconds / 60;
                                             $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                             if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_early_departure) {
                                                 $dataToUpdate['early_departure'] = $fromMinutesIntoDecimalNumber;
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
                                                        if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                                            $dataToUpdate['cutting_days'] += 1;
                                                        } else {
                                                            if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                                $dataToUpdate['cutting_days'] += .5;
                                                            } else {
                                                                if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                                    $dataToUpdate['cutting_days'] += .25;
                                                                } else {
                                                                    // $dataToUpdate['cutting_days'] = 0;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $dataToUpdate['vacation_id'] = 0;
                                            $flagUpdateParent = updateWhere(AttendanceDeparture::class, $dataToUpdate, [
                                                'id' => $last->id,
                                                'company_id' => $company_id,

                                            ]);
                                            if ($flagUpdateParent) {
                                                $ActionDataToInsert['attendances_departure_id'] = $last->id;
                                                $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                                $ActionDataToInsert['employee_id'] = $employee['id'];
                                                $ActionDataToInsert['dateTimeAction'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                                $ActionDataToInsert['company_id'] = $company_id;
                                                $ActionDataToInsert['type'] = $type;
                                                $ActionDataToInsert['is_active_with_parent'] = '1';
                                                $ActionDataToInsert['added_method'] = '1';
                                                $ActionDataToInsert['company_id'] = $company_id;
                                                $ActionDataToInsert['added_by'] = Auth::id();
                                                $ActionDataToInsert['notes'] = 'من خلال ملف Excel';
                                                $ActionDataToInsert['attendance_departure_actions_excel_id'] = $attendanceDepartureActionsExcel['id'];
                                                $ActionDataToUpdate['is_active_with_parent'] = '0';
                                                updateWhere(AttendanceDepartureAction::class, $ActionDataToUpdate, [
                                                    'company_id' => $company_id,
                                                    'is_active_with_parent' => '1',
                                                    'type' => $type,
                                                    'attendances_departure_id' => $last->id
                                                ]);
                                                insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                            }
                                        }
                                    } else {
                                        //as new shift 
                                        $dataToInsert2['status_move'] = 1;
                                        $dataToInsert2['shift_hours'] = $shiftHours ?? $employee['daily_work_hours'];
                                        $dataToInsert2['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                        $dataToInsert2['employee_id'] = $employee['id'];
                                        $dataToInsert2['checkInDate'] = date('Y-m-d', strtotime($dateTimeAction));
                                        $dataToInsert2['checkInTime'] = date('H:i:s', strtotime($dateTimeAction));
                                        $dataToInsert2['checkInDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                        $dataToInsert2['company_id'] = $company_id;
                                        $dataToInsert2['added_by'] = Auth::id();
                                        $dataToInsert2['notes'] = 'من خلال ملف Excel';
                                        if ($employee['fixed_shift'] == 1) {

                                             $targetDate = date('Y-m-d', strtotime($dateTimeAction));
                                             $shiftStartDateTime = $targetDate . ' ' . $shiftData['start_time'];
                                             if (strtotime($dateTimeAction) > strtotime($shiftStartDateTime)) {
                                                 $diffInSeconds = strtotime($dateTimeAction) - strtotime($shiftStartDateTime);
                                                $diffInMinutes = $diffInSeconds / 60;
                                                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                                if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_delay) {
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
                                                    if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                                        $dataToInsert2['cutting_days'] = 1;
                                                    } else {
                                                        if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                            $dataToInsert2['cutting_days'] = .5;
                                                        } else {
                                                            if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                                $dataToInsert2['cutting_days'] = .25;
                                                            } else {
                                                                $dataToInsert2['cutting_days'] = 0;
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
                                            'company_id' => $company_id,
                                            'is_archived' => 0,
                                        ]);
                                        if (!empty($mainSalaryEmployee)) {
                                            $dataToInsert2['main_salary_employee_id'] = $mainSalaryEmployee->id;
                                        }
                                        $flagInsertParent = insert(new AttendanceDeparture(), $dataToInsert2, true);
                                        if ($flagInsertParent) {
                                            $ActionDataToInsert['attendances_departure_id'] = $flagInsertParent->id;
                                            $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                            $ActionDataToInsert['employee_id'] = $employee['id'];
                                            $ActionDataToInsert['dateTimeAction'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                            $ActionDataToInsert['company_id'] = $company_id;
                                            $ActionDataToInsert['type'] = $type;
                                            $ActionDataToInsert['is_active_with_parent'] = '1';
                                            $ActionDataToInsert['added_method'] = '1';
                                            $ActionDataToInsert['is_action_made_on_employee'] = '0';
                                            $ActionDataToInsert['company_id'] = $company_id;
                                            $ActionDataToInsert['added_by'] = Auth::id();
                                            $ActionDataToInsert['notes'] = 'من خلال ملف Excel';
                                            $ActionDataToInsert['attendance_departure_actions_excel_id'] = $attendanceDepartureActionsExcel['id'];
                                            insert(new AttendanceDepartureAction(), $ActionDataToInsert);
                                        }
                                    }
                                }
                            }
                        } else {
                            // will consider it as the first finger print in the current opened finance_monthly_calendar_id
                            // will prepare the insert array and will consider it as check in
                            $dataToInsert2['status_move'] = 1;
                            $dataToInsert2['shift_hours'] = $shiftHours ?? $employee['daily_work_hours'];
                            $dataToInsert2['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                            $dataToInsert2['employee_id'] = $employee['id'];
                            $dataToInsert2['checkInDate'] = date('Y-m-d', strtotime($dateTimeAction));
                            $dataToInsert2['checkInTime'] = date('H:i:s', strtotime($dateTimeAction));
                            $dataToInsert2['checkInDateTime'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                            $dataToInsert2['company_id'] = $company_id;
                            $dataToInsert2['added_by'] = Auth::id();
                            $dataToInsert2['notes'] = 'من خلال ملف Excel';
                            if ($employee['fixed_shift'] == 1) {
                                $targetDate = date('Y-m-d', strtotime($dateTimeAction));
                                $shiftStartDateTime = $targetDate . ' ' . $shiftData['start_time'];
                                if (strtotime($dateTimeAction) > strtotime($shiftStartDateTime)) {
                                    $diffInSeconds = strtotime($dateTimeAction) - strtotime($shiftStartDateTime);
                                    $diffInMinutes = $diffInSeconds / 60;
                                    $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                                    if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_delay) {
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
                                        if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                                            $dataToInsert2['cutting_days'] = 1;
                                        } else {
                                            if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                                                $dataToInsert2['cutting_days'] = .5;
                                            } else {
                                                if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                                    $dataToInsert2['cutting_days'] = .25;
                                                } else {
                                                    $dataToInsert2['cutting_days'] = 0;
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
                                'company_id' => $company_id,
                                'is_archived' => 0,
                            ]);
                            if (!empty($mainSalaryEmployee)) {
                                $dataToInsert2['main_salary_employee_id'] = $mainSalaryEmployee->id;
                            }
                            $flagInsertParent = insert(new AttendanceDeparture(), $dataToInsert2, true);
                            if ($flagInsertParent) {
                                $ActionDataToInsert['attendances_departure_id'] = $flagInsertParent->id;
                                $ActionDataToInsert['finance_monthly_calendar_id'] = $this->finance_monthly_calendar_id;
                                $ActionDataToInsert['employee_id'] = $employee['id'];
                                $ActionDataToInsert['dateTimeAction'] = date('Y-m-d H:i:s', strtotime($dateTimeAction));
                                $ActionDataToInsert['company_id'] = $company_id;
                                $ActionDataToInsert['type'] = $type;
                                $ActionDataToInsert['is_active_with_parent'] = '1';
                                $ActionDataToInsert['added_method'] = '1';
                                $ActionDataToInsert['is_action_made_on_employee'] = '0';
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
}

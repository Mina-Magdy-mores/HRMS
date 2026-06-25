<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceDepartureRequest;
use App\Imports\AttendanceDepartureImport;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Employee;
use App\Models\Branche;
use App\Models\Department;
use App\Models\JobsCategory;
use App\Models\AdminPanelSetting;
use App\Models\AttendanceDeparture;
use App\Models\AttendanceDepartureAction;
use App\Models\AttendanceDepartureActionsExcel;
use App\Models\MainSalaryEmployee;
use App\Models\DeductionType;
use App\Models\Occasion;
use App\Models\ShiftsType;
use App\Models\VacationType;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceDepartureController extends Controller
{
    use GeneralTrait;
    public function index()
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendars = get_cols_where_order2_with(FinanceMonthlyCalendar::class, ['financeCalendar'], ['*'], ['company_id' => $company_id], 'finance_yr', 'desc', 'id', 'asc', 12);
        foreach ($financeMonthlyCalendars as $calendar) {
            $calendar->total_opened_months = get_count_where(FinanceMonthlyCalendar::class, ['company_id' => $company_id, 'status' => '1']);
            $calendar->total_prev_months_waiting_to_open = FinanceMonthlyCalendar::where(['company_id' => $company_id, 'status' => '0', 'finance_yr' => $calendar->finance_yr])->where('month_id', '<', $calendar->month_id)->count();
        }

        return view('admin.attendanceDepartures.index', ['financeMonthlyCalendars' => $financeMonthlyCalendars]);
    }

    public function show($id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.attendanceDepartures.index')->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $employees = Employee::with(['job', 'branch', 'department'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->paginate(PAGEINATION_COUNTER);

        $employees_search_list = Employee::select(['id', 'name'])
            ->where('company_id', $company_id)
            ->orderBy('id', 'asc')
            ->get();

        $branches = get_cols_where(Branche::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $departments = get_cols_where(Department::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $jobs = get_cols_where(JobsCategory::class, ['id', 'name'], ['company_id' => $company_id, 'status' => 1], 'id', 'asc');
        $lastUploadedFingerPrint = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'created_at', 'added_by'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $id], 'id', 'DESC');
        if ($lastUploadedFingerPrint) {
            $lastUploadedFingerPrint->load('addedBy');
        }
        $latestActionRecord = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'dateTimeAction'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $id], 'dateTimeAction', 'DESC');

        return view('admin.attendanceDepartures.show', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'employees' => $employees,
            'employees_search_list' => $employees_search_list,
            'branches' => $branches,
            'departments' => $departments,
            'jobs' => $jobs,
            'lastUploadedFingerPrint' => $lastUploadedFingerPrint,
            'latestActionRecord' => $latestActionRecord
        ]);
    }
    public function fingerPrintDetails($id, $finance_monthly_calendar_id)
    {
        $company_id = Auth::user()->company_id;
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $finance_monthly_calendar_id)
            ->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->route('admin.attendanceDepartures.show', ['id' => $finance_monthly_calendar_id])->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
        }

        $employee = Employee::with(['job', 'branch', 'department'])
            ->where('company_id', $company_id)
            ->where('id', $id)
            ->first();
        if (empty($employee)) {
            return redirect()->route('admin.attendanceDepartures.show', ['id' => $finance_monthly_calendar_id])->with('error', 'عفوا غير قادر للوصول الى بيانات الموظف');
        }

        $fingerprintActions = AttendanceDepartureActionsExcel::with(['financeMonthlyCalendar.month', 'addedBy'])
            ->where('company_id', $company_id)
            ->where('employee_id', $id)
            ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
            ->orderBy('dateTimeAction', 'asc')
            ->get();

        $allFingerprintArchive = AttendanceDepartureActionsExcel::with(['financeMonthlyCalendar.month', 'addedBy'])
            ->where('company_id', $company_id)
            ->where('employee_id', $id)
            ->orderBy('dateTimeAction', 'asc')
            ->get();

        $financeMonthlyCalendars = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->orderBy('finance_yr', 'desc')
            ->orderBy('month_id', 'desc')
            ->get();

        return view('admin.attendanceDepartures.finger-print-details', [
            'financeMonthlyCalendar' => $financeMonthlyCalendar,
            'employee' => $employee,
            'fingerprintActions' => $fingerprintActions,
            'allFingerprintArchive' => $allFingerprintArchive,
            'financeMonthlyCalendars' => $financeMonthlyCalendars,
        ]);
    }

    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id_search;
            $branch_id = $request->branch_id_search;
            $department_id = $request->department_id_search;
            $job_id = $request->job_id_search;

            $query = Employee::with(['job', 'branch', 'department'])
                ->where('company_id', $company_id);

            if (!empty($employee_id)) {
                $query->where('id', $employee_id);
            }
            if (!empty($branch_id)) {
                $query->where('branch_id', $branch_id);
            }
            if (!empty($department_id)) {
                $query->where('department_id', $department_id);
            }
            if (!empty($job_id)) {
                $query->where('job_id', $job_id);
            }

            $employees = $query->orderBy('id', 'asc')->paginate(PAGEINATION_COUNTER);
            $financeMonthlyCalendar = FinanceMonthlyCalendar::findOrFail($request->finance_monthly_calendar_id);

            return view('admin.attendanceDepartures.ajaxSearch', compact('employees', 'financeMonthlyCalendar'));
        }
    }

    public function printSearch(Request $request)
    {
        $company_id = Auth::user()->company_id;
        $employee_id = $request->employee_id_search;
        $branch_id = $request->branch_id_search;
        $department_id = $request->department_id_search;
        $job_id = $request->job_id_search;

        $query = Employee::with(['job', 'branch', 'department'])
            ->where('company_id', $company_id);

        if (!empty($employee_id)) {
            $query->where('id', $employee_id);
        }
        if (!empty($branch_id)) {
            $query->where('branch_id', $branch_id);
        }
        if (!empty($department_id)) {
            $query->where('department_id', $department_id);
        }
        if (!empty($job_id)) {
            $query->where('job_id', $job_id);
        }

        $employees = $query->orderBy('id', 'asc')->get();
        $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
            ->where('company_id', $company_id)
            ->where('id', $request->finance_monthly_calendar_id_search)
            ->first();

        $adminPanelSetting = AdminPanelSetting::where('company_id', $company_id)->first();
        $systemData = [
            'system_name' => $adminPanelSetting->company_name ?? '',
            'photo'       => $adminPanelSetting->image ?? '',
            'address'     => $adminPanelSetting->address ?? '',
            'phone'       => $adminPanelSetting->phone ?? '',
            'email'       => $adminPanelSetting->email ?? '',
        ];

        return view('admin.attendanceDepartures.print_search', compact('employees', 'financeMonthlyCalendar', 'systemData'));
    }

    public function store(AttendanceDepartureRequest $request)
    {

        $company_id = Auth::user()->company_id;
        $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;

        $financeMonthlyCalendar = FinanceMonthlyCalendar::select(['*'])
            ->where([
                'company_id' => $company_id,
                'id' => $finance_monthly_calendar_id
            ])->first();

        if (empty($financeMonthlyCalendar)) {
            return redirect()->back()->with('error', 'عفواً، لا يمكن العثور على الشهر المالي المحدد.');
        }

        if ($financeMonthlyCalendar->status != 1) {
            return redirect()->back()->with('error', 'عفواً، لا يمكن رفع البصمة لشهر مالي غير مفتوح.');
        }

        try {
            Excel::import(new AttendanceDepartureImport($financeMonthlyCalendar), $request->file('excel_file'));

            $lastUploadedFingerPrint = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'created_at', 'added_by'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $finance_monthly_calendar_id], 'id', 'DESC');
            if ($lastUploadedFingerPrint) {
                $lastUploadedFingerPrint->load('addedBy');
            }
            $latestActionRecord = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'dateTimeAction'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $finance_monthly_calendar_id], 'dateTimeAction', 'DESC');

            return redirect()->back()->with('success', 'تم رفع البصمات بنجاح.')->with('lastUploadedFingerPrint', $lastUploadedFingerPrint)->with('latestActionRecord', $latestActionRecord);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء معالجة ملف الإكسل: ' . $e->getMessage());
        }
    }

    public function loadFingerPrintGrid(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;

            $financeMonthlyCalendar = FinanceMonthlyCalendar::with('month')
                ->where('company_id', $company_id)
                ->where('id', $finance_monthly_calendar_id)
                ->first();

            if (empty($financeMonthlyCalendar)) {
                return response()->json(['error' => 'عفوا غير قادر للوصول الى بيانات الشهر'], 404);
            }

            $employee = Employee::with(['job', 'branch', 'department'])
                ->where('company_id', $company_id)
                ->where('id', $employee_id)
                ->first();

            if (empty($employee)) {
                return response()->json(['error' => 'عفوا غير قادر للوصول الى بيانات الموظف'], 404);
            }

            // Fetch active occasions
            $occasions = Occasion::where('company_id', $company_id)->where('status', 1)->get();

            // Fetch active deduction types
            $deductionTypes = DeductionType::where('company_id', $company_id)->where('status', 1)->get();

            // Fetch active vacation types
            $vacationTypes = VacationType::where('company_id', $company_id)->where('status', 1)->get();

            // Get last uploaded excel and latest action
            $lastUploadedFingerPrint = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'created_at', 'added_by'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $finance_monthly_calendar_id], 'id', 'DESC');
            if ($lastUploadedFingerPrint) {
                $lastUploadedFingerPrint->load('addedBy');
            }
            $latestActionRecord = get_cols_where_row_orderby(new AttendanceDepartureActionsExcel(), ['id', 'dateTimeAction'], ['company_id' => $company_id, 'finance_monthly_calendar_id' => $finance_monthly_calendar_id], 'dateTimeAction', 'DESC');

            // Get existing daily attendance records
            $attendances = AttendanceDeparture::with('actions')->where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id
            ])->get()->sortBy(function ($att) {
                return ($att->checkInDateTime !== null || $att->checkOutDateTime !== null) ? 1 : 0;
            })->keyBy('day_of_finger_print');

            // If the financial month is open, auto-generate missing day records
            if ($financeMonthlyCalendar->status == 1) {
                // Find main salary employee record
                $mainSalaryEmployee = MainSalaryEmployee::where([
                    'company_id' => $company_id,
                    'employee_id' => $employee_id,
                    'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                    'is_archived' => 0
                ])->first();

                // Fetch admin panel settings for vacation rules
                $adminSetting = AdminPanelSetting::where('company_id', $company_id)->first();

                $startDateTemp = \Carbon\Carbon::parse($financeMonthlyCalendar->start_date_for_calculation);
                $endDateTemp = \Carbon\Carbon::parse($financeMonthlyCalendar->end_date_for_calculation);

                // Get shift hours
                $shift_hours = 0;
                if ($employee->fixed_shift == 1) {
                    $shiftData = ShiftsType::where([
                        'company_id' => $company_id,
                        'id' => $employee->shift_type_id
                    ])->first();
                    if ($shiftData) {
                        $shift_hours = $shiftData->total_hours;
                    }
                } else {
                    $shift_hours = $employee->daily_work_hours ?? 0;
                }

                $inserted_any = false;
                $vacations_assigned_this_run = 0;
                $absences_assigned_this_run = 0;

                DB::transaction(function () use (
                    $startDateTemp,
                    $endDateTemp,
                    $attendances,
                    $employee,
                    $company_id,
                    $finance_monthly_calendar_id,
                    $financeMonthlyCalendar,
                    $mainSalaryEmployee,
                    $occasions,
                    $shift_hours,
                    $adminSetting,
                    &$inserted_any,
                    &$vacations_assigned_this_run,
                    &$absences_assigned_this_run
                ) {
                    for ($date = $startDateTemp->copy(); $date->lte($endDateTemp); $date->addDay()) {
                        $dateStr = $date->format('Y-m-d');

                        // Skip if date is prior to employee's hire date
                        if ($employee->hire_date && $dateStr < $employee->hire_date) {
                            continue;
                        }

                        $attendance = $attendances->get($dateStr);

                        $is_empty_or_new = false;
                        if (!$attendance) {
                            $is_empty_or_new = true;
                        } elseif ($attendance->checkInDateTime == null && $attendance->checkOutDateTime == null && $attendance->is_action_made_on_employee == 0 && $attendance->vacation_id == 0 && $attendance->occasion_id == null) {
                            $is_empty_or_new = true;
                        }

                        if ($is_empty_or_new) {
                            // Find if this date is a holiday/occasion
                            $matchedOccasion = null;
                            foreach ($occasions as $occ) {
                                if ($dateStr >= $occ->from_date && $dateStr <= $occ->to_date) {
                                    $matchedOccasion = $occ;
                                    break;
                                }
                            }

                            $data = [
                                'company_id' => $company_id,
                                'employee_id' => $employee->id,
                                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                                'day_of_finger_print' => $dateStr,
                                'year_and_month' => $financeMonthlyCalendar->year_and_month,
                                'employee_branch_id' => $employee->branch_id,
                                'employee_status' => $employee->employment_status,
                                'added_by' => Auth::id(),
                                'main_salary_employee_id' => $mainSalaryEmployee ? $mainSalaryEmployee->id : null,
                                'shift_hours' => $shift_hours,
                                'total_hours' => 0,
                                'overtime_hours' => 0,
                                'attendance_delay' => 0,
                                'early_departure' => 0,
                                'vacation_id' => 0,
                                'occasion_id' => null,
                                'cutting_days' => 0,
                                'is_action_made_on_employee' => '0',
                            ];

                            if ($matchedOccasion) {
                                $data['occasion_id'] = $matchedOccasion->id;
                                $data['absence_hours'] = 0;
                                $data['notes'] = 'إجازة رسمية: ' . $matchedOccasion->name;
                            } else {
                                // Check if employee is active for vacation and has vacation balance
                                $has_vacation_balance = false;
                                if ($employee->active_for_vacation == 1 && $employee->hire_date && $adminSetting) {
                                    $hireDate = \Carbon\Carbon::parse($employee->hire_date);
                                    $targetDate = \Carbon\Carbon::parse($dateStr);
                                    $daysSinceHire = $hireDate->diffInDays($targetDate, false);

                                    if ($daysSinceHire >= 0) {
                                        $first_balance = (float)($adminSetting->first_balance_begin_vacation ?? 0);
                                        $after_days = (float)($adminSetting->after_days_begin_vacation ?? 0);
                                        $monthly_bal = (float)($adminSetting->monthly_vacation_balance ?? 0);

                                        $accumulated = $first_balance;
                                        if ($daysSinceHire >= $after_days) {
                                            $monthsWorked = $hireDate->diffInMonths($targetDate);
                                            $accumulated += ($monthsWorked * $monthly_bal);
                                        }

                                        $taken = AttendanceDeparture::where('employee_id', $employee->id)
                                            ->where('company_id', $company_id)
                                            ->where('day_of_finger_print', '!=', $dateStr) // Skip current date
                                            ->where(function ($query) {
                                                $query->where('vacation_id', '>', 0)
                                                    ->orWhere(function ($q) {
                                                        $q->where('absence_hours', '>', 0)
                                                            ->where('cutting_days', 0);
                                                    });
                                            })
                                            ->count();

                                        $available_balance = $accumulated - $taken - $vacations_assigned_this_run;
                                        if ($available_balance >= 1) {
                                            $has_vacation_balance = true;
                                        }
                                    }
                                }

                                if ($has_vacation_balance) {
                                    $data['absence_hours'] = $shift_hours;
                                    $data['cutting_days'] = 0; // Paid absence deducted from vacation balance
                                    $data['notes'] = 'غياب تلقائي (خصماً من رصيد الإجازات المتاح)';
                                    $vacations_assigned_this_run++;
                                } else {
                                    // Calculate the absence counter to apply the sanctions from general settings
                                    $existingAbsencesCount = AttendanceDeparture::where([
                                        'employee_id' => $employee->id,
                                        'company_id' => $company_id,
                                        'finance_monthly_calendar_id' => $finance_monthly_calendar_id
                                    ])->where('day_of_finger_print', '!=', $dateStr) // Skip current date
                                        ->where('absence_hours', '>', 0)
                                        ->where('cutting_days', '>', 0) // Only count unpaid absences
                                        ->count();

                                    $current_absence_number = $existingAbsencesCount + $absences_assigned_this_run + 1;

                                    $cutting_days = 1.00;
                                    if ($adminSetting) {
                                        if ($current_absence_number == 1) {
                                            $cutting_days = (float)($adminSetting->sanctions_value_first_absence ?? 1.00);
                                        } elseif ($current_absence_number == 2) {
                                            $cutting_days = (float)($adminSetting->sanctions_value_second_absence ?? 2.00);
                                        } elseif ($current_absence_number == 3) {
                                            $cutting_days = (float)($adminSetting->sanctions_value_third_absence ?? 3.00);
                                        } else {
                                            $cutting_days = (float)($adminSetting->sanctions_value_fourth_absence ?? 5.00);
                                        }
                                    }

                                    $data['absence_hours'] = $shift_hours;
                                    $data['cutting_days'] = $cutting_days;
                                    $data['notes'] = 'غياب تلقائي (غياب رقم ' . $current_absence_number . ' - خصم ' . $cutting_days . ' يوم)';
                                    $absences_assigned_this_run++;
                                }
                            }

                            if (!$attendance) {
                                AttendanceDeparture::create($data);
                            } else {
                                $attendance->update($data);
                            }
                            $inserted_any = true;
                        }
                    }

                    if ($inserted_any && $mainSalaryEmployee) {
                        $this->recalculate_main_salary($mainSalaryEmployee->id);
                    }
                });

                if ($inserted_any) {
                    // Refetch existing daily attendance records
                    $attendances = AttendanceDeparture::with('actions')->where([
                        'company_id' => $company_id,
                        'employee_id' => $employee_id,
                        'finance_monthly_calendar_id' => $finance_monthly_calendar_id
                    ])->get()->sortBy(function ($att) {
                        return ($att->checkInDateTime !== null || $att->checkOutDateTime !== null) ? 1 : 0;
                    })->keyBy('day_of_finger_print');
                }
            }

            // Get action counts from AttendanceDepartureActionsExcel grouped by date
            $actionCounts = AttendanceDepartureActionsExcel::where('employee_id', $employee_id)
                ->where('finance_monthly_calendar_id', $finance_monthly_calendar_id)
                ->where('company_id', $company_id)
                ->select(DB::raw('DATE(dateTimeAction) as action_date'), DB::raw('count(*) as total'))
                ->groupBy(DB::raw('DATE(dateTimeAction)'))
                ->pluck('total', 'action_date');

            // Sync checkIn/checkOut times with approved actions
            $sync_needed = false;
            foreach ($attendances as $att) {
                if ($att->is_action_made_on_employee == 0 && $att->actions->isNotEmpty()) {
                    $approvedCheckIn = $att->actions->first(function ($act) {
                        return $act->type == 1 && $act->is_active_with_parent == 1;
                    });
                    $approvedCheckOut = $att->actions->first(function ($act) {
                        return $act->type == 2 && $act->is_active_with_parent == 1;
                    });

                    $newCheckInTime = $approvedCheckIn ? date('H:i:s', strtotime($approvedCheckIn->dateTimeAction)) : null;
                    $newCheckOutTime = $approvedCheckOut ? date('H:i:s', strtotime($approvedCheckOut->dateTimeAction)) : null;

                    $newCheckInDateTime = $approvedCheckIn ? date('Y-m-d H:i:s', strtotime($approvedCheckIn->dateTimeAction)) : null;
                    $newCheckOutDateTime = $approvedCheckOut ? date('Y-m-d H:i:s', strtotime($approvedCheckOut->dateTimeAction)) : null;

                    $dbCheckInTime = $att->checkInTime ? date('H:i:s', strtotime($att->checkInTime)) : null;
                    $dbCheckOutTime = $att->checkOutTime ? date('H:i:s', strtotime($att->checkOutTime)) : null;

                    if ($newCheckInTime !== $dbCheckInTime || $newCheckOutTime !== $dbCheckOutTime) {
                        $att->update([
                            'checkInTime' => $newCheckInTime,
                            'checkOutTime' => $newCheckOutTime,
                            'checkInDateTime' => $newCheckInDateTime,
                            'checkOutDateTime' => $newCheckOutDateTime,
                        ]);
                        $sync_needed = true;
                    }
                }
            }

            if ($sync_needed) {
                $attendances = AttendanceDeparture::with('actions')->where([
                    'company_id' => $company_id,
                    'employee_id' => $employee_id,
                    'finance_monthly_calendar_id' => $finance_monthly_calendar_id
                ])->get()->sortBy(function ($att) {
                    return ($att->checkInDateTime !== null || $att->checkOutDateTime !== null) ? 1 : 0;
                })->keyBy('day_of_finger_print');
            }

            // Generate dates
            $startDate = \Carbon\Carbon::parse($financeMonthlyCalendar->start_date_for_calculation);
            $endDate = \Carbon\Carbon::parse($financeMonthlyCalendar->end_date_for_calculation);

            $days = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $attendance = $attendances->get($dateStr);
                $days[] = [
                    'date' => $dateStr,
                    'day_name' => $date->locale('ar')->translatedFormat('l'),
                    'attendance' => $attendance,
                    'movements_count' => $actionCounts->get($dateStr, 0)
                ];
            }

            $is_editable = (bool)$financeMonthlyCalendar;

            // Calculate totals
            $totals = [
                'total_hours' => 0,
                'overtime_hours' => 0,
                'absence_hours' => 0,
                'cutting_days' => 0,
                'attendance_delay' => 0,
                'early_departure' => 0,
                'approved_attendance_delay_early_departure' => 0,
                'vacation_summary' => '',
                'occasion_summary' => '',
            ];

            $vacationCounts = [];
            $occasionCounts = [];

            foreach ($days as $day) {
                $att = $day['attendance'];
                if ($att) {
                    $totals['total_hours'] += (float)($att->total_hours ?? 0);
                    $totals['overtime_hours'] += (float)($att->overtime_hours ?? 0);
                    $totals['absence_hours'] += (float)($att->absence_hours ?? 0);
                    $totals['cutting_days'] += (float)($att->cutting_days ?? 0);
                    $totals['attendance_delay'] += (float)($att->attendance_delay ?? 0);
                    $totals['early_departure'] += (float)($att->early_departure ?? 0);
                    $totals['approved_attendance_delay_early_departure'] += (float)($att->approved_attendance_delay_early_departure ?? 0);

                    if (!empty($att->vacation_id) && $att->vacation_id > 0) {
                        if (!isset($vacationCounts[$att->vacation_id])) {
                            $vacationCounts[$att->vacation_id] = 0;
                        }
                        $vacationCounts[$att->vacation_id]++;
                    }

                    if (!empty($att->occasion_id) && $att->occasion_id > 0) {
                        if (!isset($occasionCounts[$att->occasion_id])) {
                            $occasionCounts[$att->occasion_id] = 0;
                        }
                        $occasionCounts[$att->occasion_id]++;
                    }
                }
            }

            $vacationSummary = [];
            foreach ($vacationCounts as $vacId => $count) {
                $vt = $vacationTypes->firstWhere('id', $vacId);
                if ($vt) {
                    $vacationSummary[] = $vt->name . ' (' . $count . ')';
                }
            }
            $totals['vacation_summary'] = implode(' ، ', $vacationSummary);

            $occasionSummary = [];
            foreach ($occasionCounts as $occId => $count) {
                $occ = $occasions->firstWhere('id', $occId);
                if ($occ) {
                    $occasionSummary[] = $occ->name . ' (' . $count . ')';
                }
            }
            $totals['occasion_summary'] = implode(' ، ', $occasionSummary);

            // Return rendered HTML partial
            $html = view('admin.attendanceDepartures.finger-print-grid-table', compact(
                'financeMonthlyCalendar',
                'employee',
                'days',
                'occasions',
                'deductionTypes',
                'vacationTypes',
                'is_editable',
                'totals'
            ))->render();

            return response()->json([
                'html' => $html,
                'last_uploaded_date' => $lastUploadedFingerPrint ? $lastUploadedFingerPrint->created_at->format('Y-m-d h:i A') : '---',
                'last_uploaded_by' => $lastUploadedFingerPrint && $lastUploadedFingerPrint->addedBy ? $lastUploadedFingerPrint->addedBy->name : 'النظام',
                'last_action_date' => $latestActionRecord ? \Carbon\Carbon::parse($latestActionRecord->dateTimeAction)->format('Y-m-d h:i A') : '---'
            ]);
        }
    }

    public function saveFingerPrintRow(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;
            $date = $request->date;

            $financeMonthlyCalendar = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $finance_monthly_calendar_id)
                ->first();

            if (empty($financeMonthlyCalendar)) {
                return response()->json(['error' => 'عفوا غير قادر على تعديل بيانات هذا الشهر المالي'], 400);
            }

            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'day_of_finger_print' => $date
            ])->first();

            if ($attendance && $attendance->is_archived == 1) {
                return response()->json(['error' => 'عفوا لا يمكن التعديل على هذا اليوم لأنه مؤرشف'], 400);
            }

            $employee = Employee::where('company_id', $company_id)
                ->where('id', $employee_id)
                ->first();

            if (empty($employee)) {
                return response()->json(['error' => 'عفوا الموظف غير موجود'], 404);
            }

            // Find main salary employee record
            $mainSalaryEmployee = MainSalaryEmployee::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'is_archived' => 0
            ])->first();

            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'day_of_finger_print' => $date
            ])->first();

            $data = [
                'total_hours' => $request->total_hours !== '' ? $request->total_hours : 0,
                'overtime_hours' => $request->overtime_hours !== '' ? $request->overtime_hours : 0,
                'absence_hours' => $request->absence_hours !== '' ? $request->absence_hours : 0,
                'cutting_days' => $request->cutting_days !== '' ? $request->cutting_days : 0,
                'vacation_id' => $request->vacation_id !== '' ? $request->vacation_id : 0,
                'occasion_id' => $request->occasion_id > 0 ? $request->occasion_id : null,
                'variables' => $request->variables,
                'attendance_delay' => $request->attendance_delay !== '' ? $request->attendance_delay : 0,
                'early_departure' => $request->early_departure !== '' ? $request->early_departure : 0,
                'approved_attendance_delay_early_departure' => $request->approved_attendance_delay_early_departure,
                'is_action_made_on_employee' => $request->is_action_made_on_employee !== '' ? $request->is_action_made_on_employee : '0',
                'notes' => $request->notes,
                'updated_by' => Auth::id()
            ];

            if ($attendance) {
                $attendance->update($data);
            } else {
                $data['company_id'] = $company_id;
                $data['added_by'] = Auth::id();
                $data['main_salary_employee_id'] = $mainSalaryEmployee ? $mainSalaryEmployee->id : null;

                AttendanceDeparture::create($data);
            }

            if ($mainSalaryEmployee) {
                $this->recalculate_main_salary($mainSalaryEmployee->id);
            }

            return response()->json(['success' => 'تم حفظ التعديلات بنجاح']);
        }
    }

    public function getDayMovements(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $date = $request->date;

            // Fetch parent daily record
            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'day_of_finger_print' => $date
            ])->first();

            // Fetch processed movements (on this date, or linked to this day's attendance record)
            $processedActions = AttendanceDepartureAction::with(['excelAction', 'addedBy'])
                ->where('company_id', $company_id)
                ->where('employee_id', $employee_id)
                ->whereDate('dateTimeAction', $date)
                ->get();

            // Fetch raw Excel actions
            $excelActions = AttendanceDepartureActionsExcel::with('addedBy')
                ->where('company_id', $company_id)
                ->where('employee_id', $employee_id)
                ->whereDate('dateTimeAction', $date)
                ->get();

            $linkedExcelIds = $processedActions->pluck('attendance_departure_actions_excel_id')->filter()->toArray();

            $actions = collect();

            // Add all processed actions (approved and unapproved processed ones)
            foreach ($processedActions as $action) {
                $actions->push($action);
            }

            // Add raw Excel actions that are not processed/imported
            foreach ($excelActions as $ea) {
                if (!in_array($ea->id, $linkedExcelIds)) {
                    $act = new AttendanceDepartureAction();
                    $act->dateTimeAction = $ea->dateTimeAction;
                    $act->type = $ea->type;
                    $act->is_active_with_parent = 0; // Unapproved
                    $act->added_method = '1';
                    $act->addedBy = $ea->addedBy;
                    $act->notes = $ea->notes ?? 'من خلال ملف Excel (لم يتم استيرادها)';
                    $act->setRelation('excelAction', $ea);
                    $actions->push($act);
                }
            }

            // Sort actions chronologically by time
            $actions = $actions->sortBy('dateTimeAction')->values();

            $prevDate = date('Y-m-d', strtotime($date . ' -1 day'));
            $nextDate = date('Y-m-d', strtotime($date . ' +1 day'));

            $attendancePrev = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'day_of_finger_print' => $prevDate
            ])->first();

            $attendanceNext = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'day_of_finger_print' => $nextDate
            ])->first();

            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;
            $financeMonthlyCalendar = null;
            if ($finance_monthly_calendar_id) {
                $financeMonthlyCalendar = FinanceMonthlyCalendar::where('company_id', $company_id)
                    ->where('id', $finance_monthly_calendar_id)
                    ->first();
            }
            if (!$financeMonthlyCalendar) {
                $financeMonthlyCalendar = FinanceMonthlyCalendar::where('company_id', $company_id)
                    ->where('start_date_for_calculation', '<=', $date)
                    ->where('end_date_for_calculation', '>=', $date)
                    ->first();
            }

            $is_archived = false;
            if ($attendance && $attendance->is_archived == 1) {
                $is_archived = true;
            }

            $is_editable = $financeMonthlyCalendar && !$is_archived;

            $html = view('admin.attendanceDepartures.finger-print-day-movements', compact(
                'actions',
                'date',
                'attendance',
                'attendancePrev',
                'attendanceNext',
                'is_editable'
            ))->render();

            return response()->json(['html' => $html]);
        }
    }

    public function saveAllFingerPrintRows(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;
            $rows = $request->rows;

            $financeMonthlyCalendar = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $finance_monthly_calendar_id)
                ->first();

            if (empty($financeMonthlyCalendar)) {
                return response()->json(['error' => 'عفوا غير قادر على تعديل بيانات هذا الشهر المالي'], 400);
            }

            $employee = Employee::where('company_id', $company_id)
                ->where('id', $employee_id)
                ->first();

            if (empty($employee)) {
                return response()->json(['error' => 'عفوا الموظف غير موجود'], 404);
            }

            $mainSalaryEmployee = MainSalaryEmployee::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'is_archived' => 0
            ])->first();

            try {
                DB::transaction(function () use ($company_id, $employee_id, $finance_monthly_calendar_id, $rows, $financeMonthlyCalendar, $employee, $mainSalaryEmployee) {
                    foreach ($rows as $row_data) {
                        $date = $row_data['date'];

                        $attendance = AttendanceDeparture::where([
                            'company_id' => $company_id,
                            'employee_id' => $employee_id,
                            'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                            'day_of_finger_print' => $date
                        ])->first();

                        if ($attendance && $attendance->is_archived == 1) {
                            continue;
                        }

                        $data = [
                            'total_hours' => $row_data['total_hours'] !== '' ? $row_data['total_hours'] : 0,
                            'overtime_hours' => $row_data['overtime_hours'] !== '' ? $row_data['overtime_hours'] : 0,
                            'absence_hours' => $row_data['absence_hours'] !== '' ? $row_data['absence_hours'] : 0,
                            'cutting_days' => $row_data['cutting_days'] !== '' ? $row_data['cutting_days'] : 0,
                            'vacation_id' => $row_data['vacation_id'] !== '' ? $row_data['vacation_id'] : 0,
                            'occasion_id' => $row_data['occasion_id'] > 0 ? $row_data['occasion_id'] : null,
                            'variables' => $row_data['variables'],
                            'attendance_delay' => $row_data['attendance_delay'] !== '' ? $row_data['attendance_delay'] : 0,
                            'early_departure' => $row_data['early_departure'] !== '' ? $row_data['early_departure'] : 0,
                            'approved_attendance_delay_early_departure' => $row_data['approved_attendance_delay_early_departure'],
                            'is_action_made_on_employee' => $row_data['is_action_made_on_employee'] !== '' ? $row_data['is_action_made_on_employee'] : '0',
                            'notes' => $row_data['notes'],
                            'updated_by' => Auth::id()
                        ];

                        if ($attendance) {
                            $attendance->update($data);
                        } else {
                            $data['company_id'] = $company_id;
                            $data['employee_id'] = $employee_id;
                            $data['finance_monthly_calendar_id'] = $finance_monthly_calendar_id;
                            $data['day_of_finger_print'] = $date;
                            $data['year_and_month'] = $financeMonthlyCalendar->year_and_month;
                            $data['employee_branch_id'] = $employee->branch_id;
                            $data['employee_status'] = $employee->employment_status;
                            $data['added_by'] = Auth::id();
                            $data['main_salary_employee_id'] = $mainSalaryEmployee ? $mainSalaryEmployee->id : null;

                            AttendanceDeparture::create($data);
                        }
                    }

                    if ($mainSalaryEmployee) {
                        $this->recalculate_main_salary($mainSalaryEmployee->id);
                    }
                });

                return response()->json(['success' => 'تم حفظ جميع التعديلات وإعادة احتساب الراتب بنجاح']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'حدث خطأ أثناء حفظ التعديلات: ' . $e->getMessage()], 500);
            }
        }
    }

    public function updateDayMovements(Request $request)
    {
        if ($request->ajax()) {
            $company_id = Auth::user()->company_id;
            $employee_id = $request->employee_id;
            $finance_monthly_calendar_id = $request->finance_monthly_calendar_id;
            $date = $request->date;

            $financeMonthlyCalendar = FinanceMonthlyCalendar::where('company_id', $company_id)
                ->where('id', $finance_monthly_calendar_id)
                ->first();

            $mainSalaryEmployeeForEdit = MainSalaryEmployee::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id
            ])->first();

            // Find daily attendance record if exists
            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'day_of_finger_print' => $date
            ])->first();

            $is_archived = false;
            if ($attendance && $attendance->is_archived == 1) {
                $is_archived = true;
            }

            if (empty($financeMonthlyCalendar) || $is_archived) {
                return response()->json(['error' => 'عفوا غير قادر على تعديل بيانات هذا اليوم أو اليوم مؤرشف نهائياً'], 400);
            }

            $employee = Employee::where('company_id', $company_id)
                ->where('id', $employee_id)
                ->first();

            if (empty($employee)) {
                return response()->json(['error' => 'عفوا الموظف غير موجود'], 404);
            }

            // Find daily attendance record or create one if it doesn't exist
            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'day_of_finger_print' => $date
            ])->first();

            $mainSalaryEmployee = MainSalaryEmployee::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                'is_archived' => 0
            ])->first();

            if (empty($attendance)) {
                $attendance = AttendanceDeparture::create([
                    'company_id' => $company_id,
                    'employee_id' => $employee_id,
                    'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                    'day_of_finger_print' => $date,
                    'year_and_month' => $financeMonthlyCalendar->year_and_month,
                    'employee_branch_id' => $employee->branch_id,
                    'employee_status' => $employee->employment_status,
                    'added_by' => Auth::id(),
                    'main_salary_employee_id' => $mainSalaryEmployee ? $mainSalaryEmployee->id : null,
                ]);
            }

            // Check-in handling
            $newCheckInDateTime = null;
            if ($request->check_in_date && $request->check_in_time) {
                $newCheckInDateTime = $request->check_in_date . ' ' . $request->check_in_time . ':00';
            }

            // Find existing approved check-in action
            $checkInAction = AttendanceDepartureAction::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'attendances_departure_id' => $attendance->id,
                'type' => 1,
                'is_active_with_parent' => '1'
            ])->first();

            if ($newCheckInDateTime) {
                if ($checkInAction) {
                    $checkInAction->update([
                        'dateTimeAction' => $newCheckInDateTime,
                        'added_method' => 2, // manual
                        'updated_by' => Auth::id(),
                        'notes' => 'تعديل يدوي من شاشة الحركات'
                    ]);
                } else {
                    AttendanceDepartureAction::create([
                        'company_id' => $company_id,
                        'employee_id' => $employee_id,
                        'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                        'attendances_departure_id' => $attendance->id,
                        'dateTimeAction' => $newCheckInDateTime,
                        'type' => 1,
                        'added_method' => 2,
                        'is_active_with_parent' => '1',
                        'added_by' => Auth::id(),
                        'notes' => 'إدخال يدوي من شاشة الحركات'
                    ]);
                }
                $attendance->checkInDateTime = $newCheckInDateTime;
                $attendance->checkInTime = date('H:i:s', strtotime($newCheckInDateTime));
            } else {
                // If cleared
                if ($checkInAction) {
                    $checkInAction->update([
                        'is_active_with_parent' => '0',
                        'updated_by' => Auth::id()
                    ]);
                }
                $attendance->checkInDateTime = null;
                $attendance->checkInTime = null;
            }

            // Check-out handling
            $newCheckOutDateTime = null;
            if ($request->check_out_date && $request->check_out_time) {
                $newCheckOutDateTime = $request->check_out_date . ' ' . $request->check_out_time . ':00';
            }

            // Find existing approved check-out action
            $checkOutAction = AttendanceDepartureAction::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'attendances_departure_id' => $attendance->id,
                'type' => 2,
                'is_active_with_parent' => '1'
            ])->first();

            if ($newCheckOutDateTime) {
                if ($checkOutAction) {
                    $checkOutAction->update([
                        'dateTimeAction' => $newCheckOutDateTime,
                        'added_method' => 2, // manual
                        'updated_by' => Auth::id(),
                        'notes' => 'تعديل يدوي من شاشة الحركات'
                    ]);
                } else {
                    AttendanceDepartureAction::create([
                        'company_id' => $company_id,
                        'employee_id' => $employee_id,
                        'finance_monthly_calendar_id' => $finance_monthly_calendar_id,
                        'attendances_departure_id' => $attendance->id,
                        'dateTimeAction' => $newCheckOutDateTime,
                        'type' => 2,
                        'added_method' => 2,
                        'is_active_with_parent' => '1',
                        'added_by' => Auth::id(),
                        'notes' => 'إدخال يدوي من شاشة الحركات'
                    ]);
                }
                $attendance->checkOutDateTime = $newCheckOutDateTime;
                $attendance->checkOutTime = date('H:i:s', strtotime($newCheckOutDateTime));
            } else {
                // If cleared
                if ($checkOutAction) {
                    $checkOutAction->update([
                        'is_active_with_parent' => '0',
                        'updated_by' => Auth::id()
                    ]);
                }
                $attendance->checkOutDateTime = null;
                $attendance->checkOutTime = null;
            }

            // Save the parent record before recalculating
            $attendance->save();

            // Run the recalculation logic on this daily record
            $this->recalculateAttendanceDay($attendance, $employee, $company_id);

            // Recalculate main salary if exists
            if ($mainSalaryEmployee) {
                $this->recalculate_main_salary($mainSalaryEmployee->id);
            }

            return response()->json(['success' => 'تم التحديث وإعادة الحساب بنجاح']);
        }
    }

    private function recalculateAttendanceDay($attendance, $employee, $company_id)
    {
        $admin_panel_settings = getColsWhereRow(new AdminPanelSetting(), [
            'after_minute_calculate_delay',
            'after_minute_calculate_early_departure',
            'after_minute_quarter_day_cut',
            'after_days_half_day_cut',
            'after_days_allday_day_cut',
            'after_mins_neglect',
            'sanctions_value_first_absence',
            'sanctions_value_second_absence',
            'sanctions_value_third_absence',
            'sanctions_value_fourth_absence',
            'first_balance_begin_vacation',
            'after_days_begin_vacation',
            'monthly_vacation_balance'
        ], ['company_id' => $company_id]);

        if (empty($admin_panel_settings)) {
            $admin_panel_settings = (object)[
                'after_minute_calculate_delay' => 15,
                'after_minute_calculate_early_departure' => 15,
                'after_minute_quarter_day_cut' => 3,
                'after_days_half_day_cut' => 3,
                'after_days_allday_day_cut' => 3,
                'after_mins_neglect' => 0,
                'sanctions_value_first_absence' => 1.00,
                'sanctions_value_second_absence' => 2.00,
                'sanctions_value_third_absence' => 3.00,
                'sanctions_value_fourth_absence' => 5.00,
                'first_balance_begin_vacation' => 0,
                'after_days_begin_vacation' => 0,
                'monthly_vacation_balance' => 0
            ];
        }

        $shiftHours = null;
        $shiftData = null;
        if ($employee->fixed_shift == 1) {
            $shiftData = ShiftsType::where('company_id', $company_id)->find($employee->shift_type_id);
            if ($shiftData) {
                $shiftHours = $shiftData->total_hours;
            }
        } else {
            if ($employee->daily_work_hours > 0) {
                $shiftHours = $employee->daily_work_hours;
            }
        }

        $date = $attendance->day_of_finger_print;
        $checkInDateTime = $attendance->checkInDateTime;
        $checkOutDateTime = $attendance->checkOutDateTime;

        $total_hours = 0;
        $overtime_hours = 0;
        $absence_hours = 0;
        $attendance_delay = 0;
        $early_departure = 0;
        $cutting_days = 0;
        $notes = $attendance->notes;

        $is_vacation_or_holiday = ($attendance->vacation_id > 0 || $attendance->occasion_id > 0);

        if ($checkInDateTime && $checkOutDateTime) {
            $diffInSeconds = strtotime($checkOutDateTime) - strtotime($checkInDateTime);
            $diffInHours = max(0, $diffInSeconds / 3600);
            $total_hours = number_format($diffInHours, 2, '.', '');

            if ($shiftHours !== null) {
                if ($diffInHours < $shiftHours) {
                    $overtime_hours = 0;
                    $absence_hours = number_format($shiftHours - $diffInHours, 2, '.', '');
                } else {
                    $overtime_hours = number_format($diffInHours - $shiftHours, 2, '.', '');
                    $absence_hours = 0;
                }
            }
            if ($notes && str_contains($notes, 'غياب تلقائي')) {
                $notes = 'تم التحديث يدوياً';
            }
        } else {
            // One or both are missing
            if ($is_vacation_or_holiday) {
                $absence_hours = 0;
                $cutting_days = 0;
                $total_hours = 0;
                $overtime_hours = 0;
            } else {
                if (!$checkInDateTime && !$checkOutDateTime) {
                    // Full absence
                    $absence_hours = $shiftHours !== null ? $shiftHours : 0;
                    $total_hours = 0;
                    $overtime_hours = 0;

                    // Match occasion if any exists for this date
                    $occasions = Occasion::where('company_id', $company_id)->where('status', 1)->get();
                    $matchedOccasion = null;
                    foreach ($occasions as $occ) {
                        if ($date >= $occ->from_date && $date <= $occ->to_date) {
                            $matchedOccasion = $occ;
                            break;
                        }
                    }

                    if ($matchedOccasion) {
                        $attendance->occasion_id = $matchedOccasion->id;
                        $absence_hours = 0;
                        $cutting_days = 0;
                        $notes = 'إجازة رسمية: ' . $matchedOccasion->name;
                    } else {
                        // Check if employee is active for vacation and has vacation balance
                        $has_vacation_balance = false;
                        if ($employee->active_for_vacation == 1 && $employee->hire_date) {
                            $hireDate = \Carbon\Carbon::parse($employee->hire_date);
                            $targetDate = \Carbon\Carbon::parse($date);
                            $daysSinceHire = $hireDate->diffInDays($targetDate, false);

                            if ($daysSinceHire >= 0) {
                                $first_balance = (float)($admin_panel_settings->first_balance_begin_vacation ?? 0);
                                $after_days = (float)($admin_panel_settings->after_days_begin_vacation ?? 0);
                                $monthly_bal = (float)($admin_panel_settings->monthly_vacation_balance ?? 0);

                                $accumulated = $first_balance;
                                if ($daysSinceHire >= $after_days) {
                                    $monthsWorked = $hireDate->diffInMonths($targetDate);
                                    $accumulated += ($monthsWorked * $monthly_bal);
                                }

                                $taken = AttendanceDeparture::where('employee_id', $employee->id)
                                    ->where('company_id', $company_id)
                                    ->where('day_of_finger_print', '!=', $date)
                                    ->where(function ($query) {
                                        $query->where('vacation_id', '>', 0)
                                            ->orWhere(function ($q) {
                                                $q->where('absence_hours', '>', 0)
                                                    ->where('cutting_days', 0);
                                            });
                                    })
                                    ->count();

                                $available_balance = $accumulated - $taken;
                                if ($available_balance >= 1) {
                                    $has_vacation_balance = true;
                                }
                            }
                        }

                        if ($has_vacation_balance) {
                            $cutting_days = 0;
                            $notes = 'غياب تلقائي (خصماً من رصيد الإجازات المتاح)';
                        } else {
                            $existingAbsencesCount = AttendanceDeparture::where([
                                'employee_id' => $employee->id,
                                'company_id' => $company_id,
                                'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id
                            ])->where('id', '!=', $attendance->id)
                              ->where('absence_hours', '>', 0)
                              ->where('cutting_days', '>', 0)
                              ->count();

                            $current_absence_number = $existingAbsencesCount + 1;

                            $cutting_days = 1.00;
                            if ($current_absence_number == 1) {
                                $cutting_days = (float)($admin_panel_settings->sanctions_value_first_absence ?? 1.00);
                            } elseif ($current_absence_number == 2) {
                                $cutting_days = (float)($admin_panel_settings->sanctions_value_second_absence ?? 2.00);
                            } elseif ($current_absence_number == 3) {
                                $cutting_days = (float)($admin_panel_settings->sanctions_value_third_absence ?? 3.00);
                            } else {
                                $cutting_days = (float)($admin_panel_settings->sanctions_value_fourth_absence ?? 5.00);
                            }

                            $notes = 'غياب تلقائي (غياب رقم ' . $current_absence_number . ' - خصم ' . $cutting_days . ' يوم)';
                        }
                    }
                } else {
                    // Only check-in or only check-out
                    $absence_hours = $shiftHours !== null ? $shiftHours : 0;
                    $total_hours = 0;
                    $overtime_hours = 0;
                    $cutting_days = 0;
                    if ($notes && str_contains($notes, 'غياب تلقائي')) {
                        $notes = 'حضور/انصراف ناقص';
                    }
                }
            }
        }

        // Delay calculation (if checkInDateTime exists)
        if ($checkInDateTime && $employee->fixed_shift == 1 && !empty($shiftData)) {
            $shiftStartDateTime = $date . ' ' . $shiftData->start_time;
            if (strtotime($checkInDateTime) > strtotime($shiftStartDateTime)) {
                $diffInSeconds = strtotime($checkInDateTime) - strtotime($shiftStartDateTime);
                $diffInMinutes = $diffInSeconds / 60;
                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_delay) {
                    $attendance_delay = $fromMinutesIntoDecimalNumber;

                    $counterCutQuarterDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => .25
                    ])->where('id', '!=', $attendance->id)->count();

                    $counterCutHalfDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => .5
                    ])->where('id', '!=', $attendance->id)->count();

                    $counterCutFullDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => 1
                    ])->where('id', '!=', $attendance->id)->count();

                    if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                        $cutting_days = 1;
                    } else {
                        if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                            $cutting_days = .5;
                        } else {
                            if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                $cutting_days = .25;
                            } else {
                                $cutting_days = 0;
                            }
                        }
                    }
                }
            }
        }

        // Early departure calculation (if checkOutDateTime exists)
        if ($checkOutDateTime && $employee->fixed_shift == 1 && !empty($shiftData)) {
            $shiftEndDate = $date;
            if ($shiftData->end_time < $shiftData->start_time) {
                $shiftEndDate = date('Y-m-d', strtotime($date . ' +1 day'));
            }
            $shiftEndDateTime = $shiftEndDate . ' ' . $shiftData->end_time;
            if (strtotime($shiftEndDateTime) > strtotime($checkOutDateTime)) {
                $diffInSeconds = strtotime($shiftEndDateTime) - strtotime($checkOutDateTime);
                $diffInMinutes = $diffInSeconds / 60;
                $fromMinutesIntoDecimalNumber = number_format($diffInMinutes, 2, '.', '');
                if ($fromMinutesIntoDecimalNumber >= $admin_panel_settings->after_minute_calculate_early_departure) {
                    $early_departure = $fromMinutesIntoDecimalNumber;

                    $counterCutQuarterDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => .25
                    ])->where('id', '!=', $attendance->id)->count();

                    $counterCutHalfDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => .5
                    ])->where('id', '!=', $attendance->id)->count();

                    $counterCutFullDay = AttendanceDeparture::where([
                        'company_id' => $company_id,
                        'finance_monthly_calendar_id' => $attendance->finance_monthly_calendar_id,
                        'employee_id' => $employee->id,
                        'cutting_days' => 1
                    ])->where('id', '!=', $attendance->id)->count();

                    if ($counterCutFullDay >= $admin_panel_settings->after_days_allday_day_cut) {
                        $cutting_days += 1;
                    } else {
                        if ($counterCutHalfDay >= $admin_panel_settings->after_days_half_day_cut) {
                            $cutting_days += .5;
                        } else {
                            if ($counterCutQuarterDay >= $admin_panel_settings->after_minute_quarter_day_cut) {
                                $cutting_days += .25;
                            }
                        }
                    }
                }
            }
        }

        // Update daily record
        $attendance->update([
            'total_hours' => $total_hours,
            'overtime_hours' => $overtime_hours,
            'absence_hours' => $absence_hours,
            'attendance_delay' => $attendance_delay,
            'early_departure' => $early_departure,
            'cutting_days' => $cutting_days,
            'notes' => $notes,
        ]);
    }
}


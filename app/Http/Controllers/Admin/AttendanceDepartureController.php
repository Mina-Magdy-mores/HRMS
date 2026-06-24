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
            return redirect()->route('admin.attendanceDepartures.show',['id' => $finance_monthly_calendar_id])->with('error', 'عفوا غير قادر للوصول الى بيانات الشهر');
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
             ])->get()->sortBy(function($att) {
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
                    $startDateTemp, $endDateTemp, $attendances, $employee, $company_id, $finance_monthly_calendar_id,
                    $financeMonthlyCalendar, $mainSalaryEmployee, $occasions, $shift_hours, $adminSetting,
                    &$inserted_any, &$vacations_assigned_this_run, &$absences_assigned_this_run
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
                        } elseif ($attendance->checkInDateTime == null && $attendance->checkOutDateTime == null && $attendance->is_action_made_on_employee == 0) {
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
                      ])->get()->sortBy(function($att) {
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
                    $approvedCheckIn = $att->actions->first(function($act) {
                        return $act->type == 1 && $act->is_active_with_parent == 1;
                    });
                    $approvedCheckOut = $att->actions->first(function($act) {
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
                ])->get()->sortBy(function($att) {
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

            $is_editable = ($financeMonthlyCalendar->status == 1);

            // Return rendered HTML partial
            $html = view('admin.attendanceDepartures.finger-print-grid-table', compact(
                'financeMonthlyCalendar',
                'employee',
                'days',
                'occasions',
                'deductionTypes',
                'is_editable'
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

            if (empty($financeMonthlyCalendar) || $financeMonthlyCalendar->status != 1) {
                return response()->json(['error' => 'عفوا غير قادر على تعديل بيانات هذا الشهر المالي'], 400);
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
                'variables' => $request->variables > 0 ? $request->variables : null,
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

            // Fetch processed movements
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

            // Fetch parent daily record, previous day, and next day records to determine how the system matched the actions
            $attendance = AttendanceDeparture::where([
                'company_id' => $company_id,
                'employee_id' => $employee_id,
                'day_of_finger_print' => $date
            ])->first();

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

            $html = view('admin.attendanceDepartures.finger-print-day-movements', compact(
                'actions', 
                'date', 
                'attendance', 
                'attendancePrev', 
                'attendanceNext'
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

            if (empty($financeMonthlyCalendar) || $financeMonthlyCalendar->status != 1) {
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

                        $data = [
                            'total_hours' => $row_data['total_hours'] !== '' ? $row_data['total_hours'] : 0,
                            'overtime_hours' => $row_data['overtime_hours'] !== '' ? $row_data['overtime_hours'] : 0,
                            'absence_hours' => $row_data['absence_hours'] !== '' ? $row_data['absence_hours'] : 0,
                            'cutting_days' => $row_data['cutting_days'] !== '' ? $row_data['cutting_days'] : 0,
                            'vacation_id' => $row_data['vacation_id'] !== '' ? $row_data['vacation_id'] : 0,
                            'occasion_id' => $row_data['occasion_id'] > 0 ? $row_data['occasion_id'] : null,
                            'variables' => $row_data['variables'] > 0 ? $row_data['variables'] : null,
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
}

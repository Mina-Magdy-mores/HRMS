@extends('admin.layouts.admin')

@section('title', 'الملف الاحترافي للموظف')
@section('contentHeader')
    <i class="fas fa-id-card-alt text-primary"></i>
    الملف المالي والعملي للموظف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employee-dashboard.index', ['tab' => $activeTab]) }}">لوحة الموظف</a>
@endsection
@section('contentHeaderActive', 'عرض التفاصيل')

@section('content')
<div class="container-fluid">

    <!-- Admin View: Employee Select Dropdown -->
    @if(auth()->user()->is_employee == 0)
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-users-cog"></i>
                استعراض ملفات الموظفين (للمشرفين)
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.employee-dashboard.index') }}" method="GET" class="row">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <div class="col-md-8 col-sm-9">
                    <div class="form-group mb-0">
                        <select name="employee_id" class="form-control select2" required>
                            <option value="">-- اختر موظفاً لعرض ملفه بالكامل --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} (كود الموظف: {{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-3">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm">
                        <i class="fas fa-eye"></i> عرض الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @if(!$employee)
        <div class="alert alert-info shadow-sm text-center">
            <h5><i class="fas fa-info-circle"></i> برجاء اختيار موظف لاستعراض كافة بياناته.</h5>
        </div>
    @else
        <!-- Quick stats cards -->
        <div class="row">
            <!-- Basic Salary -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($employee->salary, 2) }}</h3>
                        <p class="font-weight-bold">الراتب الأساسي (ج.م)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                </div>
            </div>

            <!-- Vacation balance net -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($vacationBalances->remaining_net_balance ?? 0, 2) }} يوم</h3>
                        <p class="font-weight-bold">صافي رصيد الإجازات المتبقي</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-umbrella-beach"></i>
                    </div>
                </div>
            </div>

            <!-- Total sloans/loans unpaid -->
            @php
                $unpaidLoans = $loans->where('is_closed', 0)->sum('amount');
                $unpaidPloans = $ploans->sum('remaining_amount');
                $totalDebt = $unpaidLoans + $unpaidPloans;
            @endphp
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($totalDebt, 2) }}</h3>
                        <p class="font-weight-bold">إجمالي المديونية / السلف المتبقية</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>

            <!-- Job Title -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning shadow-sm">
                    <div class="inner text-white">
                        <h3 style="font-size: 1.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $employee->job->name ?? '---' }}
                        </h3>
                        <p class="font-weight-bold">المسمى الوظيفي</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Section -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow">
                    <div class="card-body">
                        @if($activeTab == 'personal')
                            <!-- Section 1: Personal & Job Details -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-user-circle"></i> البيانات الشخصية والمهنية للموظف
                            </h4>
                            <div class="row">
                                <div class="col-md-3 text-center mb-4">
                                    <div class="p-3 border rounded shadow-sm bg-light">
                                        <i class="fas fa-user-tie fa-7x text-secondary my-3"></i>
                                        <h5 class="font-weight-bold text-dark mb-1">{{ $employee->name }}</h5>
                                        <span class="badge badge-secondary px-3 py-1">كود: {{ $employee->employee_code }}</span>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th class="bg-light text-right" style="width: 30%;">القسم / الإدارة</th>
                                                    <td>{{ $employee->department->name ?? '---' }}</td>
                                                    <th class="bg-light text-right" style="width: 30%;">المسمى الوظيفي</th>
                                                    <td>{{ $employee->job->name ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-right">البريد الإلكتروني</th>
                                                    <td>{{ $employee->email ?? '---' }}</td>
                                                    <th class="bg-light text-right">الهاتف</th>
                                                    <td>{{ $employee->work_telephone ?: ($employee->home_telephone ?: '---') }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-right">تاريخ التعيين</th>
                                                    <td>{{ $employee->hire_date ?? '---' }}</td>
                                                    <th class="bg-light text-right">المؤهل الدراسي</th>
                                                    <td>{{ $employee->qualification->name ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-right">الرقم القومي</th>
                                                    <td>{{ $employee->nationality_number ?? '---' }}</td>
                                                    <th class="bg-light text-right">تاريخ الميلاد</th>
                                                    <td>{{ $employee->birth_date ?? '---' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light text-right">الجنس</th>
                                                    <td>{{ $employee->gender == 1 ? 'ذكر' : ($employee->gender == 2 ? 'أنثى' : '---') }}</td>
                                                    <th class="bg-light text-right">العنوان الحالي</th>
                                                    <td>{{ $employee->home_address ?: ($employee->stable_address ?: '---') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        @elseif($activeTab == 'vacation')
                            <!-- Section 2: Vacation Balances -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-umbrella-beach"></i> أرصدة إجازات الموظف الفعلية
                            </h4>
                            @if(!$vacationBalances)
                                <div class="alert alert-warning text-center">لا توجد سجلات أرصدة إجازات متوفرة لهذا الموظف.</div>
                            @else
                                <div class="row">
                                    <!-- Carryover from previous month -->
                                    <div class="col-md col-sm-6 mb-3">
                                        <div class="info-box shadow-sm border h-100">
                                            <span class="info-box-icon bg-secondary"><i class="fas fa-history"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text font-weight-bold">مرحل من السابق</span>
                                                <span class="info-box-number text-secondary">{{ number_format($vacationBalances->carryover_from_previous_month, 2) }} يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Current month balance -->
                                    <div class="col-md col-sm-6 mb-3">
                                        <div class="info-box shadow-sm border h-100">
                                            <span class="info-box-icon bg-info"><i class="fas fa-plus-circle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text font-weight-bold">رصيد الشهر الحالي</span>
                                                <span class="info-box-number text-info">{{ number_format($vacationBalances->current_month_balance, 2) }} يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Total available balance -->
                                    <div class="col-md col-sm-6 mb-3">
                                        <div class="info-box shadow-sm border h-100">
                                            <span class="info-box-icon bg-primary"><i class="fas fa-calculator"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text font-weight-bold">إجمالي المتاح</span>
                                                <span class="info-box-number text-primary">{{ number_format($vacationBalances->total_available_balance, 2) }} يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Spent balance -->
                                    <div class="col-md col-sm-6 mb-3">
                                        <div class="info-box shadow-sm border h-100">
                                            <span class="info-box-icon bg-danger"><i class="fas fa-calendar-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text font-weight-bold">الرصيد المستهلك</span>
                                                <span class="info-box-number text-danger">{{ number_format($vacationBalances->spent_balance, 2) }} يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Remaining net balance -->
                                    <div class="col-md col-sm-6 mb-3">
                                        <div class="info-box shadow-sm border h-100">
                                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text font-weight-bold">صافي المتبقي</span>
                                                <span class="info-box-number text-success">{{ number_format($vacationBalances->remaining_net_balance, 2) }} يوم</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="font-weight-bold text-secondary mt-4 mb-3">
                                    <i class="fas fa-list-ol"></i> سجل أرصدة الإجازات الشهرية والتاريخية
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped text-center align-middle">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>الشهر والسنة</th>
                                                <th>السنة المالية</th>
                                                <th>مرحل من السابق</th>
                                                <th>رصيد الشهر الحالي</th>
                                                <th>إجمالي المتاح</th>
                                                <th>الرصيد المستهلك</th>
                                                <th>صافي المتبقي</th>
                                                <th>تاريخ التحديث</th>
                                                <th>حالة الأرشفة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vacationBalancesList as $balance)
                                                <tr>
                                                    <td><span class="badge badge-info px-2 py-1">{{ $balance->year_and_month ?: '---' }}</span></td>
                                                    <td>{{ $balance->financial_year ?: '---' }}</td>
                                                    <td class="text-primary font-weight-bold">{{ number_format($balance->carryover_from_previous_month, 2) }} يوم</td>
                                                    <td class="text-success font-weight-bold">{{ number_format($balance->current_month_balance, 2) }} يوم</td>
                                                    <td class="text-info font-weight-bold">{{ number_format($balance->total_available_balance, 2) }} يوم</td>
                                                    <td class="text-danger font-weight-bold">{{ number_format($balance->spent_balance, 2) }} يوم</td>
                                                    <td class="text-warning font-weight-bold" style="font-size: 1.1rem;">{{ number_format($balance->remaining_net_balance, 2) }} يوم</td>
                                                    <td>{{ $balance->updated_at ? $balance->updated_at->format('Y-m-d H:i') : '---' }}</td>
                                                    <td>
                                                        @if($balance->is_archived)
                                                            <span class="badge badge-secondary"><i class="fas fa-lock"></i> مؤرشف</span>
                                                        @else
                                                            <span class="badge badge-success"><i class="fas fa-lock-open"></i> نشط</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @elseif($activeTab == 'attendance')
                            <!-- Section 3: Attendance Logs (Day-by-Day Fingerprint Grid) -->
                            <h4 class="text-primary font-weight-bold mb-3">
                                <i class="fas fa-fingerprint"></i> سجل البصمة والحضور والانصراف التفصيلي
                            </h4>

                            @if($financeMonthlyCalendars->isEmpty())
                                <div class="alert alert-warning text-center">لا توجد شهور مالية معرفة بالنظام لسجل البصمات.</div>
                            @else
                                <!-- Month Switcher dropdown -->
                                <div class="card card-outline card-info shadow-sm mb-4 d-print-none">
                                    <div class="card-body py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-group mb-0">
                                                    <label for="select_finance_monthly_calendar" class="font-weight-bold text-secondary">اختر شهر سجل الراتب المالي لتفاصيل البصمة:</label>
                                                    <select id="select_finance_monthly_calendar" class="form-control select2">
                                                        @foreach ($financeMonthlyCalendars as $calendar)
                                                            <option value="{{ $calendar->id }}" {{ $financeMonthlyCalendar && $calendar->id == $financeMonthlyCalendar->id ? 'selected' : '' }}>
                                                                {{ $calendar->month->name }} ({{ $calendar->finance_yr }})
                                                                @if ($calendar->status == 1)
                                                                    - (مفعل) 🟢
                                                                @elseif ($calendar->status == 0)
                                                                    - (مغلق و فى انتظار الفتح) 🔴
                                                                @else
                                                                    - (مغلق و مؤرشف) 🔒
                                                                @endif
                                                                @if ($calendar->start_date_for_calculation && $calendar->end_date_for_calculation)
                                                                    [من {{ $calendar->start_date_for_calculation }} إلى {{ $calendar->end_date_for_calculation }}]
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-left border-right pr-md-4 mt-2 mt-md-0">
                                                <span class="d-block text-muted small font-weight-bold">آخر تاريخ مسجل لحركة البصمة:</span>
                                                <span class="font-weight-bold text-dark" id="stat_last_action_date" style="font-size: 1rem;">
                                                    <i class="fas fa-clock text-info mr-1"></i> ---
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Day-by-Day Attendance Table Container -->
                                <div id="grid_container">
                                    <div class="text-center py-5">
                                        <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                                        <h5 class="text-muted">جاري تحميل بيانات البصمة اليومية...</h5>
                                    </div>
                                </div>

                                <!-- Day Movements Modal -->
                                <div class="modal fade shadow-lg" id="dayMovementsModal" tabindex="-1" role="dialog" aria-labelledby="dayMovementsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-white">
                                                <h5 class="modal-title font-weight-bold" id="dayMovementsModalLabel">
                                                    <i class="fas fa-walking mr-2"></i>
                                                    حركات البصمة اليومية لليوم: <span id="modal_day_display"></span>
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="إغلاق">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-3" id="modal_movements_content">
                                                <!-- Loaded dynamically via AJAX -->
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">إغلاق</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        @elseif($activeTab == 'loans')
                            <!-- Section 4: Normal Loans -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-hand-holding-usd text-warning"></i> السلف العادية الجارية والتاريخية
                            </h4>
                            @if($loans->isEmpty())
                                <div class="alert alert-warning text-center">لا توجد سلف عادية مسجلة للموظف.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered text-center align-middle">
                                        <thead class="bg-warning text-white">
                                            <tr>
                                                <th>المبلغ</th>
                                                <th>الشهر المالي</th>
                                                <th>الحالة</th>
                                                <th>تاريخ الصرف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($loans as $loan)
                                            <tr>
                                                <td class="font-weight-bold text-dark">{{ number_format($loan->amount, 2) }} ج.م</td>
                                                <td>{{ $loan->finance_yr_and_month ?? '---' }}</td>
                                                <td>
                                                    @if($loan->is_closed == 1)
                                                        <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> مغلقة / تم الخصم</span>
                                                    @else
                                                        <span class="badge badge-warning px-3 py-2 text-white"><i class="fas fa-clock"></i> قيد الانتظار / جارية</span>
                                                    @endif
                                                </td>
                                                <td class="text-muted">{{ $loan->created_at ? $loan->created_at->format('Y-m-d') : '---' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        @elseif($activeTab == 'ploans')
                            <!-- Section 5: PLoans (Permanent Loans) -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-university text-danger"></i> السلف المستديمة وجداول أقساطها
                            </h4>
                            @if($ploans->isEmpty())
                                <div class="alert alert-warning text-center">لا توجد سلف مستديمة مسجلة للموظف.</div>
                            @else
                                <div class="row">
                                    @foreach($ploans as $pl)
                                    <div class="col-md-6 mb-4">
                                        <div class="card card-outline card-danger shadow-sm h-100">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title text-danger font-weight-bold">
                                                    <i class="fas fa-money-check-alt"></i> سلفة بقيمة {{ number_format($pl->amount, 2) }} ج.م
                                                </h5>
                                                <div class="card-tools">
                                                    <span class="badge badge-secondary px-3 py-1">قسط: {{ number_format($pl->installment_amount_monthly, 2) }} ج.م/شهر</span>
                                                </div>
                                            </div>
                                            <div class="card-body p-3 bg-light border-bottom">
                                                <div class="row text-center font-weight-bold">
                                                    <div class="col-4">
                                                        <span class="text-muted small d-block">إجمالي المدفوع</span>
                                                        <span class="text-success">{{ number_format($pl->paid_amount, 2) }} ج.م</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <span class="text-muted small d-block">المتبقي المطلوب</span>
                                                        <span class="text-danger">{{ number_format($pl->remaining_amount, 2) }} ج.م</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <span class="text-muted small d-block">مدة السداد</span>
                                                        <span class="text-info">{{ $pl->number_of_installment_months }} أشهر</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-2">
                                                <small class="font-weight-bold d-block mb-2 text-secondary"><i class="fas fa-calendar-alt"></i> جدول استحقاق الأقساط الشهرية:</small>
                                                <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                                    <table class="table table-bordered table-sm text-center small mb-0 table-striped">
                                                        <thead>
                                                            <tr class="bg-light text-secondary">
                                                                <th>الشهر المالي</th>
                                                                <th>مبلغ القسط</th>
                                                                <th>الحالة</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($pl->mainSalaryEmployeePLoanInstallments as $inst)
                                                            <tr>
                                                                <td class="font-weight-bold">{{ $inst->next_installment_year_and_month }}</td>
                                                                <td class="font-weight-bold text-dark">{{ number_format($inst->amount ?? $inst->installment_amount_monthly, 2) }} ج.م</td>
                                                                <td>
                                                                    @if($inst->installment_status == 1)
                                                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> تم السداد</span>
                                                                    @else
                                                                        <span class="badge badge-warning px-2 py-1 text-white"><i class="fas fa-clock"></i> قيد الانتظار</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif

                        @elseif($activeTab == 'salary')
                            <!-- Section 6: Salary History -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-history"></i> أرشيف الرواتب الشهرية والبدلات المصروفة
                            </h4>
                            @if($salaryHistory->isEmpty())
                                <div class="alert alert-warning text-center">لا يوجد أرشيف رواتب محسوبة لهذا الموظف حتى الآن.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>السنة / الشهر</th>
                                                <th>الراتب الأساسي</th>
                                                <th>إجمالي الاستحقاقات</th>
                                                <th>إجمالي الاستقطاعات</th>
                                                <th>صافي المرتب</th>
                                                <th>حالة الصرف</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salaryHistory as $sal)
                                            <tr>
                                                <td class="font-weight-bold">
                                                    {{ $sal->financeMonthlyCalendar->financeCalendar->finance_yr_desc ?? $sal->financial_year }} / 
                                                    {{ $sal->financeMonthlyCalendar->month->name ?? $sal->year_and_month }}
                                                </td>
                                                <td>{{ number_format($sal->employee_salary, 2) }} ج.م</td>
                                                <td class="text-success font-weight-bold">+{{ number_format($sal->total_benefits, 2) }} ج.م</td>
                                                <td class="text-danger font-weight-bold">-{{ number_format($sal->total_deductions, 2) }} ج.م</td>
                                                <td class="bg-light text-primary font-weight-bold" style="font-size: 1.1rem;">
                                                    {{ number_format($sal->employee_net_salary, 2) }} ج.م
                                                </td>
                                                <td>
                                                    @if($sal->is_disbursed == 1)
                                                        <span class="badge badge-success px-3 py-2">
                                                            <i class="fas fa-check-circle mr-1"></i> تم الصرف
                                                        </span>
                                                    @elseif($sal->is_archived == 1)
                                                        @if($sal->employee_net_salary < 0)
                                                            <span class="badge badge-danger px-3 py-2">
                                                                <i class="fas fa-arrow-circle-down mr-1"></i> مرحل (مدين)
                                                            </span>
                                                        @else
                                                            <span class="badge badge-info px-3 py-2 text-white">
                                                                <i class="fas fa-archive mr-1"></i> معتمد / لم يصرف
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning px-3 py-2 text-white">
                                                            <i class="fas fa-hourglass-half mr-1"></i> قيد المراجعة
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @elseif($activeTab == 'tasks')
                            <!-- Section 7: Employee Tasks -->
                            <h4 class="text-primary font-weight-bold mb-4">
                                <i class="fas fa-tasks text-warning"></i> المهام المسندة للموظف
                            </h4>
                            <!-- Navigation for Active vs Archived in Dashboard tasks -->
                            @php
                                $showArchived = request()->get('show_archived', 0);
                                $filteredTasks = $tasks->where('is_archived', $showArchived);
                            @endphp
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="btn-group w-100 shadow-sm" role="group">
                                        <a href="{{ route('admin.employee-dashboard.index', ['employee_id' => $employeeId, 'tab' => 'tasks', 'show_archived' => 0]) }}" 
                                           class="btn {{ $showArchived == 0 ? 'btn-primary active font-weight-bold' : 'btn-outline-primary' }} w-50">
                                            <i class="fas fa-folder-open mr-1"></i>
                                            المهام النشطة الجارية
                                        </a>
                                        <a href="{{ route('admin.employee-dashboard.index', ['employee_id' => $employeeId, 'tab' => 'tasks', 'show_archived' => 1]) }}" 
                                           class="btn {{ $showArchived == 1 ? 'btn-primary active font-weight-bold' : 'btn-outline-primary' }} w-50">
                                            <i class="fas fa-archive mr-1"></i>
                                            المهام مؤرشفة
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($filteredTasks->isEmpty())
                                <div class="alert alert-warning text-center">لا توجد مهام مسجلة في هذا القسم.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover text-center align-middle">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>عنوان المهمة</th>
                                                <th>محتوى المهمة</th>
                                                <th>حالة الإنجاز</th>
                                                <th>تاريخ الإضافة</th>
                                                <th>بواسطة</th>
                                                @if($showArchived == 1)
                                                    <th>أرشفة بواسطة</th>
                                                    <th>تاريخ الأرشفة</th>
                                                @endif
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($filteredTasks as $task)
                                            <tr>
                                                <td>{{ $task->id }}</td>
                                                <td>{{ $task->title }}</td>
                                                <td style="max-width: 280px; white-space: normal; text-align: right;">
                                                    <div class="font-weight-bold mb-1">{{ $task->content }}</div>
                                                    @if($task->notes)
                                                        <div class="mt-1">
                                                            <small class="text-muted"><i class="fas fa-comment"></i> <strong>ملاحظات:</strong> {{ $task->notes }}</small>
                                                        </div>
                                                    @endif
                                                    @if($task->employee_reply)
                                                        <div class="mt-2 p-1 border rounded bg-light text-right">
                                                            <small class="text-success font-weight-bold"><i class="fas fa-reply"></i> رد الموظف:</small>
                                                            <div class="text-dark small pr-2">{{ $task->employee_reply }}</div>
                                                            @if($task->employee_replied_at)
                                                                <span class="text-muted d-block text-left" style="font-size: 9px; direction: ltr;">
                                                                    {{ \Carbon\Carbon::parse($task->employee_replied_at)->format('Y-m-d H:i') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    @if($showArchived == 0 && auth()->user()->is_employee == 1 && auth()->user()->employee_id == $task->employee_id)
                                                        <div class="mt-2 text-left">
                                                            <button type="button" class="btn btn-xs btn-outline-info" onclick="toggleReplyForm({{ $task->id }})">
                                                                <i class="fas fa-reply"></i> {{ $task->employee_reply ? 'تحديث الرد' : 'إضافة رد الموظف' }}
                                                            </button>
                                                        </div>
                                                        <form id="reply-form-{{ $task->id }}" action="{{ route('admin.employee-tasks.reply', $task->id) }}" method="POST" class="mt-2 text-right" style="display: none;">
                                                            @csrf
                                                            <div class="form-group mb-1">
                                                                <textarea name="employee_reply" class="form-control form-control-sm" rows="2" placeholder="اكتب ردك هنا..." required>{{ $task->employee_reply }}</textarea>
                                                            </div>
                                                            <button class="btn btn-xs btn-success shadow-sm" type="submit">إرسال الرد</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($showArchived == 0 && (check_permission('مهام الموظفين', 'تعديل') || (auth()->user()->is_employee == 1 && auth()->user()->employee_id == $task->employee_id)))
                                                        <a href="{{ route('admin.employee-tasks.toggle-status', $task->id) }}" class="btn-link">
                                                            @if($task->is_completed == 0)
                                                                <span class="badge badge-secondary px-3 py-2 shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                                                    <i class="fas fa-play"></i> لم تبدأ
                                                                </span>
                                                            @elseif($task->is_completed == 1)
                                                                <span class="badge badge-warning px-3 py-2 text-white shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                                                    <i class="fas fa-hourglass-half"></i> قيد العمل
                                                                </span>
                                                            @else
                                                                <span class="badge badge-success px-3 py-2 shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                                                    <i class="fas fa-check-circle"></i> منتهية
                                                                </span>
                                                            @endif
                                                        </a>
                                                    @else
                                                        @if($task->is_completed == 0)
                                                            <span class="badge badge-secondary px-3 py-2">
                                                                <i class="fas fa-play"></i> لم تبدأ
                                                            </span>
                                                        @elseif($task->is_completed == 1)
                                                            <span class="badge badge-warning px-3 py-2 text-white">
                                                                <i class="fas fa-hourglass-half"></i> قيد العمل
                                                            </span>
                                                        @else
                                                            <span class="badge badge-success px-3 py-2">
                                                                <i class="fas fa-check-circle"></i> منتهية
                                                            </span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                                                <td>{{ $task->addedBy->name ?? '---' }}</td>
                                                @if($showArchived == 1)
                                                    <td>{{ $task->archivedBy->name ?? '---' }}</td>
                                                    <td>{{ $task->archived_at ? \Carbon\Carbon::parse($task->archived_at)->format('Y-m-d H:i') : '---' }}</td>
                                                 @endif
                                                 <td>
                                                     <a href="{{ route('admin.employee-tasks.show', $task->id) }}" class="btn btn-sm btn-info" title="عرض التفاصيل والردود">
                                                         <i class="fas fa-eye"></i> عرض التفاصيل
                                                     </a>
                                                 </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            
                            <script>
                            function toggleReplyForm(id) {
                                var form = document.getElementById('reply-form-' + id);
                                if (form) {
                                    if (form.style.display === 'none') {
                                        form.style.display = 'block';
                                    } else {
                                        form.style.display = 'none';
                                    }
                                }
                            }
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('js')
<script>
    function loadEmployeeAttendanceGrid() {
        var calendarId = $('#select_finance_monthly_calendar').val();
        var employeeId = '{{ $employeeId }}';
        
        if (!employeeId || !calendarId) return;

        $('#grid_container').html(`
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-3x text-primary mb-3"></i>
                <h5 class="text-muted">جاري تحميل بيانات البصمة اليومية...</h5>
            </div>
        `);
        
        $.ajax({
            url: '{{ route('admin.attendanceDepartures.finger-print-details.load-grid') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: employeeId,
                finance_monthly_calendar_id: calendarId
            },
            dataType: 'json',
            success: function(response) {
                $('#grid_container').html(response.html);
                
                // Update stats panel
                $('#stat_last_uploaded_date').html('<i class="far fa-calendar-alt text-success mr-1"></i> ' + response.last_uploaded_date);
                $('#stat_last_uploaded_by').text(response.last_uploaded_by);
                $('#stat_last_action_date').html('<i class="fas fa-clock text-info mr-1"></i> ' + response.last_action_date);
            },
            error: function(xhr) {
                $('#grid_container').html(`
                    <div class="alert alert-danger text-center m-4 py-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        حدث خطأ أثناء تحميل البيانات. يرجى إعادة المحاولة.
                    </div>
                `);
            }
        });
    }

    $(document).ready(function() {
        if ($('#select_finance_monthly_calendar').length) {
            loadEmployeeAttendanceGrid();

            // When selected month changes
            $('#select_finance_monthly_calendar').on('change', function() {
                loadEmployeeAttendanceGrid();
            });
        }

        // Load movements modal
        $(document).on('click', '.view-day-movements-btn', function() {
            var btn = $(this);
            var row = btn.closest('tr');
            var date = row.data('date');
            var employeeId = '{{ $employeeId }}';
            var calendarId = $('#select_finance_monthly_calendar').val();
            
            $('#modal_day_display').text(date);
            $('#modal_movements_content').html(`
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-info mb-2"></i>
                    <p class="text-muted">جاري تحميل حركات اليوم...</p>
                </div>
            `);
            
            $('#dayMovementsModal').modal('show');
            
            $.ajax({
                url: '{{ route('admin.attendanceDepartures.finger-print-details.day-movements') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: employeeId,
                    finance_monthly_calendar_id: calendarId,
                    date: date
                },
                dataType: 'json',
                success: function(response) {
                    $('#modal_movements_content').html(response.html);
                },
                error: function(xhr) {
                    $('#modal_movements_content').html(`
                        <div class="alert alert-danger text-center">
                            حدث خطأ أثناء تحميل الحركات. يرجى المحاولة لاحقاً.
                        </div>
                    `);
                }
            });
        });
    });

    function scrollGrid(direction) {
        var container = document.getElementById('gridTableContainer');
        if (!container) return;
        var amount = direction === 'left' ? -350 : 350;
        container.scrollBy({
            left: amount,
            behavior: 'smooth'
        });
    }
</script>
@endsection

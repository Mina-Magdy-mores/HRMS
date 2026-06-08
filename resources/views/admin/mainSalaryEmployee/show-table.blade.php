<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-calendar-alt text-info mr-2"></i>
                        الراتب الأساسي لشهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.main-salary-employee.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> العودة لقائمة الشهور
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-wallet text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الرواتب الأساسية</span>
                    <span class="info-box-number">
                        {{ number_format($mainSalaryEmployees2->sum('employee_salary'), 2) }} <small>ج.م</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-plus-circle text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الاستحقاقات</span>
                    <span class="info-box-number text-success font-weight-bold">
                        {{ number_format($mainSalaryEmployees2->sum('total_benefits'), 2) }} <small>ج.م</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-minus-circle text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الاستقطاعات</span>
                    <span class="info-box-number text-danger font-weight-bold">
                        {{ number_format($mainSalaryEmployees2->sum('total_deductions'), 2) }} <small>ج.م</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-dollar-sign text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي صافي الرواتب</span>
                    <span class="info-box-number text-info font-weight-bold">
                        {{ number_format($mainSalaryEmployees2->sum('employee_net_salary'), 2) }} <small>ج.م</small>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-list mr-2"></i>
                سجل رواتب الموظفين المفصل للشهر
            </h3>
            <div class="card-tools">

                @if ($financeMonthlyCalendar->status == 1)
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                        data-target="#addMainSalaryRecordModal">

                        <i class="fas fa-list-plus"></i>
                        إضافة راتب للموظف
                    </button>
                @endif

            </div>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Search Form -->
            <form action="{{ route('admin.main-salary-employee.print-search') }}" method="POST" target="_blank">
                @csrf
                <input type="hidden" name="finance_monthly_calendar_id_search"
                    value="{{ $financeMonthlyCalendar->id }}">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اسم الموظف</label>
                            <select name="employee_id_search" id="employee_id_search" class="form-control select2">
                                <option value="">اختر اسم الموظف</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الفرع</label>
                            <select name="branch_id_search" id="branch_id_search"
                                class="form-control select2">
                                <option value="">اختر الفرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الإدارة</label>
                            <select name="department_id_search" id="department_id_search"
                                class="form-control select2">
                                <option value="">اختر الإدارة</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الوظيفة</label>
                            <select name="job_id_search" id="job_id_search"
                                class="form-control select2">
                                <option value="">اختر الوظيفة</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job->id }}">
                                        {{ $job->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة التوظيف</label>
                            <select name="employment_status_search" id="employment_status_search"
                                class="form-control select2">
                                <option value="">اختر حالة التوظيف</option>
                                <option value="1">
                                    نشط
                                </option>
                                <option value="0">
                                    غير نشط
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>طريقة الدفع</label>
                            <select name="payment_method_search" id="payment_method_search"
                                class="form-control select2">
                                <option value="">اختر طريقة الدفع</option>
                                <option value="1">
                                    نقداً
                                </option>
                                <option value="2">
                                    تحويل بنكي
                                </option>
                                <option value="3">
                                    شيك
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة الصرف</label>
                            <select name="is_disbursed_search" id="is_disbursed_search" class="form-control select2">
                                <option value="">الكل</option>
                                <option value="1">تم الصرف</option>
                                <option value="0">لم يتم الصرف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة الراتب (الوقف)</label>
                            <select name="payment_on_hold_search" id="payment_on_hold_search"
                                class="form-control select2">
                                <option value="">الكل</option>
                                <option value="0">نشط</option>
                                <option value="1">موقوف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>حالة الأرشفة</label>
                            <select name="is_archived_search" id="is_archived_search" class="form-control select2">
                                <option value="">الكل</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">غير مؤرشف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group w-100 mb-3">
                            <button type="submit" class="btn btn-success btn-block shadow-sm" id="print_button">
                                <i class="fas fa-print mr-1"></i> طباعة كشف الرواتب
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="ajax_responce_search">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>كود الموظف</th>
                                <th>الموظف</th>
                                <th>الفرع/القسم</th>
                                <th>الوظيفة/المنصب</th>
                                <th>الصورة</th>
                                <th>الراتب الأساسي</th>
                                <th>إجمالي الاستحقاقات</th>
                                <th>إجمالي الاستقطاعات</th>
                                <th>صافي الراتب</th>
                                <th>حالة الراتب</th>
                                <th>حالة الصرف</th>
                                <th>حالة الأرشفة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mainSalaryEmployees as $record)
                                <tr>
                                    <td>{{ $loop->iteration + ($mainSalaryEmployees->currentPage() - 1) * $mainSalaryEmployees->perPage() }}
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                            {{ $record->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $record->employee_name }}
                                    </td>
                                    <td class="small text-muted">
                                        {{ $record->branch->name ?? '---' }} <br>
                                        <span class="text-secondary">{{ $record->department->name ?? '---' }}</span>
                                    </td>
                                    <td class="small font-weight-bold">
                                        {{ $record->job->name ?? '---' }}
                                    </td>
                                     <td>
                                    @if($record->employee->image)
                                    <img src="{{ asset('storage/' . $record->employee->image) }}" alt="صورة الموظف"
                                        class="img-thumbnail" style="max-width: 90px; max-height: 90px;">
                                    @else
                                    <span>---</span>
                                    @endif
                                </td>
                                    <td class="font-weight-bold">
                                        {{ number_format($record->employee_salary, 2) }} ج.م
                                    </td>
                                    <td class="text-success font-weight-bold">
                                        {{ number_format($record->total_benefits, 2) }} ج.م
                                    </td>
                                    <td class="text-danger font-weight-bold">
                                        {{ number_format($record->total_deductions, 2) }} ج.م
                                    </td>
                                    <td class="text-primary font-weight-bold" style="font-size: 15px;">
                                        {{ number_format($record->employee_net_salary, 2) }} ج.م
                                    </td>
                                    <td>
                                        @if ($record->payment_on_hold == 1)
                                            <span class="badge badge-danger px-2 py-1">
                                                <i class="fas fa-hand-paper mr-1"></i> موقوف
                                            </span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-play mr-1"></i> نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->is_disbursed == 1)
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-check-circle mr-1"></i> تم الصرف
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="fas fa-hourglass-half mr-1"></i> لم يصرف
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->is_archived == 1)
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-archive mr-1"></i> مؤرشف
                                            </span>
                                        @else
                                            <span class="badge badge-primary px-2 py-1">
                                                <i class="fas fa-archive mr-1"></i> لم يؤرشف
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex ">
                                            <button class="btn btn-info btn-sm show-details shadow-sm m-2"
                                            data-employee-name="{{ $record->employee_name }}"
                                            data-employee-code="{{ $record->employee->employee_code ?? '---' }}"
                                            data-employee-salary="{{ $record->employee_salary ?? 0.0 }}"
                                            data-motivation-amount="{{ $record->motivation_amount ?? 0.0 }}"
                                            data-fixed-allowance="{{ $record->fixed_allowance ?? 0.0 }}"
                                            data-employee-total-allowance="{{ $record->employee_total_allowance ?? 0.0 }}"
                                            data-employee-total-bonus="{{ $record->employee_total_bonus ?? 0.0 }}"
                                            data-employee-additions-payment-total="{{ $record->employee_additions_payment_total ?? 0.0 }}"
                                            data-total-benefits="{{ $record->total_benefits ?? 0.0 }}"
                                            data-social-insurance-amount="{{ $record->social_insurance_amount ?? 0.0 }}"
                                            data-medical-insurance-amount="{{ $record->medical_insurance_amount ?? 0.0 }}"
                                            data-employee-deductions-payment-total="{{ $record->employee_deductions_payment_total ?? 0.0 }}"
                                            data-employee-absences-payment-total="{{ $record->employee_absences_payment_total ?? 0.0 }}"
                                            data-employee-total-deduction-type="{{ $record->employee_total_deduction_type ?? 0.0 }}"
                                            data-monthly-loan-amount="{{ $record->monthly_loan_amount ?? 0.0 }}"
                                            data-permanent-loan-amount="{{ $record->permanent_loan_amount ?? 0.0 }}"
                                            data-total-deductions="{{ $record->total_deductions ?? 0.0 }}"
                                            data-employee-net-salary="{{ $record->employee_net_salary ?? 0.0 }}"
                                            data-employee-rollover-amount="{{ $record->employee_rollover_amount ?? 0.0 }}"
                                            data-is-disbursed="{{ $record->is_disbursed ?? 0 }}"
                                            data-payment-on-hold="{{ $record->payment_on_hold ?? 0 }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if ($record->is_archived == 0)
                                        <button class="btn btn-danger btn-sm deleteMainSalaryRecord m-2" data-id="{{ $record->id }}"
                                            data-employee-id="{{ $record->employee_id }}"
                                            data-finance-monthly-calendar-id="{{ $record->finance_monthly_calendar_id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="alert alert-warning mb-0 text-center py-3">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                            لا توجد سجلات رواتب للموظفين في هذا الشهر المالي حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $mainSalaryEmployees->links() }}
                </div>

            </div>

        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="salaryDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-info-circle mr-1"></i>
                    تفاصيل الراتب للموظف: <span id="modal_employee_name"
                        class="badge badge-light px-3 py-2 text-primary font-weight-bold"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row text-center mb-4">
                    <div class="col-md-6 border-right">
                        <span class="text-muted d-block small">كود الموظف</span>
                        <strong id="modal_employee_code" class="text-secondary font-weight-bold"></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted d-block small">الراتب الأساسي</span>
                        <strong id="modal_employee_salary" class="text-primary font-weight-bold"></strong> <span
                            class="small text-muted">ج.م</span>
                    </div>
                </div>

                <div class="row">
                    <!-- Benefits Section -->
                    <div class="col-md-6 pr-md-3">
                        <div class="card card-outline card-success shadow-none border">
                            <div class="card-header bg-light">
                                <h6 class="card-title text-success font-weight-bold mb-0">
                                    <i class="fas fa-plus-circle mr-1"></i> الاستحقاقات (+)
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-striped mb-0 text-center">
                                    <tbody>
                                        <tr>
                                            <td class="text-right pr-3">الراتب الأساسي</td>
                                            <td id="detail_salary" class="font-weight-bold">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">الحوافز</td>
                                            <td id="detail_motivation" class="font-weight-bold text-success">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">بدلات ثابتة</td>
                                            <td id="detail_fixed_allowance" class="font-weight-bold text-success">0.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">بدلات متغيرة</td>
                                            <td id="detail_variable_allowance" class="font-weight-bold text-success">
                                                0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">المكافآت</td>
                                            <td id="detail_bonus" class="font-weight-bold text-success">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">إضافي الأيام</td>
                                            <td id="detail_additions" class="font-weight-bold text-success">0.00</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-success-light font-weight-bold">
                                            <td class="text-right pr-3 text-success">إجمالي الاستحقاقات</td>
                                            <td id="detail_total_benefits" class="text-success text-center">0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Deductions Section -->
                    <div class="col-md-6 pl-md-3">
                        <div class="card card-outline card-danger shadow-none border">
                            <div class="card-header bg-light">
                                <h6 class="card-title text-danger font-weight-bold mb-0">
                                    <i class="fas fa-minus-circle mr-1"></i> الاستقطاعات (-)
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-striped mb-0 text-center">
                                    <tbody>
                                        <tr>
                                            <td class="text-right pr-3">تأمينات اجتماعية</td>
                                            <td id="detail_social_insurance" class="font-weight-bold text-danger">0.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">تأمين طبي</td>
                                            <td id="detail_medical_insurance" class="font-weight-bold text-danger">
                                                0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">غياب الموظف</td>
                                            <td id="detail_absences" class="font-weight-bold text-danger">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">الخصومات العامة</td>
                                            <td id="detail_deductions" class="font-weight-bold text-danger">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">الجزاءات (العقوبات)</td>
                                            <td id="detail_penalty" class="font-weight-bold text-danger">0.00</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">سلف شهرية</td>
                                            <td id="detail_monthly_loan" class="font-weight-bold text-danger">0.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-3">سلف مستديمة</td>
                                            <td id="detail_permanent_loan" class="font-weight-bold text-danger">0.00
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-danger-light font-weight-bold">
                                            <td class="text-right pr-3 text-danger">إجمالي الاستقطاعات</td>
                                            <td id="detail_total_deductions" class="text-danger text-center">0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Net Salary row -->
                <div class="bg-light p-3 rounded d-flex justify-content-between align-items-center mt-3 border">
                    <div>
                        <span class="text-muted d-block small">الراتب المرحل من الشهر الماضي</span>
                        <strong id="detail_rollover" class="text-secondary">0.00</strong> <small
                            class="text-muted">ج.م</small>
                    </div>
                    <div class="text-right">
                        <span class="text-dark font-weight-bold d-block">صافي الراتب المستحق</span>
                        <h4 id="detail_net_salary" class="text-primary font-weight-bold mb-0">0.00</h4> <small
                            class="text-muted">ج.م</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    <i class="fas fa-times-circle mr-1"></i> إغلاق النافذة
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Main Salary Record Modal -->
<div class="modal fade" id="addMainSalaryRecordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-success text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i>
                    إضافة راتب للموظف يدوياً لشهر {{ $financeMonthlyCalendar->month->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="form-group">
                    <label class="font-weight-bold">اختر الموظف (الذين ليس لديهم سجل مالي هذا الشهر)</label>
                    <select name="add_employee_id" id="add_employee_id" class="form-control select2 w-100">
                        <option value="">-- اختر الموظف --</option>
                        @foreach ($employees_does_not_have_opened_monthly_record as $emp)
                            <option value="{{ $emp->id }}" data-salary="{{ $emp->salary }}" data-payment-per-day="{{ $emp->payment_per_day }}" data-code="{{ $emp->employee_code }}">
                                {{ $emp->name }} (كود: {{ $emp->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Display Selected Employee Info -->
                <div id="add_employee_info_box" class="bg-light p-3 rounded mt-3 border" style="display: none;">
                    <div class="row">
                        <div class="col-6 border-right">
                            <span class="text-muted d-block small">كود الموظف</span>
                            <strong id="add_modal_employee_code" class="text-secondary font-weight-bold"></strong>
                        </div>
                        <div class="col-6 text-center">
                            <span class="text-muted d-block small">الراتب الأساسي</span>
                            <strong id="add_modal_employee_salary" class="text-success font-weight-bold"></strong> <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12 text-center border-top pt-2">
                            <span class="text-muted d-block small">أجر اليوم المالي</span>
                            <strong id="add_modal_employee_per_day" class="text-primary font-weight-bold"></strong> <small class="text-muted">ج.م</small>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-success shadow-sm" id="submit_add_main_salary_record">
                    <i class="fas fa-save mr-1"></i> حفظ وإضافة الموظف
                </button>
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>


<style>
    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.08);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.08);
    }
</style>

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        function initSelect2() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }

        $(document).ready(function() {
            initSelect2();

            $(document).on('change', '#employee_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#branch_id_search', function() {
                ajax_search();
            })
            $(document).on('change', '#department_id_search', function () {
                ajax_search();
            })
            $(document).on('change', '#job_id_search', function () {
                ajax_search();
            })
            $(document).on('change', '#employment_status_search', function () {
                ajax_search();
            })
            $(document).on('change', '#payment_method_search', function () {
                ajax_search();
            })
            $(document).on('change', '#is_disbursed_search', function() {
                ajax_search();
            });
            $(document).on('change', '#payment_on_hold_search', function() {
                ajax_search();
            });
            $(document).on('change', '#is_archived_search', function() {
                ajax_search();
            });
            

            
            function ajax_search() {
                var employee_id_search = $('#employee_id_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var payment_method_search = $('#payment_method_search').val();
                var is_disbursed_search = $('#is_disbursed_search').val();
                var payment_on_hold_search = $('#payment_on_hold_search').val();
                var is_archived_search = $('#is_archived_search').val();
                $.ajax({
                    url: '{{ route('admin.main-salary-employee.ajax-search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        branch_id_search: branch_id_search,
                        department_id_search: department_id_search,
                        job_id_search: job_id_search,
                        employment_status_search: employment_status_search,
                        payment_method_search: payment_method_search,
                        is_disbursed_search: is_disbursed_search,
                        payment_on_hold_search: payment_on_hold_search,
                        is_archived_search: is_archived_search
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        console.log('Error during search');
                    }
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search = $('#employee_id_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var payment_method_search = $('#payment_method_search').val();
                var is_disbursed_search = $('#is_disbursed_search').val();
                var payment_on_hold_search = $('#payment_on_hold_search').val();
                var is_archived_search = $('#is_archived_search').val();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        branch_id_search: branch_id_search,
                        department_id_search: department_id_search,
                        job_id_search: job_id_search,
                        employment_status_search: employment_status_search,
                        payment_method_search: payment_method_search,
                        is_disbursed_search: is_disbursed_search,
                        payment_on_hold_search: payment_on_hold_search,
                        is_archived_search: is_archived_search
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        console.log('Error during pagination');
                    }
                });
            });

            // Handle Show Details Modal
            $(document).on('click', '.show-details', function() {
                var btn = $(this);
                var modal = $('#salaryDetailsModal');

                modal.find('#modal_employee_name').text(btn.data('employee-name'));
                modal.find('#modal_employee_code').text(btn.data('employee-code'));
                modal.find('#modal_employee_salary').text(parseFloat(btn.data('employee-salary')).toFixed(
                    2));

                modal.find('#detail_salary').text(parseFloat(btn.data('employee-salary')).toFixed(2));
                modal.find('#detail_motivation').text(parseFloat(btn.data('motivation-amount')).toFixed(2));
                modal.find('#detail_fixed_allowance').text(parseFloat(btn.data('fixed-allowance')).toFixed(
                    2));
                modal.find('#detail_variable_allowance').text(parseFloat(btn.data(
                    'employee-total-allowance')).toFixed(2));
                modal.find('#detail_bonus').text(parseFloat(btn.data('employee-total-bonus')).toFixed(2));
                modal.find('#detail_additions').text(parseFloat(btn.data(
                    'employee-additions-payment-total')).toFixed(2));
                modal.find('#detail_total_benefits').text(parseFloat(btn.data('total-benefits')).toFixed(
                    2));

                modal.find('#detail_social_insurance').text(parseFloat(btn.data('social-insurance-amount'))
                    .toFixed(2));
                modal.find('#detail_medical_insurance').text(parseFloat(btn.data(
                    'medical-insurance-amount')).toFixed(2));
                modal.find('#detail_absences').text(parseFloat(btn.data('employee-absences-payment-total'))
                    .toFixed(2));
                modal.find('#detail_deductions').text(parseFloat(btn.data(
                    'employee-deductions-payment-total')).toFixed(2));
                modal.find('#detail_penalty').text(parseFloat(btn.data('employee-total-deduction-type'))
                    .toFixed(2));
                modal.find('#detail_monthly_loan').text(parseFloat(btn.data('monthly-loan-amount')).toFixed(
                    2));
                modal.find('#detail_permanent_loan').text(parseFloat(btn.data('permanent-loan-amount'))
                    .toFixed(2));
                modal.find('#detail_total_deductions').text(parseFloat(btn.data('total-deductions'))
                    .toFixed(2));

                modal.find('#detail_rollover').text(parseFloat(btn.data('employee-rollover-amount'))
                    .toFixed(2));
                modal.find('#detail_net_salary').text(parseFloat(btn.data('employee-net-salary')).toFixed(
                    2));

                modal.modal('show');
            });

            // Handle Employee Select change in Add Modal
            $(document).on('change', '#add_employee_id', function() {
                var selected = $(this).find(':selected');
                var employee_id = $(this).val();
                if (employee_id) {
                    var salary = parseFloat(selected.data('salary')).toFixed(2);
                    var per_day = parseFloat(selected.data('payment-per-day')).toFixed(2);
                    var code = selected.data('code');

                    $('#add_modal_employee_code').text(code);
                    $('#add_modal_employee_salary').text(salary);
                    $('#add_modal_employee_per_day').text(per_day);
                    $('#add_employee_info_box').slideDown();
                } else {
                    $('#add_employee_info_box').slideUp();
                }
            });

            // Re-initialize select2 with dropdownParent when modal is shown
            $('#addMainSalaryRecordModal').on('shown.bs.modal', function () {
                $('#add_employee_id').select2({
                    theme: 'bootstrap4',
                    dropdownParent: $('#addMainSalaryRecordModal')
                });
            });

            // Submit Add Main Salary Record Form
            $(document).on('click', '#submit_add_main_salary_record', function() {
                var employee_id = $('#add_employee_id').val();
                if (!employee_id) {
                    alert('من فضلك اختر الموظف أولاً');
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.main-salary-employee.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: employee_id,
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }}
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#addMainSalaryRecordModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message || 'حدث خطأ أثناء الحفظ');
                        }
                    },
                    error: function(xhr) {
                        alert('حدث خطأ غير متوقع أثناء الاتصال بالخادم');
                    }
                });
            });

             // Delete Main Salary Record
            $(document).on('click', '.deleteMainSalaryRecord', function() {
                if (!confirm('هل أنت متأكد من حذف هذا الراتب الاساسي؟')) {
                    return;
                }
                var id = $(this).data('id');
                var employee_id = $(this).data('employee-id');
                var finance_monthly_calendar_id = $(this).data('finance-monthly-calendar-id');

                $.ajax({
                    url: "{{ route('admin.main-salary-employee.destroy') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        employee_id: employee_id,
                        finance_monthly_calendar_id: finance_monthly_calendar_id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            ajax_search()
                        } else {
                            alert(response.message || 'حدث خطأ أثناء الحذف');
                        }
                    },
                    error: function(xhr) {
                        alert('حدث خطأ غير متوقع أثناء الاتصال بالخادم');
                    }
                });
            });
        });
    </script>
@endsection

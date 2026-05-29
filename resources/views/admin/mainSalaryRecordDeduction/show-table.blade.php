<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-calendar-alt text-info mr-2"></i>
                        جزاءات شهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.main-salary-employee-deductions.index') }}"
                        class="btn btn-outline-secondary btn-sm">
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
                    <i class="fas fa-file-invoice-dollar text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي عدد الجزاءات</span>
                    <span class="info-box-number">{{ $mainSalaryEmployeeDeductions->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الجزاءات المعتمدة</span>
                    <span class="info-box-number">
                        {{ $mainSalaryEmployeeDeductions->where('is_approved', 1)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-clock text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">بانتظار الاعتماد</span>
                    <span class="info-box-number">
                        {{ $mainSalaryEmployeeDeductions->where('is_approved', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-calculator text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي المبالغ المستقطعة</span>
                    <span class="info-box-number text-danger font-weight-bold">
                        {{ number_format($mainSalaryEmployeeDeductions->sum('total'), 2) }}
                        <small>ج.م</small>
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
                سجل جزاءات الموظفين المفصل للشهر
            </h3>
            <div class="card-tools">

                @if ($financeMonthlyCalendar->status == 1)
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                        data-target="#addDeductionModal">

                        <i class="fas fa-list-plus"></i>
                        إضافة جزاء جديد
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

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover text-center align-middle">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>كود الموظف</th>
                            <th>الموظف</th>
                            <th>اسم الجزاء / السبب</th>
                            <th>نوع الجزاء</th>
                            <th>القيمة / الأيام</th>
                            <th>إجمالي الخصم</th>
                            <th>الإضافة</th>
                            <th>حالة الاعتماد</th>
                            <th>تاريخ الإضافة</th>
                            <th>أضيف بواسطة</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mainSalaryEmployeeDeductions as $deduction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                        {{ $deduction->employee->employee_code ?? '---' }}
                                    </span>
                                </td>
                                <td class="text-right font-weight-bold">
                                    {{ $deduction->employee->name ?? '---' }}
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold">{{ $deduction->name }}</span>
                                </td>
                                <td>
                                    @if ($deduction->deduction_type == 1)
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fas fa-calendar-times mr-1"></i> خصم أيام
                                        </span>
                                    @elseif ($deduction->deduction_type == 2)
                                        <span class="badge badge-danger px-2 py-1">
                                            <i class="fas fa-fingerprint mr-1"></i> خصم بصمة
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">
                                            غير معروف
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($deduction->amount, 2) }}
                                </td>
                                <td class="text-danger font-weight-bold">
                                    {{ number_format($deduction->total, 2) }} ج.م
                                </td>
                                <td>
                                    @if ($deduction->is_auto == 1)
                                        <span class="badge badge-info px-2 py-1">
                                            <i class="fas fa-robot mr-1"></i> تلقائي
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">
                                            <i class="fas fa-keyboard mr-1"></i> يدوي
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($deduction->is_approved == 1)
                                        <span class="badge badge-success px-2 py-1"
                                            title="اعتمد بواسطة: {{ optional($deduction->approvedBy)->name ?? '---' }} في {{ $deduction->approved_at }}">
                                            <i class="fas fa-check-double mr-1"></i> معتمد
                                        </span>
                                    @else
                                        <span class="badge badge-warning px-2 py-1">
                                            <i class="fas fa-hourglass-half mr-1"></i> قيد الانتظار
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small text-muted d-block">
                                        {{ $deduction->created_at ? $deduction->created_at->format('Y-m-d') : '---' }}
                                    </span>
                                    <span class="small text-muted d-block font-italic">
                                        {{ $deduction->created_at ? $deduction->created_at->format('h:i A') : '' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-secondary px-2 py-1">
                                        {{ optional($deduction->addedBy)->name ?? '---' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="small font-italic text-secondary">
                                        {{ $deduction->notes ?? '---' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning mb-0 text-center py-3">
                                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                        لا توجد سجلات جزاءات للموظفين في هذا الشهر المالي حالياً.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

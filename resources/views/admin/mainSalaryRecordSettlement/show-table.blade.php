<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-balance-scale text-info mr-2"></i>
                        تسويات شهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.main-salary-employee-settlements.index') }}"
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
                    <span class="info-box-text">إجمالي التسويات</span>
                    <span class="info-box-number">{{ $mainSalaryEmployeeSettlements2->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-plus-circle text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي مبالغ الإضافة</span>
                    <span class="info-box-number text-success font-weight-bold">
                        {{ number_format($mainSalaryEmployeeSettlements2->sum('total_amount_for_addition'), 2) }}
                        <small>ج.م</small>
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
                    <span class="info-box-text">إجمالي مبالغ الخصم</span>
                    <span class="info-box-number text-danger font-weight-bold">
                        {{ number_format($mainSalaryEmployeeSettlements2->sum('total_amount_for_deduction'), 2) }}
                        <small>ج.م</small>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-wallet text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">صافي التسويات</span>
                    <span class="info-box-number text-info font-weight-bold">
                        {{ number_format($mainSalaryEmployeeSettlements2->sum('final_total_amount'), 2) }}
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
                سجل تسويات رواتب الموظفين المؤرشفة للشهر
            </h3>
            <div class="card-tools">
                @if ($financeMonthlyCalendar->status == 2)
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                        data-target="#addSettlementModal">
                        <i class="fas fa-plus"></i>
                        إضافة تسوية جديدة
                    </button>
                @endif
            </div>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close data-dismiss-btn" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="close data-dismiss-btn" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.main-salary-employee-settlements.print-search') }}" method="POST"
                target="_blank" class="mb-4">
                @csrf
                <input type="hidden" name="finance_monthly_calendar_id_search"
                    value="{{ $financeMonthlyCalendar->id }}">
                
                <div class="row align-items-end">
                    <div class="col-md-4 form-group">
                        <label>بحث باسم الموظف</label>
                        <select name="employee_id_search" id="employee_id_search" class="form-control select2">
                            <option value="">كل الموظفين</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <label>حالة الأرشفة</label>
                        <select name="is_archived_search" id="is_archived_search" class="form-control select2">
                            <option value="" selected>الكل</option>
                            <option value="1">مؤرشفة</option>
                            <option value="0">غير مؤرشفة</option>
                        </select>
                    </div>

                    <div class="col-md-4 form-group">
                        <button type="submit" class="btn btn-secondary shadow-sm">
                            <i class="fas fa-print"></i> طباعة البحث
                        </button>
                    </div>
                </div>
            </form>

            <div id="ajax_responce_search">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>اسم الموظف</th>
                                <th>أجر اليوم</th>
                                <th>إجمالي الإضافة (+)</th>
                                <th>إجمالي الخصم (-)</th>
                                <th>صافي التسوية</th>
                                <th>تاريخ الإضافة</th>
                                <th>بواسطة</th>
                                <th>ملاحظات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mainSalaryEmployeeSettlements as $settlement)
                                <tr>
                                    <td>{{ $settlement->employee->name }}</td>
                                    <td>{{ number_format($settlement->employee_per_day_salary, 2) }} ج.م</td>
                                    <td class="text-success font-weight-bold">+{{ number_format($settlement->total_amount_for_addition, 2) }} ج.م</td>
                                    <td class="text-danger font-weight-bold">-{{ number_format($settlement->total_amount_for_deduction, 2) }} ج.م</td>
                                    <td class="@if($settlement->final_total_amount >= 0) text-primary @else text-danger @endif font-weight-bold">
                                        {{ number_format($settlement->final_total_amount, 2) }} ج.م
                                    </td>
                                    <td>{{ $settlement->created_at->format('Y-m-d') }}</td>
                                    <td>{{ $settlement->addedBy->name }}</td>
                                    <td>{{ $settlement->notes }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success show-settlement-details-btn mr-1"
                                            data-id="{{ $settlement->id }}" title="عرض التفاصيل الكاملة">
                                            <i class="fas fa-eye"></i> تفاصيل
                                        </button>
                                        @if ($financeMonthlyCalendar->status == 2)
                                            <button type="button" class="btn btn-sm btn-info edit-settlement"
                                                data-id="{{ $settlement->id }}">
                                                <i class="fas fa-edit"></i> تعديل
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-settlement-btn"
                                                data-id="{{ $settlement->id }}">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="alert alert-warning mb-0">
                                            لا توجد تسويات مسجلة لهذا الشهر.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div id="ajax-pagination" class="mt-3">
                    {{ $mainSalaryEmployeeSettlements->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- ADD SETTLEMENT MODAL -->
    <div class="modal fade" id="addSettlementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-plus"></i> إضافة تسوية جديدة لموظف مؤرشف راتبه</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addSettlementForm">
                        @csrf
                        <input type="hidden" name="finance_monthly_calendar_id" value="{{ $financeMonthlyCalendar->id }}">
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>اسم الموظف</label>
                                <select name="employee_id" id="add_employee_id" class="form-control select2" style="width: 100%;">
                                    <option value="">اختر الموظف</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" data-payment-per-day="{{ $emp->payment_per_day }}" data-salary="{{ $emp->salary }}">{{ $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>الراتب الأساسي الافتراضي</label>
                                <input type="text" id="add_employee_salary" class="form-control" readonly value="0.00">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>أجر اليوم الواحد</label>
                                <input type="text" name="employee_per_day_salary" id="add_employee_per_day_salary" class="form-control" readonly value="0.00">
                            </div>
                        </div>

                        <!-- Additions Section -->
                        <h6 class="text-success mt-3 font-weight-bold"><i class="fas fa-plus-circle"></i> تسويات الإضافة والمنح والمكافئات المباشرة (+)</h6>
                        <hr class="border-success mt-1">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>أيام عمل غير مسجلة (<span id="add_working_days_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="working_days_number" id="add_working_days_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام عمل إضافية (<span id="add_extra_working_days_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="extra_working_days_number" id="add_extra_working_days_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام غياب مستردة (<span id="add_absent_days_back_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="absent_days_back_number" id="add_absent_days_back_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام خصم مستردة (<span id="add_deducted_days_restored_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="deducted_days_restored_number" id="add_deducted_days_restored_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>فروقات رواتب (+)</label>
                                <input type="number" step="0.01" name="different_in_salary_amount" id="add_different_in_salary_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>مكافأة مباشرة (+)</label>
                                <input type="number" step="0.01" name="bonus_amount" id="add_bonus_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>بدل مالي مباشر (+)</label>
                                <input type="number" step="0.01" name="allowance_amount" id="add_allowance_amount" class="form-control add-calc" value="0">
                            </div>
                        </div>

                        <!-- Deductions Section -->
                        <h6 class="text-danger mt-3 font-weight-bold"><i class="fas fa-minus-circle"></i> تسويات الخصومات والجزاءات والقروض (-)</h6>
                        <hr class="border-danger mt-1">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>أيام غياب (<span id="add_absent_days_amount_span" class="text-danger font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="absent_days_number" id="add_absent_days_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام خصم جزاء (<span id="add_deducted_days_amount_span" class="text-danger font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="deducted_days_number" id="add_deducted_days_number" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم مالي مباشر (-)</label>
                                <input type="number" step="0.01" name="salary_deduction_amount" id="add_salary_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصومات أخرى (-)</label>
                                <input type="number" step="0.01" name="others_salary_deduction_amount" id="add_others_salary_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم تأمين طبي (-)</label>
                                <input type="number" step="0.01" name="medical_insurance_deduction_amount" id="add_medical_insurance_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم سلفة شهرية (-)</label>
                                <input type="number" step="0.01" name="monthly_loan_deduction_amount" id="add_monthly_loan_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>قسط سلفة مستديمة (-)</label>
                                <input type="number" step="0.01" name="permanent_loan_deduction_amount" id="add_permanent_loan_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم جزاءات وتحقيقات (-)</label>
                                <input type="number" step="0.01" name="penalty_deduction_amount" id="add_penalty_deduction_amount" class="form-control add-calc" value="0">
                            </div>
                        </div>

                        <!-- Summary Totals Block -->
                        <div class="row mt-4 p-3 bg-light rounded border">
                            <div class="col-md-4 text-center">
                                <h6 class="text-success font-weight-bold">إجمالي الإضافات</h6>
                                <input type="text" id="add_total_amount_for_addition" class="form-control text-center font-weight-bold text-success" readonly value="0.00">
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="text-danger font-weight-bold">إجمالي الخصومات</h6>
                                <input type="text" id="add_total_amount_for_deduction" class="form-control text-center font-weight-bold text-danger" readonly value="0.00">
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="text-primary font-weight-bold">صافي القيمة النهائية للتسوية</h6>
                                <input type="text" id="add_final_total_amount" class="form-control text-center font-weight-bold text-primary" readonly value="0.00">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>ملاحظات أو سبب التسوية</label>
                            <textarea name="notes" id="add_notes" class="form-control" rows="2" placeholder="اكتب هنا تفاصيل وسبب عمل التسوية للموظف..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="submit_add_settlement">حفظ وتسميع التسوية</button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT SETTLEMENT MODAL -->
    <div class="modal fade" id="editSettlementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit"></i> تعديل تسوية مالية</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editSettlementForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_settlement_id">
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>اسم الموظف</label>
                                <select name="employee_id" id="edit_employee_id" class="form-control" readonly disabled>
                                    <!-- Populated dynamically via JS -->
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>الراتب الأساسي الافتراضي</label>
                                <input type="text" id="edit_employee_salary" class="form-control" readonly value="0.00">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>أجر اليوم الواحد</label>
                                <input type="text" name="employee_per_day_salary" id="edit_employee_per_day_salary" class="form-control" readonly value="0.00">
                            </div>
                        </div>

                        <!-- Additions Section -->
                        <h6 class="text-success mt-3 font-weight-bold"><i class="fas fa-plus-circle"></i> تسويات الإضافة والمنح والمكافئات المباشرة (+)</h6>
                        <hr class="border-success mt-1">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>أيام عمل غير مسجلة (<span id="edit_working_days_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="working_days_number" id="edit_working_days_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام عمل إضافية (<span id="edit_extra_working_days_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="extra_working_days_number" id="edit_extra_working_days_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام غياب مستردة (<span id="edit_absent_days_back_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="absent_days_back_number" id="edit_absent_days_back_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام خصم مستردة (<span id="edit_deducted_days_restored_amount_span" class="text-success font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="deducted_days_restored_number" id="edit_deducted_days_restored_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>فروقات رواتب (+)</label>
                                <input type="number" step="0.01" name="different_in_salary_amount" id="edit_different_in_salary_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>مكافأة مباشرة (+)</label>
                                <input type="number" step="0.01" name="bonus_amount" id="edit_bonus_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>بدل مالي مباشر (+)</label>
                                <input type="number" step="0.01" name="allowance_amount" id="edit_allowance_amount" class="form-control edit-calc" value="0">
                            </div>
                        </div>

                        <!-- Deductions Section -->
                        <h6 class="text-danger mt-3 font-weight-bold"><i class="fas fa-minus-circle"></i> تسويات الخصومات والجزاءات والقروض (-)</h6>
                        <hr class="border-danger mt-1">
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>أيام غياب (<span id="edit_absent_days_amount_span" class="text-danger font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="absent_days_number" id="edit_absent_days_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>أيام خصم جزاء (<span id="edit_deducted_days_amount_span" class="text-danger font-weight-bold">0.00</span> ج.م)</label>
                                <input type="number" step="0.01" name="deducted_days_number" id="edit_deducted_days_number" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم مالي مباشر (-)</label>
                                <input type="number" step="0.01" name="salary_deduction_amount" id="edit_salary_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصومات أخرى (-)</label>
                                <input type="number" step="0.01" name="others_salary_deduction_amount" id="edit_others_salary_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم تأمين طبي (-)</label>
                                <input type="number" step="0.01" name="medical_insurance_deduction_amount" id="edit_medical_insurance_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم سلفة شهرية (-)</label>
                                <input type="number" step="0.01" name="monthly_loan_deduction_amount" id="edit_monthly_loan_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>قسط سلفة مستديمة (-)</label>
                                <input type="number" step="0.01" name="permanent_loan_deduction_amount" id="edit_permanent_loan_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>خصم جزاءات وتحقيقات (-)</label>
                                <input type="number" step="0.01" name="penalty_deduction_amount" id="edit_penalty_deduction_amount" class="form-control edit-calc" value="0">
                            </div>
                        </div>

                        <!-- Summary Totals Block -->
                        <div class="row mt-4 p-3 bg-light rounded border">
                            <div class="col-md-4 text-center">
                                <h6 class="text-success font-weight-bold">إجمالي الإضافات الجديد</h6>
                                <input type="text" id="edit_total_amount_for_addition" class="form-control text-center font-weight-bold text-success" readonly value="0.00">
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="text-danger font-weight-bold">إجمالي الخصومات الجديد</h6>
                                <input type="text" id="edit_total_amount_for_deduction" class="form-control text-center font-weight-bold text-danger" readonly value="0.00">
                            </div>
                            <div class="col-md-4 text-center">
                                <h6 class="text-primary font-weight-bold">صافي القيمة الجديدة للتسوية</h6>
                                <input type="text" id="edit_final_total_amount" class="form-control text-center font-weight-bold text-primary" readonly value="0.00">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>ملاحظات أو سبب التسوية</label>
                            <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-info" id="submit_edit_settlement">حفظ وتعديل التسميع</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAILS SETTLEMENT MODAL -->
    <div class="modal fade" id="detailsSettlementModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i> تفاصيل التسوية المالية للموظف</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <!-- Employee Info Card -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body py-3">
                            <div class="row text-center text-md-left">
                                <div class="col-md-4 mb-2 mb-md-0 border-right">
                                    <span class="text-muted d-block small">اسم الموظف</span>
                                    <h6 class="font-weight-bold mb-0 text-primary" id="det_employee_name">---</h6>
                                </div>
                                <div class="col-md-4 mb-2 mb-md-0 border-right">
                                    <span class="text-muted d-block small">الراتب الأساسي</span>
                                    <h6 class="font-weight-bold mb-0 text-dark" id="det_employee_salary">0.00 ج.م</h6>
                                </div>
                                <div class="col-md-4">
                                    <span class="text-muted d-block small">أجر اليوم الواحد</span>
                                    <h6 class="font-weight-bold mb-0 text-dark" id="det_employee_per_day">0.00 ج.م</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Additions Breakdown -->
                        <div class="col-md-6">
                            <div class="card border-success mb-3">
                                <div class="card-header bg-success text-white font-weight-bold">
                                    <i class="fas fa-plus-circle mr-1"></i> تسويات الإضافة (+)
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0 text-center">
                                        <thead>
                                            <tr>
                                                <th>البند</th>
                                                <th>الكمية (أيام)</th>
                                                <th>القيمة المالية</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>أيام عمل غير مسجلة</td>
                                                <td id="det_working_days_number">0.00</td>
                                                <td id="det_working_days_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>أيام عمل إضافية</td>
                                                <td id="det_extra_working_days_number">0.00</td>
                                                <td id="det_extra_working_days_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>أيام غياب مستردة</td>
                                                <td id="det_absent_days_back_number">0.00</td>
                                                <td id="det_absent_days_back_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>أيام خصم مستردة</td>
                                                <td id="det_deducted_days_restored_number">0.00</td>
                                                <td id="det_deducted_days_restored_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>فروقات رواتب</td>
                                                <td>---</td>
                                                <td id="det_different_in_salary_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>مكافأة مباشرة</td>
                                                <td>---</td>
                                                <td id="det_bonus_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>بدل مالي مباشر</td>
                                                <td>---</td>
                                                <td id="det_allowance_amount" class="text-success font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr class="bg-success text-white font-weight-bold">
                                                <td>إجمالي الإضافات</td>
                                                <td>---</td>
                                                <td id="det_total_amount_for_addition">0.00 ج.م</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions Breakdown -->
                        <div class="col-md-6">
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-danger text-white font-weight-bold">
                                    <i class="fas fa-minus-circle mr-1"></i> تسويات الخصم (-)
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm table-striped mb-0 text-center">
                                        <thead>
                                            <tr>
                                                <th>البند</th>
                                                <th>الكمية (أيام)</th>
                                                <th>القيمة المالية</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>أيام غياب</td>
                                                <td id="det_absent_days_number">0.00</td>
                                                <td id="det_absent_days_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>أيام خصم جزاء</td>
                                                <td id="det_deducted_days_number">0.00</td>
                                                <td id="det_deducted_days_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>خصم مالي مباشر</td>
                                                <td>---</td>
                                                <td id="det_salary_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>خصومات أخرى</td>
                                                <td>---</td>
                                                <td id="det_others_salary_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>خصم تأمين طبي</td>
                                                <td>---</td>
                                                <td id="det_medical_insurance_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>خصم سلفة شهرية</td>
                                                <td>---</td>
                                                <td id="det_monthly_loan_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>قسط سلفة مستديمة</td>
                                                <td>---</td>
                                                <td id="det_permanent_loan_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr>
                                                <td>خصم جزاءات وعقوبات</td>
                                                <td>---</td>
                                                <td id="det_penalty_deduction_amount" class="text-danger font-weight-bold">0.00 ج.م</td>
                                            </tr>
                                            <tr class="bg-danger text-white font-weight-bold">
                                                <td>إجمالي الخصومات</td>
                                                <td>---</td>
                                                <td id="det_total_amount_for_deduction">0.00 ج.م</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Summary and Metadata -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm bg-light">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-3 mb-md-0 border-right">
                                            <span class="text-muted d-block small">صافي التسوية النهائية</span>
                                            <h4 class="font-weight-bold mb-0 text-primary" id="det_final_total_amount">0.00 ج.م</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted d-block small">ملاحظات</span>
                                            <p class="font-italic mb-0 text-secondary" id="det_notes">لا توجد ملاحظات</p>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <div class="row text-muted small">
                                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                                            <span>تمت الإضافة بواسطة: </span><span class="font-weight-bold" id="det_added_by">---</span>
                                            <span> بتاريخ: </span><span class="font-weight-bold" id="det_created_at">---</span>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <span>آخر تحديث بواسطة: </span><span class="font-weight-bold" id="det_updated_by">---</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-1"></i> إغلاق النافذة
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Handle Employee Select change in Add modal
            $(document).on('change', '#add_employee_id', function() {
                var selected = $(this).find('option:selected');
                var paymentPerDay = parseFloat(selected.data('payment-per-day')) || 0.00;
                var salary = parseFloat(selected.data('salary')) || 0.00;
                $('#add_employee_per_day_salary').val(paymentPerDay.toFixed(2));
                $('#add_employee_salary').val(salary.toFixed(2));
                calculateAddTotals();
            });

            // Add calc dynamic triggers
            $(document).on('input', '.add-calc', function() {
                calculateAddTotals();
            });

            // Edit calc dynamic triggers
            $(document).on('input', '.edit-calc', function() {
                calculateEditTotals();
            });

            // Calculate Add Totals Function
            function calculateAddTotals() {
                var perDay = parseFloat($('#add_employee_per_day_salary').val()) || 0;

                // Additions calculations
                var addDays = (parseFloat($('#add_working_days_number').val()) || 0) * perDay;
                var addExtra = (parseFloat($('#add_extra_working_days_number').val()) || 0) * perDay;
                var addBack = (parseFloat($('#add_absent_days_back_number').val()) || 0) * perDay;
                var addRestore = (parseFloat($('#add_deducted_days_restored_number').val()) || 0) * perDay;

                $('#add_working_days_amount_span').text(addDays.toFixed(2));
                $('#add_extra_working_days_amount_span').text(addExtra.toFixed(2));
                $('#add_absent_days_back_amount_span').text(addBack.toFixed(2));
                $('#add_deducted_days_restored_amount_span').text(addRestore.toFixed(2));

                var addDiff = parseFloat($('#add_different_in_salary_amount').val()) || 0;
                var addBonus = parseFloat($('#add_bonus_amount').val()) || 0;
                var addAllowance = parseFloat($('#add_allowance_amount').val()) || 0;

                var totalAddition = addDays + addExtra + addBack + addRestore + addDiff + addBonus + addAllowance;
                $('#add_total_amount_for_addition').val(totalAddition.toFixed(2));

                // Deductions calculations
                var dedAbsent = (parseFloat($('#add_absent_days_number').val()) || 0) * perDay;
                var dedCut = (parseFloat($('#add_deducted_days_number').val()) || 0) * perDay;

                $('#add_absent_days_amount_span').text(dedAbsent.toFixed(2));
                $('#add_deducted_days_amount_span').text(dedCut.toFixed(2));

                var dedDirect = parseFloat($('#add_salary_deduction_amount').val()) || 0;
                var dedOther = parseFloat($('#add_others_salary_deduction_amount').val()) || 0;
                var dedMed = parseFloat($('#add_medical_insurance_deduction_amount').val()) || 0;
                var dedLoan = parseFloat($('#add_monthly_loan_deduction_amount').val()) || 0;
                var dedPLoan = parseFloat($('#add_permanent_loan_deduction_amount').val()) || 0;
                var dedPen = parseFloat($('#add_penalty_deduction_amount').val()) || 0;

                var totalDeduction = dedAbsent + dedCut + dedDirect + dedOther + dedMed + dedLoan + dedPLoan + dedPen;
                $('#add_total_amount_for_deduction').val(totalDeduction.toFixed(2));

                // Final net total
                var finalTotal = totalAddition - totalDeduction;
                $('#add_final_total_amount').val(finalTotal.toFixed(2));
            }

            // Calculate Edit Totals Function
            function calculateEditTotals() {
                var perDay = parseFloat($('#edit_employee_per_day_salary').val()) || 0;

                // Additions calculations
                var addDays = (parseFloat($('#edit_working_days_number').val()) || 0) * perDay;
                var addExtra = (parseFloat($('#edit_extra_working_days_number').val()) || 0) * perDay;
                var addBack = (parseFloat($('#edit_absent_days_back_number').val()) || 0) * perDay;
                var addRestore = (parseFloat($('#edit_deducted_days_restored_number').val()) || 0) * perDay;

                $('#edit_working_days_amount_span').text(addDays.toFixed(2));
                $('#edit_extra_working_days_amount_span').text(addExtra.toFixed(2));
                $('#edit_absent_days_back_amount_span').text(addBack.toFixed(2));
                $('#edit_deducted_days_restored_amount_span').text(addRestore.toFixed(2));

                var addDiff = parseFloat($('#edit_different_in_salary_amount').val()) || 0;
                var addBonus = parseFloat($('#edit_bonus_amount').val()) || 0;
                var addAllowance = parseFloat($('#edit_allowance_amount').val()) || 0;

                var totalAddition = addDays + addExtra + addBack + addRestore + addDiff + addBonus + addAllowance;
                $('#edit_total_amount_for_addition').val(totalAddition.toFixed(2));

                // Deductions calculations
                var dedAbsent = (parseFloat($('#edit_absent_days_number').val()) || 0) * perDay;
                var dedCut = (parseFloat($('#edit_deducted_days_number').val()) || 0) * perDay;

                $('#edit_absent_days_amount_span').text(dedAbsent.toFixed(2));
                $('#edit_deducted_days_amount_span').text(dedCut.toFixed(2));

                var dedDirect = parseFloat($('#edit_salary_deduction_amount').val()) || 0;
                var dedOther = parseFloat($('#edit_others_salary_deduction_amount').val()) || 0;
                var dedMed = parseFloat($('#edit_medical_insurance_deduction_amount').val()) || 0;
                var dedLoan = parseFloat($('#edit_monthly_loan_deduction_amount').val()) || 0;
                var dedPLoan = parseFloat($('#edit_permanent_loan_deduction_amount').val()) || 0;
                var dedPen = parseFloat($('#edit_penalty_deduction_amount').val()) || 0;

                var totalDeduction = dedAbsent + dedCut + dedDirect + dedOther + dedMed + dedLoan + dedPLoan + dedPen;
                $('#edit_total_amount_for_deduction').val(totalDeduction.toFixed(2));

                // Final net total
                var finalTotal = totalAddition - totalDeduction;
                $('#edit_final_total_amount').val(finalTotal.toFixed(2));
            }

            // AJax search handling on filters change
            $(document).on('change', '#employee_id_search, #is_archived_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var employee_id_search = $('#employee_id_search').val();
                var is_archived_search = $('#is_archived_search').val();

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-settlements.ajax-search') }}",
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        is_archived_search: is_archived_search
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تحميل نتائج البحث.');
                    }
                });
            }

            // AJAX Pagination
            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search = $('#employee_id_search').val();
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
                        is_archived_search: is_archived_search
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تحميل بيانات الصفحة التالية.');
                    }
                });
            });

            // Store Settlement AJAX Submit
            $(document).on('click', '#submit_add_settlement', function() {
                var employeeId = $('#add_employee_id').val();
                if (employeeId == '') {
                    alert('من فضلك اختر الموظف أولاً');
                    return false;
                }

                var formData = $('#addSettlementForm').serialize();

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-settlements.store') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: formData,
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#addSettlementModal').modal('hide');
                            // Reset form
                            $('#addSettlementForm')[0].reset();
                            $('#add_employee_id').val('').trigger('change');
                            // Refresh list
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء الاتصال بالخادم.');
                    }
                });
            });

            // Load Settlement edit modal via AJAX
            $(document).on('click', '.edit-settlement', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-settlements.edit') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            var settlement = response.settlement;
                            var employee = settlement.employee;
                            var modal = $('#editSettlementModal');

                            modal.find('#edit_settlement_id').val(settlement.id);
                            modal.find('#edit_employee_id').html(`
                                <option value="${employee.id}" selected>${employee.name}</option>
                            `);
                            modal.find('#edit_employee_salary').val(parseFloat(employee.salary).toFixed(2));
                            modal.find('#edit_employee_per_day_salary').val(parseFloat(settlement.employee_per_day_salary).toFixed(2));
                            
                            modal.find('#edit_working_days_number').val(settlement.working_days_number);
                            modal.find('#edit_extra_working_days_number').val(settlement.extra_working_days_number);
                            modal.find('#edit_absent_days_back_number').val(settlement.absent_days_back_number);
                            modal.find('#edit_deducted_days_restored_number').val(settlement.deducted_days_restored_number);
                            modal.find('#edit_different_in_salary_amount').val(settlement.different_in_salary_amount);
                            modal.find('#edit_bonus_amount').val(settlement.bonus_amount);
                            modal.find('#edit_allowance_amount').val(settlement.allowance_amount);

                            modal.find('#edit_absent_days_number').val(settlement.absent_days_number);
                            modal.find('#edit_deducted_days_number').val(settlement.deducted_days_number);
                            modal.find('#edit_salary_deduction_amount').val(settlement.salary_deduction_amount);
                            modal.find('#edit_others_salary_deduction_amount').val(settlement.others_salary_deduction_amount);
                            modal.find('#edit_medical_insurance_deduction_amount').val(settlement.medical_insurance_deduction_amount);
                            modal.find('#edit_monthly_loan_deduction_amount').val(settlement.monthly_loan_deduction_amount);
                            modal.find('#edit_permanent_loan_deduction_amount').val(settlement.permanent_loan_deduction_amount);
                            modal.find('#edit_penalty_deduction_amount').val(settlement.penalty_deduction_amount);

                            modal.find('#edit_notes').val(settlement.notes);

                            calculateEditTotals();
                            modal.modal('show');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تحميل بيانات التسوية.');
                    }
                });
            });

            // Update Settlement AJAX Submit
            $(document).on('click', '#submit_edit_settlement', function() {
                var formData = $('#editSettlementForm').serialize();

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-settlements.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: formData,
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#editSettlementModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تعديل التسوية.');
                    }
                });
            });

            // Load Settlement details modal via AJAX
            $(document).on('click', '.show-settlement-details-btn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-settlements.edit') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            var settlement = response.settlement;
                            var employee = settlement.employee;
                            var modal = $('#detailsSettlementModal');

                            modal.find('#det_employee_name').text(employee.name);
                            modal.find('#det_employee_salary').text(parseFloat(employee.salary).toFixed(2) + ' ج.م');
                            modal.find('#det_employee_per_day').text(parseFloat(settlement.employee_per_day_salary).toFixed(2) + ' ج.م');

                            modal.find('#det_working_days_number').text(parseFloat(settlement.working_days_number).toFixed(2));
                            modal.find('#det_working_days_amount').text(parseFloat(settlement.working_days_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_extra_working_days_number').text(parseFloat(settlement.extra_working_days_number).toFixed(2));
                            modal.find('#det_extra_working_days_amount').text(parseFloat(settlement.extra_working_days_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_absent_days_back_number').text(parseFloat(settlement.absent_days_back_number).toFixed(2));
                            modal.find('#det_absent_days_back_amount').text(parseFloat(settlement.absent_days_back_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_deducted_days_restored_number').text(parseFloat(settlement.deducted_days_restored_number).toFixed(2));
                            modal.find('#det_deducted_days_restored_amount').text(parseFloat(settlement.deducted_days_restored_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_different_in_salary_amount').text(parseFloat(settlement.different_in_salary_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_bonus_amount').text(parseFloat(settlement.bonus_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_allowance_amount').text(parseFloat(settlement.allowance_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_total_amount_for_addition').text(parseFloat(settlement.total_amount_for_addition).toFixed(2) + ' ج.م');

                            modal.find('#det_absent_days_number').text(parseFloat(settlement.absent_days_number).toFixed(2));
                            modal.find('#det_absent_days_amount').text(parseFloat(settlement.absent_days_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_deducted_days_number').text(parseFloat(settlement.deducted_days_number).toFixed(2));
                            modal.find('#det_deducted_days_amount').text(parseFloat(settlement.deducted_days_amount).toFixed(2) + ' ج.م');
                            
                            modal.find('#det_salary_deduction_amount').text(parseFloat(settlement.salary_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_others_salary_deduction_amount').text(parseFloat(settlement.others_salary_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_medical_insurance_deduction_amount').text(parseFloat(settlement.medical_insurance_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_monthly_loan_deduction_amount').text(parseFloat(settlement.monthly_loan_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_permanent_loan_deduction_amount').text(parseFloat(settlement.permanent_loan_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_penalty_deduction_amount').text(parseFloat(settlement.penalty_deduction_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_total_amount_for_deduction').text(parseFloat(settlement.total_amount_for_deduction).toFixed(2) + ' ج.م');

                            modal.find('#det_final_total_amount').text(parseFloat(settlement.final_total_amount).toFixed(2) + ' ج.م');
                            modal.find('#det_notes').text(settlement.notes || 'لا توجد ملاحظات');

                            modal.find('#det_added_by').text(settlement.added_by ? (settlement.added_by.name || '---') : '---');
                            if (settlement.added_by && settlement.added_by.name) {
                                modal.find('#det_added_by').text(settlement.added_by.name);
                            }
                            
                            modal.find('#det_created_at').text(settlement.created_at ? settlement.created_at.substring(0, 10) : '---');
                            
                            if (settlement.updated_by && settlement.updated_by.name) {
                                modal.find('#det_updated_by').text(settlement.updated_by.name);
                            } else {
                                modal.find('#det_updated_by').text('---');
                            }

                            modal.modal('show');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تحميل تفاصيل التسوية.');
                    }
                });
            });

            // Delete Settlement AJAX Call
            $(document).on('click', '.delete-settlement-btn', function() {
                var id = $(this).data('id');
                var confirmDelete = confirm('هل أنت متأكد من حذف هذه التسوية المالية وعكس أثرها بالكامل من راتب الموظف المؤرشف؟');
                
                if (confirmDelete) {
                    $.ajax({
                        url: "{{ route('admin.main-salary-employee-settlements.destroy') }}",
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if (response.status == 'true') {
                                alert(response.message);
                                window.location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('عفواً، حدث خطأ أثناء حذف التسوية.');
                        }
                    });
                }
            });

        });
    </script>
@endsection

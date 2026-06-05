<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-coins text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي السلف المستديمة</span>
                    <span class="info-box-number">{{ $total_count }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">السلف المصروفة</span>
                    <span class="info-box-number">{{ $disbursed_count }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-hourglass-half text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">سلف في انتظار الصرف</span>
                    <span class="info-box-number">{{ $non_disbursed_count }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-money-bill-wave text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي مبالغ السلف</span>
                    <span class="info-box-number">
                        {{ number_format($total_amount, 2) }}
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
                <i class="fas fa-table mr-2"></i>
                سجل السلف المستديمة (القروض) للموظفين
            </h3>

            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                    data-target="#addMainSalaryRecordLoanModal">
                    <i class="fas fa-plus-circle"></i>
                    إضافة سلفة مستديمة
                </button>
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

            <form action="{{ route('admin.main-salary-employee-ploans.print-search') }}" method="POST" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <p class="btn btn-primary btn-sm shadow-sm mb-3">
                            <i class="fas fa-search"></i> تصفية البحث
                        </p>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>أسم الموظف</label>
                            <select name="employee_id_search" id="employee_id_search" class="form-control select2">
                                <option value="">اختر أسم الموظف</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع حالة الأرشيف</label>
                            <select name="is_archived" id="is_archived_search" class="form-control select2">
                                <option value="">اختر نوع الحالة</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">غير مؤرشف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع حالة الصرف</label>
                            <select name="is_disbursed" id="is_disbursed_search" class="form-control select2">
                                <option value="">اختر نوع الحالة</option>
                                <option value="1">تم الصرف</option>
                                <option value="0">قيد الانتظار</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 align-content-end">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" id="print_button">
                                <i class="fas fa-print"></i>
                                طباعة البحث
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div id="ajax_responce_search" class="mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center align-middle">

                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>الموظف</th>
                                <th>الراتب الأساسي</th>
                                <th>إجمالى مبلغ السلفة</th>
                                <th>عدد الأقساط</th>
                                <th>القسط الشهري</th>
                                <th>تاريخ البدء</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>حالة الصرف</th>
                                <th>حالة الأرشفة</th>
                                <th>تفاصيل الإدخال</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($mainSalaryEmployeePLoans as $loan)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($mainSalaryEmployeePLoans->currentPage() - 1) * $mainSalaryEmployeePLoans->perPage() }}
                                    </td>

                                    <td>
                                        <span class="font-weight-bold text-dark d-block">
                                            {{ $loan->employee->name ?? '---' }}
                                        </span>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1 mt-1">
                                            كود: {{ $loan->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-secondary font-weight-bold">
                                            {{ number_format($loan->employee_basic_salary, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-success font-weight-bold">
                                            {{ number_format($loan->amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-dark px-3 py-1 font-weight-normal">
                                            {{ $loan->number_of_installment_months }} شهر
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-primary font-weight-bold">
                                            {{ number_format($loan->installment_amount_monthly, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-dark font-weight-bold">
                                            {{ $loan->next_installment_date }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-info font-weight-bold">
                                            {{ number_format($loan->paid_amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            {{ number_format($loan->remaining_amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        @if ($loan->is_disbursed == 1)
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-check-circle mr-1"></i> تم الصرف
                                            </span>
                                            @if ($loan->disbursedBy)
                                                <small class="d-block text-muted mt-1 font-italic"
                                                    title="{{ $loan->disbursed_at }}">
                                                    بواسطة: {{ $loan->disbursedBy->name }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning px-3 py-2 text-white">
                                                <i class="fas fa-hourglass-half mr-1"></i> قيد الانتظار
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($loan->is_archived == 1)
                                            <span class="badge badge-danger px-3 py-2">
                                                <i class="fas fa-archive mr-1"></i> مؤرشف
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-light border border-success text-success px-3 py-2">
                                                <i class="fas fa-folder-open mr-1"></i> نشط
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-right" style="min-width: 180px;">
                                        <small class="d-block text-muted">
                                            <strong>أضيف بواسطة:</strong> {{ optional($loan->addedBy)->name ?? '---' }}
                                        </small>
                                        <small class="d-block text-muted">
                                            <strong>بتاريخ:</strong>
                                            {{ $loan->created_at ? $loan->created_at->format('Y-m-d h:i A') : '---' }}
                                        </small>
                                        @if ($loan->updatedBy)
                                            <hr class="my-1">
                                            <small class="d-block text-muted">
                                                <strong>تعديل بواسطة:</strong> {{ $loan->updatedBy->name }}
                                            </small>
                                            <small class="d-block text-muted">
                                                <strong>بتاريخ:</strong>
                                                {{ $loan->updated_at ? $loan->updated_at->format('Y-m-d h:i A') : '---' }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <button class="btn btn-sm btn-info m-1 show_employee_loan_details_btn"
                                                title="عرض الأقساط والتفاصيل" data-id="{{ $loan->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning m-1 edit_employee_loan_btn"
                                                title="تعديل" data-id="{{ $loan->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger m-1 delete_employee_loan_btn"
                                                title="حذف" data-id="{{ $loan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="alert alert-warning mb-0 py-3">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            لا توجد بيانات سلف مستديمة (قروض) حالياً
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $mainSalaryEmployeePLoans->links() }}
                </div>
            </div>

        </div>
    </div>
</div>


<!-- Add Modal -->
<div class="modal fade" id="addMainSalaryRecordLoanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    إضافة سلفة شهرية جديدة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body" id="months_modal_body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بيانات الموظفين</label>
                            <select name="employee_id" id="employee_id"
                                class="form-control select2 {{ $errors->has('employee_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر الموظف</option>
                                @foreach ($employees_has_opened_monthly_record as $employee)
                                    <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                                        data-payment-per-day="{{ $employee->payment_per_day }}"
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>الراتب</label>
                            <input readonly type="number" name="salary" value="0.0" id="salary"
                                class="form-control" placeholder="أدخل الراتب">
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>أجر اليوم</label>
                            <input readonly type="number" name="payment_per_day" value="0.0"
                                id="payment_per_day" class="form-control" placeholder="أدخل أجر اليوم">
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>إجمالى مبلغ السلفة</label>
                            <input type="number" name="amount" id="amount" class="form-control"
                                placeholder="أدخل إجمالى مبلغ السلفة" min="0">
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>عدد الأقساط</label>
                            <input type="number" name="number_of_installment_months"
                                id="number_of_installment_months" class="form-control" placeholder="أدخل عدد الأقساط"
                                min="1">
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>قسط الشهر</label>
                            <input type="number" name="installment_amount_monthly" readonly
                                id="installment_amount_monthly" class="form-control" placeholder="أدخل قسط الشهر"
                                min="0">
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>تاريخ بدء خصم القسط</label>
                            <input type="date" name="year_and_month_started" value="{{ date('Y-m-d') }}"
                                id="year_and_month_started" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_add_loan">
                            <i class="fas fa-save"></i>
                            حفظ البيانات
                        </button>
                        <button type="button" class="btn btn-danger shadow px-4" data-dismiss="modal">الغاء</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editMainSalaryRecordLoanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    تعديل سلفة شهرية
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body" id="edit_months_modal_body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بيانات الموظف</label>
                            <select name="employee_id" id="edit_employee_id" class="form-control" disabled>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>الراتب</label>
                            <input readonly type="number" name="salary" value="0.0" id="edit_salary"
                                class="form-control" placeholder="أدخل الراتب">
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>أجر اليوم</label>
                            <input readonly type="number" name="payment_per_day" value="0.0"
                                id="edit_payment_per_day" class="form-control" placeholder="أدخل أجر اليوم">
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>إجمالى مبلغ السلفة</label>
                            <input type="number" name="amount" value="0" id="edit_amount"
                                class="form-control" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>عدد الأقساط</label>
                            <input type="number" name="number_of_installment_months" value="0"
                                id="edit_number_of_installment_months" class="form-control" step="1"
                                min="1">
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>قسط الشهر</label>
                            <input type="number" name="installment_amount_monthly" readonly
                                id="edit_installment_amount_monthly" class="form-control"
                                placeholder="أدخل قسط الشهر" min="0">
                        </div>
                    </div>
                    <div class="col-md-3 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>تاريخ بدء خصم القسط</label>
                            <input type="date" name="year_and_month_started" id="edit_year_and_month_started"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <input type="hidden" id="edit_main_salary_employee_p_loan_id" name="id">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_edit_loan">
                            <i class="fas fa-save"></i>
                            حفظ البيانات
                        </button>
                        <button type="button" class="btn btn-danger shadow px-4" data-dismiss="modal">الغاء</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="mainSalaryEmployeePLoanDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    تفاصيل السلفة الشهرية
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body" id="mainSalaryEmployeePLoanDetailsModalBody">

            </div>

        </div>
    </div>
</div>


@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        function initSelect2() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }

        function installment_amount_monthly_calc() {
            let amount = parseInt($('#amount').val());
            let number_of_installment_months = parseInt($('#number_of_installment_months').val());
            if (amount == '') {
                amount = 0
            } else {
                amount = Math.ceil(amount)
                $('#amount').val(amount);
            }
            if (number_of_installment_months == '') {
                number_of_installment_months = 0;
            } else {
                number_of_installment_months = Math.ceil(number_of_installment_months);
                $('#number_of_installment_months').val(number_of_installment_months);
            }
            if (number_of_installment_months > 0 && amount > 0) {
                let installment_amount_monthly = parseFloat((amount / number_of_installment_months).toFixed(2));
                $('#installment_amount_monthly').val(installment_amount_monthly);
            } else {
                $('#installment_amount_monthly').val(0);

            }
        }

        function installment_amount_monthly_calc_edit() {
            let amount = parseInt($('#edit_amount').val());
            let number_of_installment_months = parseInt($('#edit_number_of_installment_months').val());
            if (amount == '') {
                amount = 0
            } else {
                amount = Math.ceil(amount)
                $('#edit_amount').val(amount);
            }
            if (number_of_installment_months == '') {
                number_of_installment_months = 0;
            } else {
                number_of_installment_months = Math.ceil(number_of_installment_months);
                $('#edit_number_of_installment_months').val(number_of_installment_months);
            }
            if (number_of_installment_months > 0 && amount > 0) {
                let installment_amount_monthly = parseFloat((amount / number_of_installment_months).toFixed(2));
                $('#edit_installment_amount_monthly').val(installment_amount_monthly);
            } else {
                $('#edit_installment_amount_monthly').val(0);
            }
        }
        $(document).ready(function() {
            initSelect2();
            $(document).on('change', '#employee_id', function() {
                var employee_id = $(this).val();
                var salary = $(this).find(':selected').data('salary');
                var payment_per_day = $(this).find(':selected').data('payment-per-day');

                if (employee_id) {
                    $('#salary').val(salary);
                    $('#payment_per_day').val(payment_per_day);
                    $('.related_to_employee').show();
                } else {
                    $('#salary').val(0);
                    $('#payment_per_day').val(0);
                    $('.related_to_employee').hide();
                }

            });
            $(document).on('input', '#amount', function() {
                installment_amount_monthly_calc();
            });
            $(document).on('input', '#number_of_installment_months', function() {
                installment_amount_monthly_calc();
            });
            $(document).on('change', '#year_and_month_started', function(e) {
                const date = new Date($(this).val());
                date.setDate(date.getDate() + 1);
                const today = new Date();
                if (date < today) {
                    alert('من فضلك اختر تاريخ صحيح');
                    $(this).val(today.toISOString().split('T')[0]);
                }
            });

            $(document).on('click', '#submit_add_loan', function(e) {
                var employee_id = $('#employee_id').val();
                if (employee_id == '') {
                    $('#employee_id').addClass('is-invalid');
                    alert('اختر الموظف');
                    return false;
                } else {
                    $('#employee_id').removeClass('is-invalid');
                }

                var amount = $('#amount').val();
                if (amount == '' || amount <= 0) {
                    $('#amount').addClass('is-invalid');
                    alert('أدخل إجمالى مبلغ السلفة بشكل صحيح');
                    return false;
                } else {
                    $('#amount').removeClass('is-invalid');
                }

                var number_of_installment_months = $('#number_of_installment_months').val();
                if (number_of_installment_months == '' || number_of_installment_months <= 0) {
                    $('#number_of_installment_months').addClass('is-invalid');
                    alert('أدخل عدد الأقساط بشكل صحيح');
                    return false;
                } else {
                    $('#number_of_installment_months').removeClass('is-invalid');
                }
                var year_and_month_started = $('#year_and_month_started').val();
                if (year_and_month_started == '' || year_and_month_started == null) {
                    $('#year_and_month_started').addClass('is-invalid');
                    alert('أدخل تاريخ بدء خصم القسط بشكل صحيح');
                    return false;
                } else {
                    $('#year_and_month_started').removeClass('is-invalid');
                }

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-ploans.ajax-check') }}",
                    dataType: "json",
                    cache: false,
                    method: "POST",
                    data: {
                        employee_id: employee_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        var flag = true;
                        if (response.status == 'true') {
                            var res = confirm(
                                'يوجد ' + response.count +
                                ' سلفة مسجلة لنفس الموظف. هل تريد إضافة سلفة أخرى؟'
                            );
                            if (res == false) {
                                flag = false;
                            }
                        }

                        if (flag) {
                            var formData = {
                                employee_id: employee_id,
                                employee_basic_salary: $('#salary').val(),
                                amount: amount,
                                number_of_installment_months: number_of_installment_months,
                                next_installment_date: year_and_month_started,
                                installment_amount_monthly: $('#installment_amount_monthly')
                                    .val(),
                                notes: $('#notes').val(),
                                _token: "{{ csrf_token() }}"
                            };
                            $.ajax({
                                url: "{{ route('admin.main-salary-employee-ploans.store') }}",
                                dataType: "json",
                                cache: false,
                                method: "POST",
                                data: formData,
                                success: function(response) {
                                    if (response.status == 'true') {
                                        alert(response.message);
                                        $('#addMainSalaryRecordLoanModal').modal(
                                            'hide');
                                        window.location.reload();
                                    } else {
                                        alert(response.message ||
                                            'عفوا، حدث خطأ أثناء الحفظ.');
                                    }
                                },
                                error: function(xhr, status, error) {
                                    alert(
                                        'عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.'
                                    );
                                }
                            })
                        }
                    }
                })
            });


            $(document).on('change', '#employee_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#is_archived_search', function() {
                ajax_search();
            });

            $(document).on('change', '#is_disbursed_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var employee_id_search = $('#employee_id_search').val();
                var is_archived_search = $('#is_archived_search').val();
                var is_disbursed_search = $('#is_disbursed_search').val();
                $.ajax({
                    url: '{{ route('admin.main-salary-employee-ploans.ajax-search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id_search: employee_id_search,
                        is_archived_search: is_archived_search,
                        is_disbursed_search: is_disbursed_search,
                    },
                    success: function(mainSalaryEmployeePLoans) {
                        $('#ajax_responce_search').html(mainSalaryEmployeePLoans);
                    },
                    error: function(xhr) {}
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search = $('#employee_id_search').val();
                var is_archived_search = $('#is_archived_search').val();
                var is_disbursed_search = $('#is_disbursed_search').val();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id_search: employee_id_search,
                        is_archived_search: is_archived_search,
                        is_disbursed_search: is_disbursed_search,

                    },
                    success: function(mainSalaryEmployeePLoans) {
                        $('#ajax_responce_search').html(mainSalaryEmployeePLoans);
                    },
                    error: function(xhr) {}
                });
            });

            $(document).on('click', '.show_employee_loan_details_btn', function(e) {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.main-salary-employee-ploans.show') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success: function(mainSalaryEmployeePLoans) {
                        $('#mainSalaryEmployeePLoanDetailsModalBody').html(
                            mainSalaryEmployeePLoans);
                        $('#mainSalaryEmployeePLoanDetailsModal').modal('show');
                    },
                    error: function(xhr) {}
                });
            });

            $(document).on('click', '.delete_employee_loan_btn', function() {
                var id = $(this).data('id');
                var res = confirm('هل انت متاكد من حذف هذه السلفة؟');
                if (res == true) {
                    $.ajax({
                        url: "{{ route('admin.main-salary-employee-ploans.destroy') }}",
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                        },
                        success: function(response) {
                            if (response.status == 'true') {
                                alert(response.message);
                                ajax_search();
                            } else {
                                alert(response.message || 'عفوا، حدث خطأ أثناء الحذف.');
                            }
                        },
                        error: function(xhr) {
                            alert('عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.');
                        }
                    })
                }
            });

            $(document).on('click', '.edit_employee_loan_btn', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-ploans.edit') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    cache: false,
                    success: function(response) {
                        if (response.status == 'true') {
                            var mainSalaryEmployeePLoan = response.mainSalaryEmployeePLoan;
                            var employee = mainSalaryEmployeePLoan.employee;
                            var modal = $('#editMainSalaryRecordLoanModal');
                            modal.find("#edit_main_salary_employee_p_loan_id").val(
                                mainSalaryEmployeePLoan.id);

                            modal.find("#edit_employee_id").html(`
                                <option value="${employee.id}" selected>
                                    ${employee.name}
                                </option>
                            `);

                            modal.find("#edit_salary").val(mainSalaryEmployeePLoan
                                .employee_basic_salary);

                            modal.find("#edit_payment_per_day").val(employee.payment_per_day);

                            modal.find("#edit_amount").val(mainSalaryEmployeePLoan.amount);

                            modal.find("#edit_number_of_installment_months").val(
                                mainSalaryEmployeePLoan.number_of_installment_months);

                            modal.find("#edit_installment_amount_monthly").val(
                                mainSalaryEmployeePLoan.installment_amount_monthly);

                            modal.find("#edit_year_and_month_started").val(
                                mainSalaryEmployeePLoan.next_installment_date);
                            modal.find("#edit_notes").val(mainSalaryEmployeePLoan.notes);

                            modal.find(".edit_related_to_employee").show();
                            modal.modal('show');
                        } else {
                            alert(response.message || 'عفوا، حدث خطأ أثناء جلب البيانات.');
                        }
                    },
                    error: function(xhr) {
                        alert('عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.');
                    }
                });
            });
            
              $(document).on('input', '#edit_amount', function() {
                installment_amount_monthly_calc_edit();
            });
            $(document).on('input', '#edit_number_of_installment_months', function() {
                installment_amount_monthly_calc_edit();
            });
            $(document).on('change', '#edit_year_and_month_started', function(e) {
                const date = new Date($(this).val());
                date.setDate(date.getDate() + 1);
                const today = new Date();
                if (date < today) {
                    alert('من فضلك اختر تاريخ صحيح');
                    $(this).val(today.toISOString().split('T')[0]);
                }
            });
            
            $(document).on('click', '#submit_edit_loan', function(e) {
                var id = $('#edit_main_salary_employee_p_loan_id').val();
                var employee_id = $('#edit_employee_id').val();
                var amount = $('#edit_amount').val();
                if (amount == '' || amount <= 0) {
                    $('#edit_amount').addClass('is-invalid');
                    alert('أدخل مبلغ السلفة بشكل صحيح');
                    return false;
                } else {
                    $('#edit_amount').removeClass('is-invalid');
                }
                  var number_of_installment_months = $('#edit_number_of_installment_months').val();
                if (number_of_installment_months == '' || number_of_installment_months <= 0) {
                    $('#edit_number_of_installment_months').addClass('is-invalid');
                    alert('أدخل عدد الأقساط بشكل صحيح');
                    return false;
                } else {
                    $('#edit_number_of_installment_months').removeClass('is-invalid');
                }
                var year_and_month_started = $('#edit_year_and_month_started').val();
                if (year_and_month_started == '' || year_and_month_started == null) {
                    $('#edit_year_and_month_started').addClass('is-invalid');
                    alert('أدخل تاريخ بدء خصم القسط بشكل صحيح');
                    return false;
                } else {
                    $('#edit_year_and_month_started').removeClass('is-invalid');
                }
                var installment_amount_monthly = $('#edit_installment_amount_monthly').val();
                var notes = $('#edit_notes').val();
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-ploans.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        id: id,
                        employee_id: employee_id,
                        amount: amount,
                        number_of_installment_months: number_of_installment_months,
                        year_and_month_started: year_and_month_started,
                        installment_amount_monthly: installment_amount_monthly,
                        notes: notes,
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#editMainSalaryRecordLoanModal').modal('hide');
                            ajax_search();
                        } else {
                            alert(response.message || 'عفوا، حدث خطأ أثناء الحفظ.');
                        }
                    },
                    error: function(xhr) {
                        alert('عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.');
                    }
                });
            });

        })
    </script>
@endsection

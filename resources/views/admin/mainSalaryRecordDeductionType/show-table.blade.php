<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-calendar-alt text-info mr-2"></i>
                        خصومات شهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.main-salary-employee-deduction-types.index') }}"
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
                    <i class="fas fa-hand-holding-usd text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي عدد الخصومات</span>
                    <span class="info-box-number">{{ $mainSalaryEmployeeDeductionTypes2->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-archive text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الخصومات المؤرشفة</span>
                    <span class="info-box-number">
                        {{ $mainSalaryEmployeeDeductionTypes2->where('is_archived', 1)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-archive text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الخصومات الغير مؤرشفة</span>
                    <span class="info-box-number">
                        {{ $mainSalaryEmployeeDeductionTypes2->where('is_archived', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-calculator text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي مبالغ الخصومات</span>
                    <span class="info-box-number text-info font-weight-bold">
                        {{ number_format($mainSalaryEmployeeDeductionTypes2->sum('amount'), 2) }}
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
                سجل خصومات الموظفين المفصل للشهر
            </h3>
            <div class="card-tools">

                @if ($financeMonthlyCalendar->status == 1)
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                        data-target="#addMainSalaryRecordDeductionTypeModal">

                        <i class="fas fa-list-plus"></i>
                        إضافة خصم جديد
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
            <form action="{{ route('admin.main-salary-employee-deduction-types.print-search') }}" method="POST"
                target="_blank">
                @csrf
                <input type="hidden" name="finance_monthly_calendar_id_search"
                    value="{{ $financeMonthlyCalendar->id }}">
                <div class="row">
                    <div class="col-md-12">
                        <p class="btn btn-primary btn-sm shadow-sm">
                            <i class="fas fa-search"></i>
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
                            <label>نوع الخصم</label>
                            <select name="deduction_type_search" id="deduction_type_search" class="form-control select2">
                                <option value="">اختر نوع الخصم</option>
                                @foreach ($deductionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>نوع الحالة</label>
                            <select name="is_archived" id="is_archived_search" class="form-control select2">
                                <option value="">اختر نوع الحالة</option>
                                <option value="1">مؤرشف</option>
                                <option value="0">غير مؤرشف</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 align-content-end">
                        <div class="form-group">
                            <button type="" class="btn btn-success" id="print_button">
                                <i class="fas fa-print"></i>
                                طباعة البحث
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
                                <th>نوع الخصم</th>
                                <th>المبلغ</th>
                                <th>الإضافة</th>
                                <th>تاريخ الإضافة</th>
                                <th>تاريخ التعديل</th>
                                <th>أضيف بواسطة</th>
                                <th>ملاحظات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mainSalaryEmployeeDeductionTypes as $deduction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                            {{ $deduction->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>
                                    <td class=" font-weight-bold">
                                        {{ $deduction->employee->name ?? '---' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-info px-2 py-1">
                                            <i class="fas fa-tag mr-1"></i>
                                            {{ optional($deduction->deductionType)->name ?? '---' }}
                                        </span>
                                    </td>
                                    <td class="text-danger font-weight-bold">
                                        {{ number_format($deduction->amount, 2) }} ج.م
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
                                        <span class="small text-muted d-block"
                                            title="أضيف بواسطة: {{ optional($deduction->addedBy)->name ?? '---' }} في {{ $deduction->created_at }}">
                                            {{ $deduction->created_at ? $deduction->created_at->format('Y-m-d') : '---' }}
                                        </span>
                                        <span class="small text-muted d-block font-italic"
                                            title="أضيف بواسطة: {{ optional($deduction->addedBy)->name ?? '---' }} في {{ $deduction->created_at }}">
                                            {{ $deduction->created_at ? $deduction->created_at->format('h:i A') : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($deduction->updated_at)
                                            <span class="small text-muted d-block"
                                                title="عدل بواسطة: {{ optional($deduction->updatedBy)->name ?? '---' }} في {{ $deduction->updated_at }}">
                                                {{ $deduction->updated_at->format('Y-m-d') }}
                                            </span>
                                            <span class="small text-muted d-block font-italic"
                                                title="عدل بواسطة: {{ optional($deduction->updatedBy)->name ?? '---' }} في {{ $deduction->updated_at }}">
                                                {{ $deduction->updated_at->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="small text-secondary">لا يوجد تعديل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-secondary px-2 py-1">
                                            {{ optional($deduction->addedBy)->name ?? '---' }}
                                        </span>
                                    </td>
                                    <td style="max-width: 100px;">
                                        <span class="small font-italic text-secondary d-inline-block text-truncate"
                                            style="max-width: 60px;" title="{{ $deduction->notes }}">
                                            {{ $deduction->notes ?? '---' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($deduction->is_archived == 1)
                                            <span class="badge badge-danger px-3 py-2">
                                                <i class="fas fa-times-circle"></i>
                                                مؤرشف</span>
                                        @else
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-check-circle"></i>
                                                غير مؤرشف</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-deduction"
                                            id="delete-deduction-btn" data-id="{{ $deduction->id }}"
                                            data-main-salary-employee-id="{{ $deduction->main_salary_employee_id }}">
                                            <i class="fas fa-trash mr-1"></i> حذف
                                        </button>
                                        <button class="btn btn-warning btn-sm edit-deduction" id="edit-deduction-btn"
                                            data-main-salary-employee-id="{{ $deduction->main_salary_employee_id }}"
                                            data-id="{{ $deduction->id }}">
                                            <i class="fas fa-edit mr-1"></i> تعديل
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12">
                                        <div class="alert alert-warning mb-0 text-center py-3">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                            لا توجد سجلات خصومات للموظفين في هذا الشهر المالي حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $mainSalaryEmployeeDeductionTypes->links() }}
                </div>
            </div>


        </div>
    </div>
</div>
</div>



<!-- Add Modal -->
<div class="modal fade " id="addMainSalaryRecordDeductionTypeModal" tabindex="0" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    إضافة خصم شهري جديد
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
                            <label>نوع الخصم</label>
                            <select name="deduction_type_id" id="deduction_type_id" class="form-control select2">
                                <option value="">اختر نوع الخصم</option>
                                @foreach ($deductionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>مبلغ الخصم</label>
                            <input type="number" name="amount" value="0" id="amount"
                                class="form-control" placeholder="أدخل مبلغ الخصم" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-12 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_add_deduction">
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
<div class="modal fade " id="editMainSalaryRecordDeductionTypeModal" tabindex="0" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-usd"></i>
                    تعديل خصم شهري
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body" id="edit_months_modal_body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>بيانات الموظفين</label>
                            <select name="employee_id" id="edit_employee_id" class="form-control" disabled>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>نوع الخصم</label>
                            <select name="deduction_type_id" id="edit_deduction_type_id" class="form-control select2">
                                <option value="">اختر نوع الخصم</option>
                                @foreach ($deductionTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>مبلغ الخصم</label>
                            <input type="number" name="amount" value="0" id="edit_amount"
                                class="form-control" step="0.01" min="0">
                        </div>
                    </div>
                    <div class="col-md-12 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="edit_notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" id="edit_deduction_id" name="id">
                        <input type="hidden" id="edit_main_salary_employee_id" name="main_salary_employee_id">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_edit_deduction">
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

            $(document).on('change', '#employee_id', function() {
                var employee_id = $(this).val();
                if (employee_id) {
                    $('.related_to_employee').show();
                } else {
                    $('.related_to_employee').hide();
                }
            });

            $(document).on('click', '#submit_add_deduction', function(e) {
                var employee_id = $('#employee_id').val();
                if (employee_id == '') {
                    $('#employee_id').addClass('is-invalid');
                    alert('اختر الموظف');
                    return false;
                } else {
                    $('#employee_id').removeClass('is-invalid');
                }
                var deduction_type_id = $('#deduction_type_id').val();
                if (deduction_type_id == '') {
                    $('#deduction_type_id').addClass('is-invalid');
                    alert('اختر نوع الخصم');
                    return false;
                } else {
                    $('#deduction_type_id').removeClass('is-invalid');
                }
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-deduction-types.ajax-check') }}",
                    dataType: "json",
                    cache: false,
                    method: "POST",
                    data: {
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id: employee_id,
                        deduction_type_id: deduction_type_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            var res = confirm(' يوجد عدد  ' + response.count +
                                '  خصومات لنفس الموظف ونفس الشهر ونفس النوع هل تريد الاضافة');
                            if (res == true) {
                                var flag = true;
                            } else {
                                var flag = false;
                            }
                        } else {
                            var flag = true;
                        }
                        if (flag) {
                            $.ajax({
                                url: "{{ route('admin.main-salary-employee-deduction-types.store') }}",
                                dataType: "json",
                                cache: false,
                                method: "POST",
                                data: {
                                    finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                                    employee_id: employee_id,
                                    deduction_type_id: deduction_type_id,
                                    amount: $('#amount').val(),
                                    notes: $('#notes').val(),
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    if (response.status == 'true') {
                                        alert(response.message);
                                        $('#addMainSalaryRecordDeductionTypeModal')
                                            .modal('hide');
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
            $(document).on('change', '#deduction_type_search', function() {
                ajax_search();
            });
            $(document).on('change', '#is_archived_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var employee_id_search    = $('#employee_id_search').val();
                var deduction_type_search = $('#deduction_type_search').val();
                var is_archived_search    = $('#is_archived_search').val();
                $.ajax({
                    url: '{{ route('admin.main-salary-employee-deduction-types.ajax-search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        deduction_type_search: deduction_type_search,
                        is_archived_search: is_archived_search
                    },
                    success: function(mainSalaryEmployeeDeductionTypes) {
                        $('#ajax_responce_search').html(mainSalaryEmployeeDeductionTypes);
                    },
                    error: function(xhr) {}
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search    = $('#employee_id_search').val();
                var deduction_type_search = $('#deduction_type_search').val();
                var is_archived_search    = $('#is_archived_search').val();
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
                        deduction_type_search: deduction_type_search,
                        is_archived_search: is_archived_search
                    },
                    success: function(mainSalaryEmployeeDeductionTypes) {
                        $('#ajax_responce_search').html(mainSalaryEmployeeDeductionTypes);
                    },
                    error: function(xhr) {}
                });
            });

            $(document).on('click', '#delete-deduction-btn', function() {
                var id = $(this).data('id');
                var main_salary_employee_id = $(this).data('main-salary-employee-id');
                var res = confirm('هل انت متاكد من حذف هذا الخصم');
                if (res == true) {
                    $.ajax({
                        url: "{{ route('admin.main-salary-employee-deduction-types.destroy') }}",
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                            main_salary_employee_id: main_salary_employee_id
                        },
                        success: function(response) {
                            if (response.status == 'true') {
                                alert(response.message);
                                ajax_search();
                            } else {
                                alert(response.message ||
                                    'عفوا، حدث خطأ أثناء الحذف.');
                            }
                        },
                        error: function(xhr) {
                            alert(
                                'عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.'
                            );
                        }
                    })
                }
            });

            $(document).on('click', '.edit-deduction', function() {
                var id = $(this).data('id');
                var main_salary_employee_id = $(this).data('main-salary-employee-id');
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-deduction-types.edit') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        main_salary_employee_id: main_salary_employee_id
                    },
                    cache: false,
                    success: function(response) {
                        if (response.status == 'true') {
                            var deduction = response.mainSalaryEmployeeDeductionType;
                            var employee  = deduction.employee;
                            var modal     = $('#editMainSalaryRecordDeductionTypeModal');

                            modal.find("#edit_deduction_id").val(deduction.id);
                            modal.find("#edit_main_salary_employee_id").val(deduction.main_salary_employee_id);

                            modal.find("#edit_employee_id").html(`
                                <option value="${employee.id}" selected>
                                    ${employee.name}
                                </option>
                            `);

                            modal.find("#edit_deduction_type_id").val(deduction.deduction_type_id).trigger('change');
                            modal.find("#edit_amount").val(deduction.amount);
                            modal.find("#edit_notes").val(deduction.notes);

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

            // Submit Edit Form via AJAX
            $(document).on('click', '#submit_edit_deduction', function(e) {
                var id                      = $('#edit_deduction_id').val();
                var deduction_type_id       = $('#edit_deduction_type_id').val();
                if (deduction_type_id == '') {
                    $('#edit_deduction_type_id').addClass('is-invalid');
                    alert('اختر نوع الخصم');
                    return false;
                } else {
                    $('#edit_deduction_type_id').removeClass('is-invalid');
                }
                var amount                  = $('#edit_amount').val();
                var notes                   = $('#edit_notes').val();
                var finance_monthly_calendar_id = {{ $financeMonthlyCalendar->id }};
                var main_salary_employee_id = $('#edit_main_salary_employee_id').val();
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-deduction-types.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        id: id,
                        deduction_type_id: deduction_type_id,
                        amount: amount,
                        notes: notes,
                        finance_monthly_calendar_id: finance_monthly_calendar_id,
                        main_salary_employee_id: main_salary_employee_id,
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#editMainSalaryRecordDeductionTypeModal').modal('hide');
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

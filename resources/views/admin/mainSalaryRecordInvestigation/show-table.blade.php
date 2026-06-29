<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-calendar-alt text-info mr-2"></i>
                        تحقيقات شهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.main-salary-employee-investigations.index') }}"
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
                    <i class="fas fa-search text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي عدد التحقيقات</span>
                    <span class="info-box-number">{{ $investigations2->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-archive text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">التحقيقات المؤرشفة</span>
                    <span class="info-box-number">
                        {{ $investigations2->where('is_archived', 1)->count() }}
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
                    <span class="info-box-text">التحقيقات الغير مؤرشفة</span>
                    <span class="info-box-number">
                        {{ $investigations2->where('is_archived', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users text-white"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد الموظفين المعنيين</span>
                    <span class="info-box-number text-info font-weight-bold">
                        {{ $investigations2->pluck('employee_id')->unique()->count() }}
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
                سجل التحقيقات الإدارية للموظفين للشهر
            </h3>
            <div class="card-tools">

                @if ($financeMonthlyCalendar->status == 1)
                    <button type="button" class="btn btn-primary btn-sm shadow-sm" data-toggle="modal"
                        data-target="#addInvestigationModal">

                        <i class="fas fa-list-plus"></i>
                        إضافة تحقيق جديد
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

            <form action="{{ route('admin.main-salary-employee-investigations.print-search') }}" method="POST"
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
                            <label>نوع الإضافة</label>
                            <select name="is_auto_search" id="is_auto_search" class="form-control select2">
                                <option value="">الكل</option>
                                <option value="0">يدوي</option>
                                <option value="1">تلقائي</option>
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
                                <th>وصف التحقيق</th>
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
                            @forelse ($investigations as $investigation)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                            {{ $investigation->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $investigation->employee->name ?? '---' }}
                                    </td>
                                    <td style="max-width: 200px;">
                                        <span class="small d-inline-block text-truncate" style="max-width: 180px;"
                                            title="{{ $investigation->description }}">
                                            {{ $investigation->description ?? '---' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($investigation->is_auto == 1)
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
                                            title="أضيف بواسطة: {{ optional($investigation->addedBy)->name ?? '---' }} في {{ $investigation->created_at }}">
                                            {{ $investigation->created_at ? $investigation->created_at->format('Y-m-d') : '---' }}
                                        </span>
                                        <span class="small text-muted d-block font-italic"
                                            title="أضيف بواسطة: {{ optional($investigation->addedBy)->name ?? '---' }} في {{ $investigation->created_at }}">
                                            {{ $investigation->created_at ? $investigation->created_at->format('h:i A') : '' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($investigation->updated_at)
                                            <span class="small text-muted d-block"
                                                title="عدل بواسطة: {{ optional($investigation->updatedBy)->name ?? '---' }} في {{ $investigation->updated_at }}">
                                                {{ $investigation->updated_at->format('Y-m-d') }}
                                            </span>
                                            <span class="small text-muted d-block font-italic"
                                                title="عدل بواسطة: {{ optional($investigation->updatedBy)->name ?? '---' }} في {{ $investigation->updated_at }}">
                                                {{ $investigation->updated_at->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="small text-secondary">لا يوجد تعديل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-secondary px-2 py-1">
                                            {{ optional($investigation->addedBy)->name ?? '---' }}
                                        </span>
                                    </td>
                                    <td style="max-width: 100px;">
                                        <span class="small font-italic text-secondary d-inline-block text-truncate"
                                            style="max-width: 60px;" title="{{ $investigation->notes }}">
                                            {{ $investigation->notes ?? '---' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($investigation->is_archived == 1)
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
                                        <button class="btn btn-danger btn-sm delete-investigation"
                                            id="delete-investigation-btn" data-id="{{ $investigation->id }}">
                                            <i class="fas fa-trash mr-1"></i> حذف
                                        </button>
                                        <button class="btn btn-warning btn-sm edit-investigation"
                                            id="edit-investigation-btn" data-id="{{ $investigation->id }}">
                                            <i class="fas fa-edit mr-1"></i> تعديل
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">
                                        <div class="alert alert-warning mb-0 text-center py-3">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                            لا توجد سجلات تحقيقات إدارية للموظفين في هذا الشهر المالي حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $investigations->links() }}
                </div>
            </div>


        </div>
    </div>
</div>
</div>



<!-- Add Investigation Modal -->
<div class="modal fade" id="addInvestigationModal" tabindex="0" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-search"></i>
                    إضافة تحقيق إداري جديد
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>بيانات الموظفين</label>
                            <select name="employee_id" id="employee_id"
                                class="form-control select2 {{ $errors->has('employee_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر الموظف</option>
                                @foreach ($employees_has_opened_monthly_record as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>وصف التحقيق</label>
                            <textarea name="description" id="description" class="form-control" rows="2"
                                placeholder="أدخل وصف التحقيق الإداري"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>

                    {{-- تنبيه التحقيقات السابقة --}}
                    <div class="col-md-12" id="prev_investigation_alert" style="display:none;">
                        <div class="alert alert-warning border-right-0 border-left border-left-4 mb-3" id="prev_investigation_msg">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_add_investigation">
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
<!-- Edit Investigation Modal -->
<div class="modal fade" id="editInvestigationModal" tabindex="0" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i>
                    تعديل تحقيق إداري
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>بيانات الموظف</label>
                            <select name="employee_id" id="edit_employee_id" class="form-control" disabled>
                                <!-- Populated dynamically -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>وصف التحقيق</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12 edit_related_to_employee" style="display: none;">
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea type="text" name="notes" id="edit_notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" id="edit_investigation_id" name="id">
                        <button type="submit" class="btn btn-success shadow px-4" id="submit_edit_investigation">
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
                $('#prev_investigation_alert').hide();
                if (employee_id) {
                    $('.related_to_employee').show();

                    // استدعاء ajaxCheck للتحقق من التحقيقات السابقة
                    $.ajax({
                        url: '{{ route('admin.main-salary-employee-investigations.ajax-check') }}',
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            employee_id: employee_id,
                            finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }}
                        },
                        success: function(response) {
                            if (response.status === 'true' && response.total_count > 0) {
                                var msg = '';
                                if (response.total_count > 0) {
                                    msg += '<i class="fas fa-exclamation-triangle mr-1"></i> ';
                                    msg += '<strong>تنبيه:</strong> لهذا الموظف <span class="badge badge-danger px-2">' + response.total_count + '</span> تحقيق إداري سابق بشكل إجمالي';
                                    if (response.current_month_count > 0) {
                                        msg += ' &nbsp;|&nbsp; هذا الشهر: <span class="badge badge-warning px-2">' + response.current_month_count + '</span>';
                                    }
                                    if (response.previous_count > 0) {
                                        msg += ' &nbsp;|&nbsp; شهور سابقة: <span class="badge badge-secondary px-2">' + response.previous_count + '</span>';
                                    }
                                }
                                $('#prev_investigation_msg').html(msg);
                                $('#prev_investigation_alert').show();
                            } else {
                                $('#prev_investigation_alert').hide();
                            }
                        },
                        error: function() {}
                    });

                } else {
                    $('.related_to_employee').hide();
                }
            });

            $(document).on('click', '#submit_add_investigation', function(e) {
                var employee_id = $('#employee_id').val();
                if (employee_id == '') {
                    $('#employee_id').addClass('is-invalid');
                    alert('اختر الموظف');
                    return false;
                } else {
                    $('#employee_id').removeClass('is-invalid');
                }

                var description = $('#description').val();
                if (description.trim() == '') {
                    $('#description').addClass('is-invalid');
                    alert('أدخل وصف التحقيق الإداري');
                    return false;
                } else {
                    $('#description').removeClass('is-invalid');
                }

                $.ajax({
                    url: "{{ route('admin.main-salary-employee-investigations.store') }}",
                    dataType: "json",
                    cache: false,
                    method: "POST",
                    data: {
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id: employee_id,
                        description: description,
                        notes: $('#notes').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#addInvestigationModal').modal('hide');
                            window.location.reload();
                        } else {
                            alert(response.message || 'عفوا، حدث خطأ أثناء الحفظ.');
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('عفوا، حدث خطأ غير متوقع أثناء الاتصال بالخادم.');
                    }
                });
            });

            $(document).on('change', '#employee_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#is_archived_search', function() {
                ajax_search();
            });
            $(document).on('change', '#is_auto_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var employee_id_search = $('#employee_id_search').val();
                var is_archived_search = $('#is_archived_search').val();
                var is_auto_search     = $('#is_auto_search').val();
                $.ajax({
                    url: '{{ route('admin.main-salary-employee-investigations.ajax-search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        is_archived_search: is_archived_search,
                        is_auto_search: is_auto_search
                    },
                    success: function(investigations) {
                        $('#ajax_responce_search').html(investigations);
                    },
                    error: function(xhr) {}
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search = $('#employee_id_search').val();
                var is_archived_search = $('#is_archived_search').val();
                var is_auto_search     = $('#is_auto_search').val();
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
                        is_archived_search: is_archived_search,
                        is_auto_search: is_auto_search
                    },
                    success: function(investigations) {
                        $('#ajax_responce_search').html(investigations);
                    },
                    error: function(xhr) {}
                });
            });

            $(document).on('click', '#delete-investigation-btn', function() {
                var id = $(this).data('id');
                var res = confirm('هل انت متاكد من حذف هذا التحقيق الإداري؟');
                if (res == true) {
                    $.ajax({
                        url: "{{ route('admin.main-salary-employee-investigations.destroy') }}",
                        type: 'POST',
                        dataType: 'json',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id,
                            finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
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
                    });
                }
            });

            $(document).on('click', '.edit-investigation', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-investigations.edit') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                    },
                    cache: false,
                    success: function(response) {
                        if (response.status == 'true') {
                            var investigation = response.investigation;
                            var employee = investigation.employee;
                            var modal = $('#editInvestigationModal');

                            modal.find("#edit_investigation_id").val(investigation.id);

                            modal.find("#edit_employee_id").html(`
                                <option value="${employee.id}" selected>
                                    ${employee.name}
                                </option>
                            `);

                            modal.find("#edit_description").val(investigation.description);
                            modal.find("#edit_notes").val(investigation.notes);

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
            $(document).on('click', '#submit_edit_investigation', function(e) {
                var id          = $('#edit_investigation_id').val();
                var description = $('#edit_description').val();
                if (description.trim() == '') {
                    $('#edit_description').addClass('is-invalid');
                    alert('أدخل وصف التحقيق الإداري');
                    return false;
                } else {
                    $('#edit_description').removeClass('is-invalid');
                }
                var notes = $('#edit_notes').val();
                var finance_monthly_calendar_id = {{ $financeMonthlyCalendar->id }};
                $.ajax({
                    url: "{{ route('admin.main-salary-employee-investigations.update') }}",
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PUT',
                        id: id,
                        description: description,
                        notes: notes,
                        finance_monthly_calendar_id: finance_monthly_calendar_id,
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            alert(response.message);
                            $('#editInvestigationModal').modal('hide');
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

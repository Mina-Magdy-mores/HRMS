@extends('admin.layouts.admin')

@section('title', 'تفاصيل بصمة الموظف')

@section('contentHeader')
    <i class="fas fa-fingerprint"></i>
    تفاصيل بصمة الموظف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.attendanceDepartures.index') }}">بصمة الموظفين</a>
@endsection

@section('contentHeaderActive', 'تفاصيل البصمة')

@section('content')
<div class="container-fluid">

    <!-- Top Navigation & Actions Bar -->
    <div class="card card-outline card-info shadow mb-4 d-print-none">
            <div class="card-body py-3">
                <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-user-clock text-info mr-2"></i>
                        تفاصيل حركات بصمة الموظف: <span class="text-primary">{{ $employee->name }}</span>
                    </h5>
                </div>
                <div class="col-md-6 text-right">
                    <!-- Button to open archive modal -->
                    <button type="button" class="btn btn-primary btn-sm mr-2 shadow-sm" data-toggle="modal" data-target="#fingerprintArchiveModal">
                        <i class="fas fa-archive mr-1"></i> عرض سجل أرشيف البصمة كامل
                    </button>
                    <a href="{{ route('admin.attendanceDepartures.show', $financeMonthlyCalendar->id) }}"
                        class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> العودة لجدول الموظفين
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Block -->
    <div class="card card-primary card-outline shadow mb-4 d-print-none">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Employee Image -->
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if ($employee->image)
                        <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                            class="img-thumbnail rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff;">
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle shadow-sm" 
                            style="width: 120px; height: 120px; border: 3px solid #dee2e6; font-size: 3rem;">
                            <i class="fas fa-user text-secondary"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Employee Details -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">كود الموظف</span>
                            <span class="badge badge-secondary px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $employee->employee_code ?? '---' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">كود البصمة</span>
                            <span class="badge badge-info px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $employee->fingerprint_code ?? 'لم يتم التعيين' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">الشهر المالي الحالي</span>
                            <span class="badge badge-primary px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $financeMonthlyCalendar->month->name }} ({{ $financeMonthlyCalendar->finance_yr }})
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>الفرع: </strong> <span class="text-dark">{{ optional($employee->branch)->name ?? '---' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>الإدارة: </strong> <span class="text-dark">{{ optional($employee->department)->name ?? '---' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>الوظيفة: </strong> <span class="text-dark">{{ optional($employee->job)->name ?? '---' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Switcher & Statistics Card -->
    <div class="card card-primary card-outline shadow mb-4">
        <div class="card-header py-3 d-print-none">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="card-title text-primary font-weight-bold mb-0">
                        <i class="fas fa-calendar-check mr-2 text-info"></i>
                        سجل بصمة الموظف اليومية للشهر المالي
                    </h4>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" id="btn_print_grid" class="btn btn-info btn-sm shadow-sm font-weight-bold mx-1">
                        <i class="fas fa-print mr-1"></i> طباعة الجدول
                    </button>
                    <button type="button" id="btn_reload_grid" class="btn btn-warning btn-sm shadow-sm font-weight-bold">
                        <i class="fas fa-sync-alt mr-1"></i> إعادة تحميل بصمة الشهر
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3 d-print-none">
                <div class="col-md-4">
                    <div class="form-group mb-0">
                        <label for="select_finance_monthly_calendar" class="font-weight-bold text-secondary">اختر شهر سجل الراتب المالي:</label>
                        <select id="select_finance_monthly_calendar" class="form-control select2">
                            @foreach ($financeMonthlyCalendars as $calendar)
                                <option value="{{ $calendar->id }}" {{ $calendar->id == $financeMonthlyCalendar->id ? 'selected' : '' }}>
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
            </div>

            <!-- Fingerprint Upload Stats Panel -->
            <div class="card border-0 shadow-sm mb-4 bg-light d-print-none" id="fingerprint_stats_card" style="border-right: 5px solid #28a745 !important;">
                <div class="card-body p-3">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="d-block text-muted small font-weight-bold">تاريخ سحب البصمة الحالي:</span>
                            <span class="font-weight-bold text-dark" id="stat_last_uploaded_date" style="font-size: 1rem;">
                                <i class="far fa-calendar-alt text-success mr-1"></i> ---
                            </span>
                            <span class="d-inline-block text-muted small mr-2" id="stat_last_uploaded_by_parent">
                                (بواسطة: <strong id="stat_last_uploaded_by">---</strong>)
                            </span>
                        </div>
                        <div class="col-md-6 border-right pr-md-4">
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

        </div>
    </div>

</div>

<!-- Complete Archive Modal -->
<div class="modal fade shadow-lg" id="fingerprintArchiveModal" tabindex="-1" role="dialog" aria-labelledby="fingerprintArchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="fingerprintArchiveModalLabel">
                    <i class="fas fa-history mr-2"></i>
                    سجل أرشيف البصمة كامل للموظف: {{ $employee->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                @if ($allFingerprintArchive->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center align-middle mb-0">
                            <thead class="bg-dark text-white" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>الشهر المالي</th>
                                    <th>تاريخ الحركة</th>
                                    <th>وقت الحركة</th>
                                    <th>نوع الحركة</th>
                                    <th>تاريخ سحب البصمة</th>
                                    <th>بواسطة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allFingerprintArchive as $archive)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold text-primary">
                                            @if ($archive->financeMonthlyCalendar)
                                                {{ $archive->financeMonthlyCalendar->month->name }} ({{ $archive->financeMonthlyCalendar->finance_yr }})
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->locale('ar')->translatedFormat('l') }} ،
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->format('Y-m-d') }}
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->format('h:i A') }}
                                        </td>
                                        <td>
                                            @if ($archive->type == 1)
                                                <span class="badge badge-success px-3 py-1">
                                                    حضور
                                                </span>
                                            @elseif ($archive->type == 2)
                                                <span class="badge badge-info px-3 py-1">
                                                    انصراف
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-1">
                                                    غير محدد
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $archive->created_at ? $archive->created_at->format('Y-m-d h:i A') : '---' }}
                                        </td>
                                        <td>{{ optional($archive->addedBy)->name ?? 'النظام' }}</td>
                                        <td>{{ $archive->notes ?? '---' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center m-4 py-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        لا توجد أي حركات بصمة مسجلة للموظف في الأرشيف بالكامل.
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">إغلاق الأرشيف</button>
            </div>
        </div>
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

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .border-right-custom-stat {
        border-right: 1px solid #dee2e6;
    }
    @media (max-width: 767.98px) {
        .border-right-custom-stat {
            border-right: none !important;
            margin-top: 10px;
        }
    }
    .btn-xs {
        padding: .125rem .25rem;
        font-size: .75rem;
        line-height: 1.5;
        border-radius: .15rem;
    }
    #attendanceGridTable input[type="number"] {
        min-width: 70px;
    }
    #attendanceGridTable select {
        min-width: 100px;
        padding-left: 2px;
        padding-right: 2px;
    }
</style>
@endsection

@section('js')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    function initSelect2() {
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    }

    function loadGrid() {
        var calendarId = $('#select_finance_monthly_calendar').val();
        var employeeId = '{{ $employee->id }}';
        
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
        initSelect2();
        loadGrid();

        // Toggle occasion dropdown based on vacation type
        $(document).on('change', '.select-vacation', function() {
            var row = $(this).closest('tr');
            var isOfficial = $(this).find('option:selected').data('is-official') == 1;
            var occasionSelect = row.find('select[name="occasion_id"]');
            if (isOfficial) {
                occasionSelect.prop('disabled', false);
            } else {
                occasionSelect.val(0).prop('disabled', true);
            }
        });

        // Reload grid on switcher change
        $('#select_finance_monthly_calendar').on('change', function() {
            loadGrid();
        });

        // Reload grid on reload button click
        $('#btn_reload_grid').on('click', function() {
            loadGrid();
        });

        // Print grid on print button click - opens dedicated print tab
        $('#btn_print_grid').on('click', function() {
            var calendarId = $('#select_finance_monthly_calendar').val();
            var employeeId = '{{ $employee->id }}';
            var url = '{{ route('admin.attendanceDepartures.finger-print-details.print', [':id', ':calendar_id']) }}';
            url = url.replace(':id', employeeId).replace(':calendar_id', calendarId);
            window.open(url, '_blank');
        });

        // Save row AJAX
        $(document).on('click', '.save-row-btn', function() {
            var btn = $(this);
            var row = btn.closest('tr');
            var date = row.data('date');
            var employeeId = '{{ $employee->id }}';
            var calendarId = $('#select_finance_monthly_calendar').val();
            
            var total_hours = row.find('input[name="total_hours"]').val();
            var overtime_hours = row.find('input[name="overtime_hours"]').val();
            var absence_hours = row.find('input[name="absence_hours"]').val();
            var cutting_days = row.find('input[name="cutting_days"]').val();
            var variables = row.find('input[name="variables"]').val();
            var vacation_id = row.find('select[name="vacation_id"]').val();
            var occasion_id = row.find('select[name="occasion_id"]').val();
            var attendance_delay = row.find('input[name="attendance_delay"]').val();
            var early_departure = row.find('input[name="early_departure"]').val();
            var approved_delay = row.find('input[name="approved_attendance_delay_early_departure"]').val();
            var is_action = row.find('select[name="is_action_made_on_employee"]').val();
            var notes = row.find('input[name="notes"]').val();
            
            var originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i>').attr('disabled', true);
            
            $.ajax({
                url: '{{ route('admin.attendanceDepartures.finger-print-details.save-row') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: employeeId,
                    finance_monthly_calendar_id: calendarId,
                    date: date,
                    total_hours: total_hours,
                    overtime_hours: overtime_hours,
                    absence_hours: absence_hours,
                    cutting_days: cutting_days,
                    variables: variables,
                    vacation_id: vacation_id,
                    occasion_id: occasion_id,
                    attendance_delay: attendance_delay,
                    early_departure: early_departure,
                    approved_attendance_delay_early_departure: approved_delay,
                    is_action_made_on_employee: is_action,
                    notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    btn.html('<i class="fas fa-check-circle"></i>').removeClass('btn-success').addClass('btn-success');
                    setTimeout(function() {
                        btn.html(originalHtml).attr('disabled', false);
                    }, 1500);
                },
                error: function(xhr) {
                    btn.html('<i class="fas fa-times-circle"></i>').removeClass('btn-success').addClass('btn-danger');
                    var errMsg = 'حدث خطأ أثناء الحفظ.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
                    setTimeout(function() {
                        btn.html(originalHtml).removeClass('btn-danger').addClass('btn-success').attr('disabled', false);
                    }, 2000);
                }
            });
        });

        // Save all rows AJAX
        $(document).on('click', '#btn_save_all', function() {
            var btn = $(this);
            var cancelBtn = $('#btn_cancel_all');
            var employeeId = '{{ $employee->id }}';
            var calendarId = $('#select_finance_monthly_calendar').val();
            
            var rows = [];
            $('#attendanceGridTable tbody tr').each(function() {
                var row = $(this);
                rows.push({
                    date: row.data('date'),
                    total_hours: row.find('input[name="total_hours"]').val(),
                    overtime_hours: row.find('input[name="overtime_hours"]').val(),
                    absence_hours: row.find('input[name="absence_hours"]').val(),
                    cutting_days: row.find('input[name="cutting_days"]').val(),
                    variables: row.find('input[name="variables"]').val(),
                    vacation_id: row.find('select[name="vacation_id"]').val(),
                    occasion_id: row.find('select[name="occasion_id"]').val(),
                    attendance_delay: row.find('input[name="attendance_delay"]').val(),
                    early_departure: row.find('input[name="early_departure"]').val(),
                    approved_attendance_delay_early_departure: row.find('input[name="approved_attendance_delay_early_departure"]').val(),
                    is_action_made_on_employee: row.find('select[name="is_action_made_on_employee"]').val(),
                    notes: row.find('input[name="notes"]').val()
                });
            });

            var originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> جاري حفظ الكل...').attr('disabled', true);
            cancelBtn.attr('disabled', true);

            $.ajax({
                url: '{{ route('admin.attendanceDepartures.finger-print-details.save-all') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: employeeId,
                    finance_monthly_calendar_id: calendarId,
                    rows: rows
                },
                dataType: 'json',
                success: function(response) {
                    btn.html('<i class="fas fa-check-double"></i> تم حفظ الكل').removeClass('btn-success').addClass('btn-success');
                    setTimeout(function() {
                        btn.html(originalHtml).attr('disabled', false);
                        cancelBtn.attr('disabled', false);
                        loadGrid();
                    }, 1500);
                },
                error: function(xhr) {
                    btn.html('<i class="fas fa-times-circle"></i> فشل الحفظ').removeClass('btn-success').addClass('btn-danger');
                    var errMsg = 'حدث خطأ أثناء حفظ التعديلات.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
                    setTimeout(function() {
                        btn.html(originalHtml).removeClass('btn-danger').addClass('btn-success').attr('disabled', false);
                        cancelBtn.attr('disabled', false);
                    }, 2000);
                }
            });
        });

        // Cancel all changes and reload grid
        $(document).on('click', '#btn_cancel_all', function() {
            if (confirm('هل أنت متأكد من إلغاء كافة التعديلات والتراجع عنها؟')) {
                loadGrid();
            }
        });

        // Load movements modal
        $(document).on('click', '.view-day-movements-btn', function() {
            var btn = $(this);
            var row = btn.closest('tr');
            var date = row.data('date');
            var employeeId = '{{ $employee->id }}';
            
            $('#modal_day_display').text(date);
            $('#modal_movements_content').html(`
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-info mb-2"></i>
                    <p class="text-muted">جاري تحميل حركات اليوم...</p>
                </div>
            `);
            
            $('#dayMovementsModal').modal('show');
            
            var calendarId = $('#select_finance_monthly_calendar').val();
            
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

        // Update day movements manual override AJAX
        $(document).on('click', '#btn_update_day_movements', function() {
            var btn = $(this);
            var form = $('#edit_day_movements_form');
            var calendarId = $('#select_finance_monthly_calendar').val();
            
            var check_in_date = $('#edit_check_in_date').val();
            var check_in_time = $('#edit_check_in_time').val();
            var check_out_date = $('#edit_check_out_date').val();
            var check_out_time = $('#edit_check_out_time').val();
            
            var originalHtml = btn.html();
            btn.html('<i class="fas fa-spinner fa-spin"></i> جاري التحديث...').attr('disabled', true);
            
            $.ajax({
                url: '{{ route('admin.attendanceDepartures.finger-print-details.update-day-movements') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: '{{ $employee->id }}',
                    finance_monthly_calendar_id: calendarId,
                    date: form.find('input[name="date"]').val(),
                    check_in_date: check_in_date,
                    check_in_time: check_in_time,
                    check_out_date: check_out_date,
                    check_out_time: check_out_time
                },
                dataType: 'json',
                success: function(response) {
                    $('#dayMovementsModal').modal('hide');
                    loadGrid(); // Refresh the main big table
                    
                    // Simple toast notification
                    var alertDiv = $(`<div class="alert alert-success alert-dismissible fade show shadow" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 300px;">
                        <i class="fas fa-check-circle mr-2"></i> ${response.success}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>`).appendTo('body');
                    setTimeout(function() {
                        alertDiv.alert('close');
                    }, 4000);
                },
                error: function(xhr) {
                    btn.html(originalHtml).attr('disabled', false);
                    var errMsg = 'حدث خطأ أثناء تحديث الحركات.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    }
                    alert(errMsg);
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

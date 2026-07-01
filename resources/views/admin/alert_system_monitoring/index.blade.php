@extends('admin.layouts.admin')

@section('title', 'مراقبة حركات النظام')

@section('contentHeader')
    <i class="fas fa-desktop text-primary mr-2"></i> مراقبة حركات النظام
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.system-monitoring.index') }}">مراقبة حركات النظام</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
<div class="container-fluid">
    <!-- Warning if monitoring is deactivated -->
    @if(isset($general_settings) && $general_settings->is_active_system_monitoring == 0)
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            <h5 class="alert-heading font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> مراقب النظام معطل حالياً!</h5>
            <p class="mb-0 small">لن يقوم النظام بتسجيل أي حركات جديدة للموظفين أو الإعدادات حتى تقوم بتفعيل خيار "حالة مراقب النظام" في <a href="{{ route('admin.general-settings') }}" class="font-weight-bold text-dark text-underline">الضبط العام</a>.</p>
            <button type="button" class="close text-right" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-history"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الحركات</span>
                    <span class="info-box-number" id="stats-total">{{ \App\Models\AlertSystemMonitoring::count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-star text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">الحركات المميزة</span>
                    <span class="info-box-number" id="stats-important">{{ \App\Models\AlertSystemMonitoring::where('is_important', 1)->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-user-shield"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">حركاتك اليوم</span>
                    <span class="info-box-number" id="stats-user">{{ \App\Models\AlertSystemMonitoring::where('added_by', Auth::id())->whereDate('created_at', now()->toDateString())->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info"><i class="fas fa-project-diagram"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر حركة مسجلة</span>
                    <span class="info-box-number" id="stats-last" style="font-size: 0.95rem;">
                        @php
                            $lastLog = \App\Models\AlertSystemMonitoring::with('alertMoveType')->latest()->first();
                            echo $lastLog && $lastLog->alertMoveType ? $lastLog->alertMoveType->name : 'لا يوجد';
                        @endphp
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form (Highly Diverse Filters) -->
    <div class="card card-primary card-outline shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-dark font-weight-bold">
                <i class="fas fa-search text-primary mr-1"></i> تصفية وبحث متقدم
            </h3>
        </div>
        <div class="card-body">
            <form id="search_form" onsubmit="return false;">
                <div class="row">
                    <!-- Text Search Input -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group mb-0">
                            <label>نص البحث (الاسم، التفاصيل، الملاحظات)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" id="search_term" class="form-control" placeholder="ابحث هنا...">
                            </div>
                        </div>
                    </div>

                    <!-- Module Select -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group mb-0">
                            <label>القسم / المديول</label>
                            <select id="alert_module_id" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Move Type Select -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group mb-0">
                            <label>نوع الحركة</label>
                            <select id="action_name" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach($moveTypes as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Admin Select -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group mb-0">
                            <label>المسؤول المنفذ</label>
                            <select id="added_by" class="form-control select2">
                                <option value="">الكل</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Importance Select -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group mb-0">
                            <label>الأهمية</label>
                            <select id="is_important" class="form-control">
                                <option value="">الكل</option>
                                <option value="1">مميز بنجمة</option>
                                <option value="0">غير مميز</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group mb-0">
                            <label>من تاريخ</label>
                            <input type="date" id="date_from" class="form-control">
                        </div>
                    </div>

                    <!-- Date To -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group mb-0">
                            <label>إلى تاريخ</label>
                            <input type="date" id="date_to" class="form-control">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-history text-secondary mr-2"></i> سجل الأنشطة والعمليات
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-sm btn-light border" id="print-btn" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> طباعة السجل الحالي
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle mr-1"></i>
                    {{ session('error') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div id="ajax_responce_search">
                @include('admin.alert_system_monitoring.table')
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" role="dialog" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold" id="logDetailsModalLabel">
                    <i class="fas fa-info-circle mr-2"></i> تفاصيل العملية الكاملة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">اسم العملية</label>
                        <h6 class="font-weight-bold text-dark mb-0" id="modal-name">---</h6>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small d-block mb-1">القسم/المديول</label>
                        <span class="badge badge-light border px-2 py-1" id="modal-module">---</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small d-block mb-1">نوع الحركة</label>
                        <span class="badge badge-secondary px-3 py-1 font-weight-bold" id="modal-action">---</span>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">المسؤول المنفذ</label>
                        <h6 class="font-weight-bold text-secondary mb-0" id="modal-admin">---</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">تاريخ العملية</label>
                        <h6 class="font-weight-bold text-secondary mb-0" id="modal-date">---</h6>
                    </div>
                </div>
                <hr class="my-2">
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">تفاصيل التغيير والنشاط</label>
                    <div class="bg-white border rounded p-3 text-dark font-weight-bold shadow-sm" id="modal-content" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;">---</div>
                </div>
                <div class="mb-3 d-none" id="modal-notes-container">
                    <label class="text-muted small d-block mb-1">ملاحظات إضافية</label>
                    <div class="bg-white border border-warning rounded p-3 text-dark" id="modal-notes" style="white-space: pre-wrap; border-right: 4px solid #ffc107 !important;"></div>
                </div>
            </div>
            <div class="modal-footer bg-white border-0">
                <button type="button" class="btn btn-secondary px-4 shadow" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        // Trigger search on typing in search box
        $('#search_term').on('keyup', function() {
            ajax_search();
        });

        // Trigger search on changes in selects and dates
        $('#alert_module_id, #action_name, #added_by, #is_important, #date_from, #date_to').on('change', function() {
            ajax_search();
        });

        // Ajax search logic
        function ajax_search() {
            var search_term = $('#search_term').val();
            var alert_module_id = $('#alert_module_id').val();
            var action_name = $('#action_name').val();
            var added_by = $('#added_by').val();
            var is_important = $('#is_important').val();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();

            $.ajax({
                url: '{{ route('admin.system-monitoring.ajax-search') }}',
                type: 'POST',
                dataType: 'html',
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    search_term: search_term,
                    alert_module_id: alert_module_id,
                    action_name: action_name,
                    added_by: added_by,
                    is_important: is_important,
                    date_from: date_from,
                    date_to: date_to
                },
                success: function(response) {
                    $('#ajax_responce_search').html(response);
                },
                error: function(xhr) {
                    console.log('Error during search');
                }
            });
        }

        // Ajax pagination binding
        $(document).on('click', '#ajax-pagination a', function(e) {
            e.preventDefault();
            var search_term = $('#search_term').val();
            var alert_module_id = $('#alert_module_id').val();
            var action_name = $('#action_name').val();
            var added_by = $('#added_by').val();
            var is_important = $('#is_important').val();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            
            var href = $(this).attr('href');
            var urlObj = new URL(href);
            var page = urlObj.searchParams.get('page');
            var ajaxUrl = '{{ route('admin.system-monitoring.ajax-search') }}?page=' + page;

            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'html',
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    search_term: search_term,
                    alert_module_id: alert_module_id,
                    action_name: action_name,
                    added_by: added_by,
                    is_important: is_important,
                    date_from: date_from,
                    date_to: date_to
                },
                success: function(response) {
                    $('#ajax_responce_search').html(response);
                },
                error: function(xhr) {
                    console.log('Error during pagination');
                }
            });
        });

        // Toggle Important (Star button) via Ajax
        $(document).on('click', '.toggle-important-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var id = btn.data('id');
            
            // Animation scale up
            btn.css('transform', 'scale(1.4)');

            $.ajax({
                url: '{{ url("admin/system-monitoring") }}/' + id + '/toggle-important',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Reset scale
                    btn.css('transform', 'scale(1)');
                    
                    if (response.status === 'success') {
                        var row = $('#log-row-' + id);
                        var icon = btn.find('.star-icon');
                        
                        if (response.is_important == 1) {
                            row.addClass('table-warning font-weight-bold');
                            icon.removeClass('far text-muted').addClass('fas text-warning');
                        } else {
                            row.removeClass('table-warning font-weight-bold');
                            icon.removeClass('fas text-warning').addClass('far text-muted');
                        }

                        // Update important counter card dynamically
                        var statsCount = $('#stats-important');
                        var currentCount = parseInt(statsCount.text());
                        statsCount.text(response.is_important == 1 ? currentCount + 1 : currentCount - 1);
                    }
                },
                error: function(xhr) {
                    btn.css('transform', 'scale(1)');
                    alert('حدث خطأ أثناء محاولة تعديل التمييز.');
                }
            });
        });

        // Show detailed log modal
        $(document).on('click', '.show-details-btn', function() {
            var btn = $(this);
            $('#modal-name').text(btn.data('name'));
            $('#modal-module').text(btn.data('module'));
            $('#modal-action').text(btn.data('action'));
            $('#modal-admin').text(btn.data('admin'));
            $('#modal-date').text(btn.data('date'));
            $('#modal-content').text(btn.data('content'));
            
            var notes = btn.data('notes');
            if (notes && notes.trim() !== "") {
                $('#modal-notes').text(notes);
                $('#modal-notes-container').removeClass('d-none');
            } else {
                $('#modal-notes-container').addClass('d-none');
            }

            $('#logDetailsModal').modal('show');
        });

        // SweetAlert or standard confirm dialog for deletion
        $(document).on('click', '.destroy-btn', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من حذف هذا السجل بشكل نهائي؟ لا يمكن التراجع عن هذا الإجراء.')) {
                $(this).closest('form').submit();
            }
        });
    });
</script>
@endsection
@endsection

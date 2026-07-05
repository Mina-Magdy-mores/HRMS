@extends('admin.layouts.admin')

@section('title', 'مراقبة حركات مراقب النظام الذاتية')

@section('contentHeader')
    <i class="fas fa-user-shield text-danger mr-2"></i> مراقبة حركات مراقب النظام الذاتية
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.system-monitoring.self-logs') }}">المراقبة الذاتية لمراقب النظام</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <h5 class="alert-heading font-weight-bold mb-1"><i class="fas fa-check-circle mr-1"></i> نجاح!</h5>
            <p class="mb-0 small">{{ session('success') }}</p>
            <button type="button" class="close text-right" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <h5 class="alert-heading font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> خطأ!</h5>
            <p class="mb-0 small">{{ session('error') }}</p>
            <button type="button" class="close text-right" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger"><i class="fas fa-shield-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي حركات المراقبة الذاتية</span>
                    <span class="info-box-number" id="stats-total">{{ \App\Models\AlertSystemMonitoringSelfLog::count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-star text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">عمليات التمييز والنجوم</span>
                    <span class="info-box-number" id="stats-stars">{{ \App\Models\AlertSystemMonitoringSelfLog::where('action', 'like', '%تمييز%')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-secondary"><i class="fas fa-trash-alt text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">عمليات حذف السجلات من النظام</span>
                    <span class="info-box-number" id="stats-deletes">{{ \App\Models\AlertSystemMonitoringSelfLog::where('action', 'like', '%حذف%')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card card-danger card-outline shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title text-dark font-weight-bold">
                <i class="fas fa-search text-danger mr-1"></i> تصفية وبحث متقدم في المراقبة الذاتية
            </h3>
        </div>
        <div class="card-body">
            <form id="search_form" onsubmit="return false;">
                <div class="row justify-content-center">
                    <div class="col-md-8 mb-3">
                        <div class="form-group mb-0">
                            <label>نص البحث (الإجراء، اسم السجل المستهدف، تفاصيل المحتوى)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search text-danger"></i></span>
                                </div>
                                <input type="text" id="search_term" class="form-control" placeholder="ابحث هنا...">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div id="ajax_responce_search" class="table-responsive">
                @include('admin.alert_system_monitoring.self_logs_table')
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="selfLogDetailsModal" tabindex="-1" role="dialog" aria-labelledby="selfLogDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold" id="selfLogDetailsModalLabel">
                    <i class="fas fa-info-circle mr-2"></i> تفاصيل حركة المراقبة الذاتية الكاملة
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">الإجراء</label>
                        <span class="badge badge-danger px-3 py-2 font-weight-bold" id="modal-self-action">---</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small d-block mb-1">معرف السجل المستهدف</label>
                        <span class="badge badge-secondary px-3 py-2 font-weight-bold" id="modal-self-target-id">---</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small d-block mb-1">المسؤول المنفذ</label>
                        <h6 class="font-weight-bold text-dark mb-0" id="modal-self-admin">---</h6>
                    </div>
                </div>
                <hr class="my-2">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">اسم السجل المستهدف</label>
                        <h6 class="font-weight-bold text-secondary mb-0" id="modal-self-target-name">---</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small d-block mb-1">تاريخ العملية</label>
                        <h6 class="font-weight-bold text-secondary mb-0" id="modal-self-date">---</h6>
                    </div>
                </div>
                <hr class="my-2">
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">التفاصيل الأصلية للحركة</label>
                    <div class="bg-white border rounded p-3 text-dark font-weight-bold shadow-sm" id="modal-self-content" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;">---</div>
                </div>
            </div>
            <div class="modal-footer bg-white py-2">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Trigger search on typing
        $('#search_term').on('keyup', function() {
            ajax_search();
        });

        function ajax_search() {
            var search_term = $('#search_term').val();

            $.ajax({
                url: '{{ route('admin.system-monitoring.self-logs') }}',
                type: 'GET',
                dataType: 'html',
                cache: false,
                data: {
                    search_term: search_term
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
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var search_term = $('#search_term').val();
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                cache: false,
                data: {
                    search_term: search_term
                },
                success: function(response) {
                    $('#ajax_responce_search').html(response);
                },
                error: function(xhr) {
                    console.log('Error during pagination');
                }
            });
        });

        // Show detailed self log modal
        $(document).on('click', '.show-details-btn', function() {
            var btn = $(this);
            $('#modal-self-action').text(btn.data('action'));
            $('#modal-self-target-id').text('#' + btn.data('target-id'));
            $('#modal-self-admin').text(btn.data('admin'));
            $('#modal-self-target-name').text(btn.data('target-name'));
            $('#modal-self-date').text(btn.data('date'));
            $('#modal-self-content').text(btn.data('content'));

            $('#selfLogDetailsModal').modal('show');
        });
    });
</script>
@endsection

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
    });
</script>
@endsection

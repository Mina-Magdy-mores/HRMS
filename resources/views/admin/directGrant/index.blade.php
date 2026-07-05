@extends('admin.layouts.admin')

@section('title', 'المنح المالية المباشرة (خارج المرتب)')

@section('css')
    <link class="styles" rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link class="styles" rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentHeader')
    <i class="fas fa-gift text-primary"></i> المنح المالية المباشرة (خارج المرتب)
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.direct-grants.index') }}">المنح المالية المباشرة</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
<div class="container-fluid">

    <!-- Dashboard Cards -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-hand-holding-usd"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">إجمالي عمليات صرف المنح</span>
                    <span class="info-box-number text-lg text-primary">{{ $directGrants->total() }} عملية</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">إجمالي مبالغ المنح</span>
                    <span class="info-box-number text-lg text-success">{{ number_format($directGrants->sum('amount'), 2) }} ج.م</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-user-check text-white"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">آخر منحة منصرفة</span>
                    <span class="info-box-number text-lg text-dark">
                        {{ $directGrants->first() ? $directGrants->first()->employee->name : 'لا يوجد' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold text-dark mt-1">
                <i class="fas fa-list-ul mr-1 text-primary"></i> سجل المنح المالية المباشرة للموظفين
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.direct-grants.create') }}" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                    <i class="fas fa-plus-circle mr-1"></i> تسجيل منحة جديدة
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Search Filters -->
            <div class="bg-light p-3 rounded mb-4 shadow-sm border">
                <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-search mr-1"></i> فلترة وتصفية السجلات</h6>
                <div class="row">
                    <div class="col-md-5 form-group">
                        <label>بحث باسم الموظف</label>
                        <select name="employee_id_search" id="employee_id_search" class="form-control select2">
                            <option value="">كل الموظفين</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5 form-group">
                        <label>بحث بالشهر المالي</label>
                        <select name="finance_monthly_calendar_id_search" id="finance_monthly_calendar_id_search" class="form-control select2">
                            <option value="">كل الشهور المالية</option>
                            @foreach ($financeMonthlyCalendars as $cal)
                                <option value="{{ $cal->id }}">
                                    سنة {{ $cal->finance_yr }} - شهر {{ optional($cal->month)->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 form-group align-self-end text-left">
                        <button type="button" id="reset_search_btn" class="btn btn-secondary btn-block shadow-sm">
                            <i class="fas fa-sync-alt mr-1"></i> إعادة ضبط
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search Results -->
            <div id="ajax_responce_search">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>كود الموظف</th>
                                <th>اسم الموظف</th>
                                <th>الشهر المالي</th>
                                <th>نوع المنحة</th>
                                <th>المبلغ</th>
                                <th>تاريخ الصرف</th>
                                <th>سجل بواسطة</th>
                                <th>تاريخ التسجيل</th>
                                <th>ملاحظات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($directGrants as $grant)
                                <tr>
                                    <td>{{ $grant->employee->employee_code }}</td>
                                    <td class="font-weight-bold text-primary">{{ $grant->employee->name }}</td>
                                    <td>
                                        سنة {{ $grant->financeMonthlyCalendar->finance_yr }} -
                                        {{ optional($grant->financeMonthlyCalendar->month)->name }}
                                    </td>
                                    <td><span class="badge badge-info py-2 px-3">{{ $grant->grantType->name }}</span></td>
                                    <td class="font-weight-bold text-success">{{ number_format($grant->amount, 2) }} ج.م</td>
                                    <td>{{ $grant->payment_date }}</td>
                                    <td>{{ $grant->addedBy->name }}</td>
                                    <td>{{ $grant->created_at->format('Y-m-d') }}</td>
                                    <td class="small">{{ $grant->notes ?: '---' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('admin.direct-grants.edit', $grant->id) }}"
                                               class="btn btn-sm btn-info mr-1" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.direct-grants.destroy', $grant->id) }}" method="POST"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المنحة المباشرة؟');" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-circle mr-1"></i> لا توجد سجلات منح مباشرة مسجلة حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $directGrants->links() }}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            function performSearch() {
                var employee_id = $('#employee_id_search').val();
                var calendar_id = $('#finance_monthly_calendar_id_search').val();

                $.ajax({
                    url: "{{ route('admin.direct-grants.ajax-search') }}",
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        employee_id_search: employee_id,
                        finance_monthly_calendar_id_search: calendar_id
                    },
                    success: function(data) {
                        $('#ajax_responce_search').html(data);
                    },
                    error: function() {
                        alert('عفواً، حدث خطأ أثناء تحميل نتائج البحث.');
                    }
                });
            }

            $(document).on('change', '#employee_id_search, #finance_monthly_calendar_id_search', function() {
                performSearch();
            });

            $('#reset_search_btn').on('click', function() {
                $('#employee_id_search').val('').trigger('change');
                $('#finance_monthly_calendar_id_search').val('').trigger('change');
            });
        });
    </script>
@endsection

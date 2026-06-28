@extends('admin.layouts.admin')

@section('title', 'رصيد إجازات الموظف بالتفصيل')
@section('contentHeader')
    <i class="fas fa-calendar-alt"></i>
    رصيد إجازات الموظف بالتفصيل
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-employees-vacations-balances.index') }}">أرصدة إجازات الموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل رصيد الإجازات')

@section('content')
    <div class="container-fluid">
        <!-- Employee Info Header Card -->
        <div class="card card-outline card-info shadow mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8 text-left">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            <i class="fas fa-user-tie text-info ml-2"></i>
                            الموظف: <span class="text-primary">{{ $employee->name }}</span> (كود:
                            {{ $employee->employee_code ?? '---' }})
                        </h5>
                        <p class="mb-0 text-muted mt-1" style="font-size: 0.9rem;">
                            <strong>الفرع:</strong> {{ optional($employee->branch)->name ?? '---' }} |
                            <strong>القسم:</strong> {{ optional($employee->department)->name ?? '---' }} |
                            <strong>الوظيفة:</strong> {{ optional($employee->job)->name ?? '---' }} |
                            <strong>تاريخ التعيين:</strong> {{ $employee->hire_date ?? '---' }} |
                            <strong>الحالة الوظيفية:</strong>
                            @if ($employee->employment_status == 1)
                                <span class="badge badge-success px-2 py-0">نشط</span>
                            @else
                                <span class="badge badge-danger px-2 py-0">غير نشط</span>
                            @endif |
                            <strong>تفعيل رصيد الإجازات:</strong>
                            @if ($employee->active_for_vacation == 1)
                                <span class="badge badge-success px-2 py-0">مفعل</span>
                            @else
                                <span class="badge badge-danger px-2 py-0">غير مفعل</span>
                            @endif
                        </p>
                        @if (!empty($current_opened_month))
                            <div class="mt-2">
                                <span class="badge badge-info px-3 py-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-calendar-check"></i> الشهر المالي المفتوح حالياً:
                                    <strong>{{ $current_opened_month->month->name }}
                                        ({{ $current_opened_month->finance_yr }})</strong>
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 text-left">
                        <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-eye"></i> عرض الموظف
                        </a>
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning btn-sm ml-2">
                            <i class="fas fa-edit"></i> تعديل الموظف
                        </a>
                        <a href="{{ route('admin.main-employees-vacations-balances.index') }}"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left ml-1"></i> العودة للقائمة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vacations Balance Details Card -->
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title text-primary font-weight-bold">
                    <i class="fas fa-list ml-2"></i>
                    سجل أرصدة إجازات الموظف التفصيلي
                </h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold mb-1"><i class="fas fa-filter text-primary ml-1"></i> تصفية بالسنة المالية:</label>
                            <select name="financial_year_search" id="financial_year_search" class="form-control select2">
                                <option value="">عرض كل السنوات المالية</option>
                                @foreach ($financialYears as $year)
                                    <option value="{{ $year->finance_yr }}">
                                        {{ $year->finance_yr_desc }} ({{ $year->finance_yr }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="ajax_responce_search">
                    @include('admin.mainEmployeesVacationsBalances.show-table', ['vacationBalances' => $vacationBalances])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#financial_year_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var financial_year = $('#financial_year_search').val();

                $.ajax({
                    url: '{{ route('admin.main-employees-vacations-balances.ajax-search-show', $employee->id) }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        financial_year: financial_year
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        // Silent fallback or standard notification
                    }
                });
            }
        });
    </script>
@endsection

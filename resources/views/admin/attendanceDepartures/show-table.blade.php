<div class="container-fluid">

    <!-- Monthly Calendar Info Header -->
    <div class="card card-outline card-info shadow mb-4">
        <div class="card-body py-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-fingerprint text-info mr-2"></i>
                        بصمة الموظفين لشهر: <span class="text-primary">{{ $financeMonthlyCalendar->month->name }}</span>
                        للسنة المالية <span class="text-primary">{{ $financeMonthlyCalendar->finance_yr }}</span>
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.attendanceDepartures.index') }}"
                        class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> العودة لقائمة الشهور
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Form Card -->
    <div class="card card-primary card-outline shadow mb-4">
        <div class="card-header">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-search mr-2"></i>
                بحث في موظفي الشهر المالي
            </h3>
            @if ($financeMonthlyCalendar->status == 1)
                <a href="{{ route('admin.attendanceDepartures.upload-excel', $financeMonthlyCalendar->id) }}"
                class="btn btn-success">
                <i class="fas fa-upload"></i>
                رفع ملف إكسل البصمة
            </a>
        @endif
        </div>
        <div class="card-body">
            <form action="{{ route('admin.attendanceDepartures.print-search') }}" method="POST" target="_blank" id="searchForm">
                @csrf
                <input type="hidden" name="finance_monthly_calendar_id_search" value="{{ $financeMonthlyCalendar->id }}">
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>اسم الموظف</label>
                            <select name="employee_id_search" id="employee_id_search" class="form-control select2">
                                <option value="">كل الموظفين</option>
                                @foreach ($employees_search_list as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الفرع</label>
                            <select name="branch_id_search" id="branch_id_search" class="form-control select2">
                                <option value="">كل الفروع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الإدارة</label>
                            <select name="department_id_search" id="department_id_search" class="form-control select2">
                                <option value="">كل الإدارات</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>الوظيفة</label>
                            <select name="job_id_search" id="job_id_search" class="form-control select2">
                                <option value="">كل الوظائف</option>
                                @foreach ($jobs as $job)
                                    <option value="{{ $job->id }}">{{ $job->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-success shadow-sm" id="print_button">
                            <i class="fas fa-print mr-1"></i> طباعة البحث الحالي
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-users mr-2"></i>
                قائمة موظفي الشركة
            </h3>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div id="ajax_responce_search">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>كود الموظف</th>
                                <th>الصورة</th>
                                <th>اسم الموظف</th>
                                <th>الفرع</th>
                                <th>الادارة</th>
                                <th>الوظيفة</th>
                                <th>حالة الموظف</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $employmentStatusLabels = [
                                    1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle mr-1"></i> نشط</span>',
                                    0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle mr-1"></i> غير نشط</span>',
                                ];
                            @endphp
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                                    <td>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                            {{ $employee->employee_code ?? '---' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                                                class="img-thumbnail" style="width: 45px; height: 45px; border-radius: 50%;">
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td class="font-weight-bold text-right">
                                        {{ $employee->name }}
                                    </td>
                                    <td>{{ optional($employee->branch)->name ?? '---' }}</td>
                                    <td>{{ optional($employee->department)->name ?? '---' }}</td>
                                    <td>{{ optional($employee->job)->name ?? '---' }}</td>
                                    <td>{!! $employmentStatusLabels[$employee->employment_status] ?? '---' !!}</td>
                                    <td>
                                        <!-- Actions -->
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="#" class="btn btn-sm btn-info mr-1" title="تفاصيل البصمة">
                                                <i class="fas fa-eye mr-1"></i> التفاصيل
                                            </a>
                                            <form action="{{ route('admin.attendanceDepartures.print-search') }}" method="POST" target="_blank" class="m-0">
                                                @csrf
                                                <input type="hidden" name="finance_monthly_calendar_id_search" value="{{ $financeMonthlyCalendar->id }}">
                                                <input type="hidden" name="employee_id_search" value="{{ $employee->id }}">
                                                <button type="submit" class="btn btn-sm btn-success mr-1" title="طباعة بصمة الموظف">
                                                    <i class="fas fa-print mr-1"></i> طباعة
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="alert alert-warning mb-0 text-center py-3">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                            لا توجد سجلات موظفين حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $employees->links() }}
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

            // Search triggering elements
            $(document).on('change', '#employee_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#branch_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#department_id_search', function() {
                ajax_search();
            });
            $(document).on('change', '#job_id_search', function() {
                ajax_search();
            });

            function ajax_search() {
                var employee_id_search = $('#employee_id_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();

                $.ajax({
                    url: '{{ route('admin.attendanceDepartures.ajax-search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        finance_monthly_calendar_id: {{ $financeMonthlyCalendar->id }},
                        employee_id_search: employee_id_search,
                        branch_id_search: branch_id_search,
                        department_id_search: department_id_search,
                        job_id_search: job_id_search
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        console.log('Error during search');
                    }
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var employee_id_search = $('#employee_id_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
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
                        branch_id_search: branch_id_search,
                        department_id_search: department_id_search,
                        job_id_search: job_id_search
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

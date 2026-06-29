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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#uploadExcelModal">
                    <i class="fas fa-upload"></i>
                    رفع ملف إكسل البصمة
                </button>
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
            <style>
                @media (min-width: 768px) {
                    .border-md-right-custom {
                        border-right: 1px solid #bbf7d0 !important;
                    }
                }
            </style>
            @if (!empty($lastUploadedFingerPrint) && !empty($latestActionRecord))
                <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-right: 5px solid #22c55e !important; border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            <!-- Column 1: Header/Icon -->
                            <div class="col-md-4 d-flex align-items-center mb-3 mb-md-0">
                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; min-width: 50px;">
                                    <i class="fas fa-fingerprint text-success fa-2x"></i>
                                </div>
                                <div class="mx-3 text-right">
                                    <h6 class="font-weight-bold mb-0 text-success" style="font-size: 1.1rem;">إحصائيات البصمة الحالية</h6>
                                    <span class="text-muted small">آخر تحديثات حركات البصمة المرفوعة</span>
                                </div>
                            </div>
                            
                            <!-- Column 2: Last Excel Upload -->
                            <div class="col-md-4 mb-3 mb-md-0 text-right border-md-right-custom">
                                <div class="pr-md-3">
                                    <span class="d-block text-muted small font-weight-bold">تاريخ آخر رفع لملف الإكسل</span>
                                    <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                        <i class="far fa-calendar-alt text-success mr-1"></i>
                                        {{ $lastUploadedFingerPrint->created_at->format('Y-m-d h:i A') }}
                                    </span>
                                    <span class="d-block text-muted small mt-1">
                                        <i class="far fa-clock mr-1"></i>
                                        منذ {{ $lastUploadedFingerPrint->created_at->diffForHumans() }}
                                    </span>
                                    @if (!empty($lastUploadedFingerPrint->addedBy))
                                        <span class="d-block text-muted small mt-1">
                                            <i class="fas fa-user-edit mr-1"></i>
                                            بواسطة: <strong>{{ $lastUploadedFingerPrint->addedBy->name }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Column 3: Latest Fingerprint Action -->
                            <div class="col-md-4 text-right border-md-right-custom">
                                <div class="pr-md-3">
                                    <span class="d-block text-muted small font-weight-bold">تاريخ أحدث بصمة مسجلة بالملف</span>
                                    <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                        <i class="fas fa-user-clock text-success mr-1"></i>
                                        {{ \Carbon\Carbon::parse($latestActionRecord->dateTimeAction)->format('Y-m-d h:i A') }}
                                    </span>
                                    <span class="d-block text-muted small mt-1">
                                        <i class="far fa-clock mr-1"></i>
                                        منذ {{ \Carbon\Carbon::parse($latestActionRecord->dateTimeAction)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 p-3" style="border-radius: 8px; border-right: 5px solid #dc3545 !important; background-color: #fff5f5; color: #b91c1c; border-left: none; border-top: none; border-bottom: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle fa-lg mr-2"></i>
                        <div class="text-right mx-2">
                            <strong>لم يتم رفع أي بصمات لهذا الشهر بعد.</strong> @if($financeMonthlyCalendar->status == 1 ) يمكنك الضغط على زر "رفع ملف إكسل البصمة" بالأعلى لاستيراد حركات الحضور والانصراف. @endif
                        </div>
                    </div>
                    <button type="button" class="close text-danger text-right" data-dismiss="alert" style="opacity: 0.8;">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h5>
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        يوجد أخطاء في البيانات
                    </h5>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                        <div class="d-inline-flex align-items-center justify-content-center">
                                            @if ($employee->image)
                                                <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                                                    class="rounded-circle shadow-sm" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid #e9ecef;"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                                                <div class="align-items-center justify-content-center bg-light text-muted rounded-circle shadow-sm" style="width: 40px; height: 40px; border: 2px solid #e9ecef; display: none;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                            @else
                                                <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle shadow-sm" style="width: 40px; height: 40px; border: 2px solid #e9ecef;">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                            @endif
                                        </div>
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
                                            <a href="{{ route('admin.attendanceDepartures.finger-print-details', ['id' => $employee->id, 'finance_monthly_calendar_id' => $financeMonthlyCalendar->id]) }}" class="btn btn-sm btn-info mr-1" title="تفاصيل البصمة">
                                                <i class="fas fa-eye mr-1"></i> التفاصيل
                                            </a>
                                            <form action="{{ route('admin.attendanceDepartures.print-search') }}" method="POST" target="_blank" class="m-0">
                                                @csrf
                                                <input type="hidden" name="finance_monthly_calendar_id_search" value="{{ $financeMonthlyCalendar->id }}">
                                                <input type="hidden" name="employee_id_search" value="{{ $employee->id }}">
                                              
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

<!-- Upload Excel Modal -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" role="dialog" aria-labelledby="uploadExcelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="uploadExcelModalLabel">
                    <i class="fas fa-file-excel mr-2"></i>
                    رفع ملف إكسل البصمة لشهر: {{ $financeMonthlyCalendar->month->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.attendanceDepartures.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="finance_monthly_calendar_id" value="{{ $financeMonthlyCalendar->id }}">
                <div class="modal-body">
                    <div class="form-group text-right">
                        <label for="excel_file" class="font-weight-bold">اختر ملف الإكسل (.xlsx, .xls, .csv)</label>
                        <input type="file" name="excel_file" id="excel_file" class="form-control-file border p-2 rounded w-100 {{ $errors->has('excel_file') ? 'is-invalid' : '' }}" accept=".xlsx,.xls" required>
                        @include('admin.errors.errors', ['value' => 'excel_file'])
                    </div>

                    <div class="alert alert-warning text-left mt-3">
                        <h6 class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-1"></i> تنبيه هام:</h6>
                        سيتم إهمال أي حركة بصمة تقع خارج فترة سحب البصمة من الشهر المالي المفتوح.
                        <br>
                        فترة سحب البصمة المعتمدة: من <strong>{{ $financeMonthlyCalendar->start_date_for_calculation }}</strong> إلى <strong>{{ $financeMonthlyCalendar->end_date_for_calculation }}</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success shadow-sm">
                        <i class="fas fa-upload mr-1"></i> رفع وحفظ البيانات
                    </button>
                    <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">إلغاء</button>
                </div>
            </form>
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

            @if ($errors->any())
                $('#uploadExcelModal').modal('show');
            @endif

            // Prevent double submit on Excel upload form
            $('#uploadExcelModal form').on('submit', function() {
                var btn = $(this).find('button[type="submit"]');
                var cancelBtn = $(this).find('button[data-dismiss="modal"]');
                btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> جاري رفع وحفظ البيانات...');
                setTimeout(function() {
                    btn.prop('disabled', true);
                    cancelBtn.prop('disabled', true);
                }, 50);
                return true;
            });

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

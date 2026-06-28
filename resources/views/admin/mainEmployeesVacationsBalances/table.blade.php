<div class="container-fluid">

    @php
        $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
        $employmentStatusLabels = [
            1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> نشط</span>',
            0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle"></i> غير نشط</span>',
        ];
    @endphp

    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد الموظفين</span>
                    <span class="info-box-number">{{ $employees->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-user-check"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الموظفين النشطين</span>
                    <span class="info-box-number">{{ $employees->where('employment_status', 1)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-user-times"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الموظفين غير النشطين</span>
                    <span class="info-box-number">{{ $employees->where('employment_status', 0)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-venus-mars"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الذكور / الإناث</span>
                    <span class="info-box-number">{{ $employees->where('gender', 1)->count() }} /
                        {{ $employees->where('gender', 2)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول أرصدة إجازات الموظفين
            </h3>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h5>
                        <i class="fas fa-exclamation-circle"></i>
                        يوجد أخطاء في البيانات
                    </h5>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
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

            <div class="row">
                <div class="col-md-12 mb-3">
                    <p class="btn btn-primary btn-sm shadow-sm mb-0">
                        <i class="fas fa-search"></i> تصفية البحث
                    </p>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>
                            <input checked type="radio" name="code_type" value="employee_code"> كود الموظف
                            <input type="radio" name="code_type" value="fingerprint_code"> كود البصمة
                        </label>
                        <input type="text" name="code_search" value="" id="code_search"
                            class="form-control"
                            placeholder="أدخل الكود">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>اسم الموظف</label>
                        <input type="text" name="name_search" value="" id="name_search"
                            class="form-control"
                            placeholder="أدخل اسم الموظف">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الفرع</label>
                        <select name="branch_id_search" id="branch_id_search"
                            class="form-control select2">
                            <option value="">اختر الفرع</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الإدارة</label>
                        <select name="department_id_search" id="department_id_search"
                            class="form-control select2">
                            <option value="">اختر الإدارة</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الوظيفة</label>
                        <select name="job_id_search" id="job_id_search"
                            class="form-control select2">
                            <option value="">اختر الوظيفة</option>
                            @foreach ($jobs as $job)
                                <option value="{{ $job->id }}">
                                    {{ $job->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>حالة التوظيف</label>
                        <select name="employment_status_search" id="employment_status_search"
                            class="form-control select2">
                            <option value="">اختر حالة التوظيف</option>
                            <option value="1">نشط</option>
                            <option value="0">غير نشط</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>حاله تفعيل رصيد الاجازات</label>
                        <select name="active_for_vacation_search" id="active_for_vacation_search"
                            class="form-control select2">
                            <option value="">اختر حالة تفعيل رصيد الاجازات</option>
                            <option value="1">مفعل</option>
                            <option value="0">غير مفعل</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الجنس</label>
                        <select name="gender_search" id="gender_search"
                            class="form-control select2">
                            <option value="">اختر الجنس</option>
                            <option value="1">ذكر</option>
                            <option value="2">أنثى</option>
                            <option value="3">آخر</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="ajax_responce_search" class="mt-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>كود الموظف</th>
                                <th>كود البصمة</th>
                                <th>الإسم</th>
                                <th>القسم</th>
                                <th>الوظيفة</th>
                                <th>الفرع</th>
                                <th>الصورة</th>
                                <th>الحالة الوظيفية</th>
                                <th>تفعيل رصيد الإجازات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->id }}</td>
                                    <td>{{ $employee->employee_code ?? '---' }}</td>
                                    <td>{{ $employee->fingerprint_code ?? '---' }}</td>
                                    <td class="text-right">{{ $employee->name ?? '---' }}</td>
                                    <td>{{ optional($employee->department)->name ?? '---' }}</td>
                                    <td>{{ optional($employee->job)->name ?? '---' }}</td>
                                    <td>{{ optional($employee->branch)->name ?? '---' }}</td>
                                    <td>
                                        @if ($employee->image)
                                            <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                                                class="img-thumbnail" style="max-width: 90px; max-height: 90px;">
                                        @else
                                            <span>---</span>
                                        @endif
                                    </td>
                                    <td>{!! $employmentStatusLabels[$employee->employment_status] ?? '---' !!}</td>
                                    <td>
                                        @if ($employee->active_for_vacation == 1)
                                            <span class="badge badge-success px-3 py-2">مفعل</span>
                                        @else
                                            <span class="badge badge-danger px-3 py-2">غير مفعل</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-1">
                                            <a href="{{ route('admin.main-employees-vacations-balances.show', $employee->id) }}"
                                                class="btn btn-sm btn-info m-1" title="عرض تفاصيل رصيد الإجازات">
                                                <i class="fas fa-eye"></i> عرض
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-circle"></i>
                                            لا توجد بيانات موظفين حالياً
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
            $('.select2').each(function() {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }
                var $modal = $select.closest('.modal');
                if ($modal.length) {
                    $select.select2({
                        theme: 'bootstrap4',
                        dropdownParent: $modal
                    });
                } else {
                    $select.select2({
                        theme: 'bootstrap4'
                    });
                }
            });
        }

        $(document).ready(function() {
            initSelect2();

            $(document).on('input', '#code_search', function() {
                ajax_search();
            });
            $(document).on('input', '#name_search', function() {
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
            $(document).on('change', '#employment_status_search', function() {
                ajax_search();
            });
            $(document).on('change', '#active_for_vacation_search', function() {
                ajax_search();
            });
            $(document).on('change', '#gender_search', function() {
                ajax_search();
            });
            $('input[type=radio][name=code_type]').change(function() {
                ajax_search();
            });

            function ajax_search() {
                var code_search = $('#code_search').val();
                var name_search = $('#name_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var active_for_vacation_search = $('#active_for_vacation_search').val();
                var gender_search = $('#gender_search').val();
                var code_type = $('input[type=radio][name=code_type]:checked').val();

                $.ajax({
                    url: '{{ route('admin.main-employees-vacations-balances.search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        fingerprint_code: code_search,
                        employee_code: code_search,
                        name: name_search,
                        branch_id: branch_id_search,
                        department_id: department_id_search,
                        job_id: job_id_search,
                        employment_status: employment_status_search,
                        active_for_vacation: active_for_vacation_search,
                        gender: gender_search,
                        code_type: code_type
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        // Error handling silently or if required
                    }
                });
            }

            $(document).on('click', '#ajax-pagination a', function(e) {
                e.preventDefault();
                var code_search = $('#code_search').val();
                var name_search = $('#name_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var active_for_vacation_search = $('#active_for_vacation_search').val();
                var gender_search = $('#gender_search').val();
                var code_type = $('input[type=radio][name=code_type]:checked').val();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        fingerprint_code: code_search,
                        employee_code: code_search,
                        name: name_search,
                        branch_id: branch_id_search,
                        department_id: department_id_search,
                        job_id: job_id_search,
                        employment_status: employment_status_search,
                        active_for_vacation: active_for_vacation_search,
                        gender: gender_search,
                        code_type: code_type
                    },
                    success: function(response) {
                        $('#ajax_responce_search').html(response);
                    },
                    error: function(xhr) {
                        alert('حدث خطأ');
                    }
                });
            });
        });
    </script>
@endsection

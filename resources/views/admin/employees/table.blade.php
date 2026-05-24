<div class="container-fluid">

    @php
    $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
    $employmentStatusLabels = [1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i>
        نشط</span>', 0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle"></i> غير
        نشط</span>'];
    $yesNoLabels = [0 => '<span class="badge badge-secondary">لا</span>', 1 => '<span
        class="badge badge-success">نعم</span>'];
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
                    <span class="info-box-number">{{ $employees->where('gender', 1)->count() }} / {{
                        $employees->where('gender', 2)->count() }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول الموظفين
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة موظف
                </a>
            </div>

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
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="close text-white text-right" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-times-circle"></i>
                {{ session('error') }}
                <button type="button" class="close text-white text-right" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <p class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-search"></i>
                    </p>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>

                            <input checked type="radio" name="code_type" value="employee_code"> كود الموظف
                            <input type="radio" name="code_type" value="fingerprint_code"> كود البصمة

                        </label>
                        <input type="text" name="code_search" value="" id="code_search"
                            class="form-control {{ $errors->has('code_search') ? 'is-invalid' : '' }}"
                            placeholder="أدخل الكود">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>اسم الموظف</label>
                        <input type="text" name="name_search" value="" id="name_search"
                            class="form-control {{ $errors->has('name_search') ? 'is-invalid' : '' }}"
                            placeholder="أدخل اسم الموظف">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>الفرع</label>
                        <select name="branch_id_search" id="branch_id_search"
                            class="form-control select2 {{ $errors->has('branch_id_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر الفرع</option>
                            @foreach($branches as $branch)
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
                            class="form-control select2 {{ $errors->has('department_id_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر الإدارة</option>
                            @foreach($departments as $department)
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
                            class="form-control select2 {{ $errors->has('job_id_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر الوظيفة</option>
                            @foreach($jobs as $job)
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
                            class="form-control select2 {{ $errors->has('employment_status_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر حالة التوظيف</option>
                            <option value="1">
                                نشط
                            </option>
                            <option value="0">
                                غير نشط
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>طريقة الدفع</label>
                        <select name="payment_method" id="payment_method_search"
                            class="form-control select2 {{ $errors->has('payment_method_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر طريقة الدفع</option>
                            <option value="1">
                                نقداً
                            </option>
                            <option value="2">
                                تحويل بنكي
                            </option>
                            <option value="3">
                                شيك
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>الجنس</label>
                        <select name="gender_search" id="gender_search"
                            class="form-control select2 {{ $errors->has('gender_search') ? 'is-invalid' : '' }}">
                            <option value="">اختر الجنس</option>
                            <option value="1">
                                ذكر
                            </option>
                            <option value="2">
                                أنثى
                            </option>
                            <option value="3">
                                آخر
                            </option>
                        </select>
                        @include('admin.errors.errors', ['value' => 'gender'])
                    </div>
                </div>
            </div>
            <div id="ajax_responce_search">
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
                                    @if($employee->image)
                                    <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                                        class="img-thumbnail" style="max-width: 90px; max-height: 90px;">
                                    @else
                                    <span>---</span>
                                    @endif
                                </td>
                                <td>{!! $employmentStatusLabels[$employee->employment_status] ?? '---' !!}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-info m-1 show_employee_details"
                                            data-id="{{ $employee->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                            class="btn btn-sm btn-warning m-1" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.employees.destroy', $employee->id) }}"
                                            method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1"
                                                title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12">
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

<!-- Employee Details Modal -->
<div class="modal fade" id="employeeDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content shadow">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle"></i>
                    بيانات الموظف
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="employee_details_modal_body" style="max-height: 80vh; overflow-y: auto;">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">جاري التحميل...</span>
                    </div>
                    <p class="mt-2">جاري تحميل البيانات...</p>
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
        $(document).on('click', '.show_employee_details', function() {
            var id = $(this).data('id');
            $('#employee_details_modal_body').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">جاري التحميل...</span>
                    </div>
                    <p class="mt-2">جاري تحميل البيانات...</p>
                </div>
            `);

            $.ajax({
                url: `/admin/employees/${id}/details`,
                type: 'GET',
                dataType: 'html',
                cache: false,
                success: function(response) {
                    $('#employee_details_modal_body').html(response);
                    $('#employeeDetailsModal').modal('show');
                },
                error: function(xhr) {
                    $('#employee_details_modal_body').html(`
                        <div class="alert alert-danger text-center m-3">
                            <i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i>
                            <strong>حدث خطأ!</strong><br>
                            ${xhr.responseText || 'لم يتم تحميل البيانات'}
                        </div>
                    `);
                    $('#employeeDetailsModal').modal('show');
                }
            });
        });

            $(document).on('input', '#code_search', function () {
                ajax_search();
            })
            $(document).on('input', '#name_search', function () {
                ajax_search();
            })
            $(document).on('change', '#branch_id_search', function () {
                ajax_search();
            })
            $(document).on('change', '#department_id_search', function () {
                ajax_search();
            })
            $(document).on('change', '#job_id_search', function () {
                ajax_search();
            })
            $(document).on('change', '#employment_status_search', function () {
                ajax_search();
            })
            $(document).on('change', '#payment_method_search', function () {
                ajax_search();
            })
            $(document).on('change', '#gender_search', function () {
                ajax_search();
            })
            $('input[type=radio][name=code_type]').change( function () {
                ajax_search();
            })
            function ajax_search() {
                var code_search = $('#code_search').val();
                var name_search = $('#name_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var payment_method_search = $('#payment_method_search').val();
                var gender_search = $('#gender_search').val();
                var code_type = $('input[type=radio][name=code_type]:checked').val();

                $.ajax({
                    url: '{{ route('admin.employees.search') }}',
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
                        payment_method: payment_method_search,
                        gender: gender_search,
                        code_type: code_type
                    },
                    success: function (employees) {
                        $('#ajax_responce_search').html(employees);
                    },
                    error: function (xhr) {
                        {{--  alert('حدث خطأ');  --}}
                    }
                });
            }

            $(document).on('click', '#ajax-pagination a', function (e) {
                e.preventDefault();
                var code_search = $('#code_search').val();
                var name_search = $('#name_search').val();
                var branch_id_search = $('#branch_id_search').val();
                var department_id_search = $('#department_id_search').val();
                var job_id_search = $('#job_id_search').val();
                var employment_status_search = $('#employment_status_search').val();
                var payment_method_search = $('#payment_method_search').val();
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
                        payment_method: payment_method_search,
                        gender: gender_search,
                        code_type: code_type
                    },
                    success: function (employees) {
                        $('#ajax_responce_search').html(employees);
                    },
                    error: function (xhr) {
                        alert('حدث خطأ');
                    }
                });
            });

    });
</script>
@endsection

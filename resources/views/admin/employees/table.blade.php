<div class="container-fluid">

    @php
    $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
    $employmentStatusLabels = [1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> نشط</span>', 0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle"></i> غير نشط</span>'];
    $yesNoLabels = [0 => '<span class="badge badge-secondary">لا</span>', 1 => '<span class="badge badge-success">نعم</span>'];
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
                    <span class="info-box-number">{{ $employees->total() ?? $employees->count() }}</span>
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
                    <span class="info-box-number">{{ $employees->where('gender', 1)->count() }} / {{ $employees->where('gender', 2)->count() }}</span>
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>كود الموظف</th>
                            <th>كود البصمة</th>
                            <th>الإسم</th>
                            <th>الجنس</th>
                            <th>القسم</th>
                            <th>الوظيفة</th>
                            <th>الهاتف</th>
                            <th>الراتب</th>
                            <th>الحالة الوظيفية</th>
                            <th>تاريخ التعيين</th>
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
                            <td>{{ $genderLabels[$employee->gender] ?? '---' }}</td>
                            <td>{{ optional($employee->department)->name ?? '---' }}</td>
                            <td>{{ optional($employee->job)->name ?? '---' }}</td>
                            <td>{{ $employee->work_telephone ?? $employee->home_telephone ?? '---' }}</td>
                            <td>{{ $employee->salary !== null ? number_format($employee->salary, 2) : '---' }}</td>
                            <td>{!! $employmentStatusLabels[$employee->employment_status] ?? '---' !!}</td>
                            <td>{{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d') : '---' }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    <button type="button" class="btn btn-sm btn-info m-1 show_employee_details" data-id="{{ $employee->id }}" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning m-1" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1" title="حذف">
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
                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
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
<script>
    $(document).ready(function() {
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
    });
</script>
@endsection

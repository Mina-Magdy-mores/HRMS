@php
$genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
$employmentStatusLabels = [1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i>
    نشط</span>', 0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle"></i> غير
    نشط</span>'];
$yesNoLabels = [0 => '<span class="badge badge-secondary">لا</span>', 1 => '<span
    class="badge badge-success">نعم</span>'];
@endphp
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
                    <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف" class="img-thumbnail"
                        style="max-width: 90px; max-height: 90px;">
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
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning m-1"
                            title="تعديل">
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
</div>
{{-- Pagination --}}
<div class="mt-3" id="ajax-pagination">
    {{ $employees->links() }}
</div>

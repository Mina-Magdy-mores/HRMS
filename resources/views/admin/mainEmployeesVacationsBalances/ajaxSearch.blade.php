@php
    $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
    $employmentStatusLabels = [
        1 => '<span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> نشط</span>',
        0 => '<span class="badge badge-danger px-3 py-2"><i class="fas fa-times-circle"></i> غير نشط</span>',
    ];
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
                            <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف" class="img-thumbnail"
                                style="max-width: 90px; max-height: 90px;">
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
<div class="mt-3" id="ajax-pagination">
    {{ $employees->links() }}
</div>

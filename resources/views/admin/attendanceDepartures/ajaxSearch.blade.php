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
                            لا توجد سجلات موظفين مطابقة للبحث حالياً.
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

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
                            <a href="#" class="btn btn-sm btn-info mr-1" title="تفاصيل البصمة">
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

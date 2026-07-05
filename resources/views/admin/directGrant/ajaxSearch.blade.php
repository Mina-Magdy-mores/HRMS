<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th>كود الموظف</th>
                <th>اسم الموظف</th>
                <th>الشهر المالي</th>
                <th>نوع المنحة</th>
                <th>المبلغ</th>
                <th>تاريخ الصرف</th>
                <th>سجل بواسطة</th>
                <th>تاريخ التسجيل</th>
                <th>ملاحظات</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($directGrants as $grant)
                <tr>
                    <td>{{ $grant->employee->employee_code }}</td>
                    <td class="font-weight-bold text-primary">{{ $grant->employee->name }}</td>
                    <td>
                        سنة {{ $grant->financeMonthlyCalendar->finance_yr }} -
                        {{ optional($grant->financeMonthlyCalendar->month)->name }}
                    </td>
                    <td><span class="badge badge-info py-2 px-3">{{ $grant->grantType->name }}</span></td>
                    <td class="font-weight-bold text-success">{{ number_format($grant->amount, 2) }} ج.م</td>
                    <td>{{ $grant->payment_date }}</td>
                    <td>{{ $grant->addedBy->name }}</td>
                    <td>{{ $grant->created_at->format('Y-m-d') }}</td>
                    <td class="small">{{ $grant->notes ?: '---' }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('admin.direct-grants.edit', $grant->id) }}"
                               class="btn btn-sm btn-info mr-1" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.direct-grants.destroy', $grant->id) }}" method="POST"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه المنحة المباشرة؟');" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-circle mr-1"></i> لا توجد سجلات منح مباشرة مسجلة حالياً.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $directGrants->links() }}
</div>

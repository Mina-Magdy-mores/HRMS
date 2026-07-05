<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th>اسم الموظف</th>
                <th>أجر اليوم</th>
                <th>إجمالي الإضافة (+)</th>
                <th>إجمالي الخصم (-)</th>
                <th>صافي التسوية</th>
                <th>تاريخ الإضافة</th>
                <th>بواسطة</th>
                <th>ملاحظات</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mainSalaryEmployeeSettlements as $settlement)
                <tr>
                    <td>{{ $settlement->employee->name }}</td>
                    <td>{{ number_format($settlement->employee_per_day_salary, 2) }} ج.م</td>
                    <td class="text-success font-weight-bold">+{{ number_format($settlement->total_amount_for_addition, 2) }} ج.م</td>
                    <td class="text-danger font-weight-bold">-{{ number_format($settlement->total_amount_for_deduction, 2) }} ج.م</td>
                    <td class="@if($settlement->final_total_amount >= 0) text-primary @else text-danger @endif font-weight-bold">
                        {{ number_format($settlement->final_total_amount, 2) }} ج.م
                    </td>
                    <td>{{ $settlement->created_at->format('Y-m-d') }}</td>
                    <td>{{ $settlement->addedBy->name }}</td>
                    <td>{{ $settlement->notes }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-success show-settlement-details-btn mr-1"
                            data-id="{{ $settlement->id }}" title="عرض التفاصيل الكاملة">
                            <i class="fas fa-eye"></i> تفاصيل
                        </button>
                        @if ($settlement->financeMonthlyCalendar->status == 2)
                            <button type="button" class="btn btn-sm btn-info edit-settlement"
                                data-id="{{ $settlement->id }}">
                                <i class="fas fa-edit"></i> تعديل
                            </button>
                            <button type="button" class="btn btn-sm btn-danger delete-settlement-btn"
                                data-id="{{ $settlement->id }}">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="alert alert-warning mb-0">
                            لا توجد تسويات مسجلة لهذا الشهر تطابق البحث.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div id="ajax-pagination" class="mt-3">
    {{ $mainSalaryEmployeeSettlements->links() }}
</div>

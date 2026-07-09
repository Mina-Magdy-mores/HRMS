<div class="table-responsive">
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th>#</th>
                <th>الشهر والسنة</th>
                <th>السنة المالية</th>
                <th>الرصيد المرحل من الشهر السابق</th>
                <th>رصيد الشهر الحالي</th>
                <th>إجمالي الرصيد المتاح</th>
                <th>الرصيد المستهلك</th>
                <th>صافي الرصيد المتبقي</th>
                <th>حالة الأرشفة</th>
                <th>المضاف بواسطة</th>
                <th>تاريخ الإضافة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vacationBalances as $balance)
                <tr>
                    <td>{{ $balance->id }}</td>
                    <td><span
                            class="badge badge-info px-2 py-1">{{ $balance->year_and_month ?: '---' }}</span>
                    </td>
                    <td>{{ $balance->financial_year ?: '---' }}</td>
                    <td class="text-primary font-weight-bold">
                        {{ number_format($balance->carryover_from_previous_month, 2) }}</td>
                    <td class="text-success font-weight-bold">
                        {{ number_format($balance->current_month_balance, 2) }}</td>
                    <td class="text-info font-weight-bold">
                        {{ number_format($balance->total_available_balance, 2) }}</td>
                    <td class="text-danger font-weight-bold">
                        {{ number_format($balance->spent_balance, 2) }}</td>
                    <td class="text-warning font-weight-bold" style="font-size: 1.1rem;">
                        {{ number_format($balance->remaining_net_balance, 2) }}</td>
                    <td>
                        @if ($balance->is_archived)
                            <span class="badge badge-secondary px-3 py-1">
                                <i class="fas fa-lock"></i> مؤرشف
                            </span>
                            @if ($balance->archived_at)
                                <br><small class="text-muted">في: {{ $balance->archived_at }}</small>
                            @endif
                            @if ($balance->archivedBy)
                                <br><small class="text-muted">بواسطة:
                                    {{ $balance->archivedBy->name }}</small>
                            @endif
                        @else
                            <span class="badge badge-success px-3 py-1">
                                <i class="fas fa-lock-open"></i> نشط
                            </span>
                        @endif
                    </td>
                    <td>{{ optional($balance->addedBy)->name ?? '---' }}</td>
                    <td>{{ $balance->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.main-employees-vacations-balances.edit', $balance->id) }}"
                            class="btn btn-warning btn-xs shadow-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12">
                        <div class="alert alert-warning mb-0 text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            لا توجد سجلات أرصدة إجازات مضافة لهذا الموظف حتى الآن.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

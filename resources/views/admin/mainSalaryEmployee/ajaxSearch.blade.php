                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>كود الموظف</th>
                                <th>الموظف</th>
                                <th>الفرع/القسم</th>
                                <th>الوظيفة/المنصب</th>
                                <th>الراتب الأساسي</th>
                                <th>إجمالي الاستحقاقات</th>
                                <th>إجمالي الاستقطاعات</th>
                                <th>صافي الراتب</th>
                                <th>حالة الراتب</th>
                                <th>حالة الصرف</th>
                                <th>حالة الأرشفة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mainSalaryEmployees as $record)
                                <tr>
                                    <td>{{ $loop->iteration + ($mainSalaryEmployees->currentPage() - 1) * $mainSalaryEmployees->perPage() }}
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                                            {{ $record->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ $record->employee_name }}
                                    </td>
                                    <td class="small text-muted">
                                        {{ $record->branch->name ?? '---' }} <br>
                                        <span class="text-secondary">{{ $record->department->name ?? '---' }}</span>
                                    </td>
                                    <td class="small font-weight-bold">
                                        {{ $record->job->name ?? '---' }}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{ number_format($record->employee_salary, 2) }} ج.م
                                    </td>
                                    <td class="text-success font-weight-bold">
                                        {{ number_format($record->total_benefits, 2) }} ج.م
                                    </td>
                                    <td class="text-danger font-weight-bold">
                                        {{ number_format($record->total_deductions, 2) }} ج.م
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-nowrap text-primary" style="font-size: 15px;">
                                            {{ number_format(abs($record->employee_net_salary), 2) }} ج.م
                                        </div>
                                        @if ($record->employee_net_salary >= 0)
                                            <span class="badge badge-success px-2 py-0" style="font-size: 11px;">
                                                دائن (مستحق له)
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-2 py-0" style="font-size: 11px;">
                                                مدين (مستحق عليه)
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->payment_on_hold == 1)
                                            <span class="badge badge-danger px-2 py-1">
                                                <i class="fas fa-hand-paper mr-1"></i> موقوف
                                            </span>
                                        @else
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-play mr-1"></i> نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->is_disbursed == 1)
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-check-circle mr-1"></i> تم الصرف
                                            </span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">
                                                <i class="fas fa-hourglass-half mr-1"></i> لم يصرف
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->is_archived == 1)
                                            <span class="badge badge-success px-2 py-1">
                                                <i class="fas fa-archive mr-1"></i> مؤرشف
                                            </span>
                                        @else
                                            <span class="badge badge-primary px-2 py-1">
                                                <i class="fas fa-archive mr-1"></i> لم يؤرشف
                                            </span>
                                        @endif
                                    </td>
                                      <td>
                                        <div class="d-flex ">
                                            <button class="btn btn-info btn-sm show-details shadow-sm m-2"
                                            data-id="{{ $record->id }}"
                                            data-employee-id="{{ $record->employee_id }}"
                                            title="تفاصيل الراتب">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if ($record->is_archived == 0 && $financeMonthlyCalendar->status == 1)
                                            @if ($record->payment_on_hold == 0)
                                                <button class="btn btn-warning btn-sm toggle-payment-status m-2 shadow-sm"
                                                    data-id="{{ $record->id }}"
                                                    title="إيقاف صرف راتب الموظف">
                                                    <i class="fas fa-pause mr-1"></i>
                                                </button>
                                                 <button
                                                        class="btn btn-primary btn-sm openArchiveModal m-2 shadow-sm"
                                                        data-id="{{ $record->id }}"
                                                        data-employee-id="{{ $record->employee_id }}"
                                                        data-finance-monthly-calendar-id="{{ $record->finance_monthly_calendar_id }}"
                                                        title="أرشفة سجل الراتب">
                                                        <i class="fas fa-lock mr-1"></i>
                                                    </button>
                                            @else
                                                <button class="btn btn-success btn-sm toggle-payment-status m-2 shadow-sm"
                                                    data-id="{{ $record->id }}"
                                                    title="تفعيل صرف راتب الموظف">
                                                    <i class="fas fa-play mr-1"></i>
                                                </button>
                                            @endif
                                        @endif
                                        @if ($record->is_archived == 0)
                                        <button class="btn btn-danger btn-sm deleteMainSalaryRecord m-2" data-id="{{ $record->id }}"
                                            data-employee-id="{{ $record->employee_id }}"
                                            data-finance-monthly-calendar-id="{{ $record->finance_monthly_calendar_id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="alert alert-warning mb-0 text-center py-3">
                                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                                            لا توجد سجلات رواتب للموظفين في هذا الشهر المالي حالياً.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3" id="ajax-pagination">
                    {{ $mainSalaryEmployees->links() }}
                </div>
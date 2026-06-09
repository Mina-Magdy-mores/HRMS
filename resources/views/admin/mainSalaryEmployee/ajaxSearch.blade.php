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
                                    <td class="text-primary font-weight-bold" style="font-size: 15px;">
                                        {{ number_format($record->employee_net_salary, 2) }} ج.م
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
                                            data-employee-name="{{ $record->employee_name }}"
                                            data-employee-code="{{ $record->employee->employee_code ?? '---' }}"
                                            data-employee-salary="{{ $record->employee_salary ?? 0.0 }}"
                                            data-motivation-amount="{{ $record->motivation_amount ?? 0.0 }}"
                                            data-fixed-allowance="{{ $record->fixed_allowance ?? 0.0 }}"
                                            data-employee-total-allowance="{{ $record->employee_total_allowance ?? 0.0 }}"
                                            data-employee-total-bonus="{{ $record->employee_total_bonus ?? 0.0 }}"
                                            data-employee-additions-payment-total="{{ $record->employee_additions_payment_total ?? 0.0 }}"
                                            data-total-benefits="{{ $record->total_benefits ?? 0.0 }}"
                                            data-social-insurance-amount="{{ $record->social_insurance_amount ?? 0.0 }}"
                                            data-medical-insurance-amount="{{ $record->medical_insurance_amount ?? 0.0 }}"
                                            data-employee-deductions-payment-total="{{ $record->employee_deductions_payment_total ?? 0.0 }}"
                                            data-employee-absences-payment-total="{{ $record->employee_absences_payment_total ?? 0.0 }}"
                                            data-employee-total-deduction-type="{{ $record->employee_total_deduction_type ?? 0.0 }}"
                                            data-monthly-loan-amount="{{ $record->monthly_loan_amount ?? 0.0 }}"
                                            data-permanent-loan-amount="{{ $record->permanent_loan_amount ?? 0.0 }}"
                                            data-total-deductions="{{ $record->total_deductions ?? 0.0 }}"
                                            data-employee-net-salary="{{ $record->employee_net_salary ?? 0.0 }}"
                                            data-employee-rollover-amount="{{ $record->employee_rollover_amount ?? 0.0 }}"
                                            data-is-disbursed="{{ $record->is_disbursed ?? 0 }}"
                                            data-payment-on-hold="{{ $record->payment_on_hold ?? 0 }}"
                                            data-additions-days-counter="{{ $record->employee_additions_days_counter ?? 0 }}"
                                            data-absences-days-counter="{{ $record->employee_absences_days_counter ?? 0 }}"
                                            data-deductions-days-counter="{{ $record->employee_deductions_days_counter ?? 0 }}"
                                            data-penalty-days-counter="{{ $record->employee_total_penalty_days ?? 0 }}"
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
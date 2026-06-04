<div class="row">
    <div class="col-md-12">
        <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">
            <i class="fas fa-info-circle mr-1"></i> تفاصيل السلفة المستديمة للموظف: {{ $mainSalaryEmployeePLoans->employee->name ?? '---' }}
        </h5>
        
        <table class="table table-bordered table-striped text-center align-middle">
            <tr>
                <th class="bg-light" style="width: 25%;">الراتب الأساسي وقت السلفة</th>
                <td>{{ number_format($mainSalaryEmployeePLoans->employee_basic_salary, 2) }} ج.م</td>
                <th class="bg-light" style="width: 25%;">إجمالي مبلغ السلفة</th>
                <td><strong class="text-success">{{ number_format($mainSalaryEmployeePLoans->amount, 2) }} ج.م</strong></td>
            </tr>
            <tr>
                <th class="bg-light">عدد الأقساط (الأشهر)</th>
                <td>{{ $mainSalaryEmployeePLoans->number_of_installment_months }} شهر</td>
                <th class="bg-light">قسط الشهر</th>
                <td><strong class="text-primary">{{ number_format($mainSalaryEmployeePLoans->installment_amount_monthly, 2) }} ج.م</strong></td>
            </tr>
            <tr>
                <th class="bg-light">المدفوع</th>
                <td><strong class="text-info">{{ number_format($mainSalaryEmployeePLoans->paid_amount, 2) }} ج.م</strong></td>
                <th class="bg-light">المتبقي</th>
                <td><strong class="text-danger">{{ number_format($mainSalaryEmployeePLoans->remaining_amount, 2) }} ج.م</strong></td>
            </tr>
            <tr>
                <th class="bg-light">تاريخ البدء</th>
                <td>{{ $mainSalaryEmployeePLoans->next_installment_date }}</td>
                <th class="bg-light">حالة الصرف</th>
                <td>
                    @if ($mainSalaryEmployeePLoans->is_disbursed == 1)
                        <span class="badge badge-success px-2 py-1">تم الصرف</span>
                    @else
                        <span class="badge badge-warning px-2 py-1 text-white">قيد الانتظار</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th class="bg-light">ملاحظات</th>
                <td colspan="3" class="text-right">{{ $mainSalaryEmployeePLoans->notes ?? 'لا يوجد' }}</td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-12 mt-4">
        <h5 class="text-primary font-weight-bold mb-3 border-bottom pb-2">
            <i class="fas fa-list-ol mr-1"></i> جدول الأقساط التفصيلي
        </h5>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover text-center align-middle">
                <thead class="bg-primary text-white">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>الشهر المستحق</th>
                        <th>مبلغ القسط</th>
                        <th>الحالة</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mainSalaryEmployeePLoans->mainSalaryEmployeePLoanInstallments as $installment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="font-weight-bold text-dark">
                                    {{ $installment->next_installment_year_and_month }}
                                </span>
                            </td>
                            <td>
                                <span class="text-primary font-weight-bold">
                                    {{ number_format($installment->installment_amount_monthly, 2) }} ج.م
                                </span>
                            </td>
                            <td>
                                @if ($installment->installment_status == '1')
                                    <span class="badge badge-success px-2 py-1">
                                        <i class="fas fa-check-circle mr-1"></i> تم الخصم من الراتب
                                    </span>
                                @elseif ($installment->installment_status == '2')
                                    <span class="badge badge-info px-2 py-1">
                                        <i class="fas fa-money-bill-wave mr-1"></i> تم الدفع نقداً
                                    </span>
                                @else
                                    <span class="badge badge-warning px-2 py-1 text-white">
                                        <i class="fas fa-clock mr-1"></i> قيد الانتظار
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small font-italic text-secondary">
                                    {{ $installment->notes ?? '---' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="alert alert-warning mb-0 py-2">
                                    لا توجد أقساط مسجلة لهذه السلفة.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>  
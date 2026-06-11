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
                <th class="bg-light">حالة الأرشفة</th>
                <td>
                    @if ($mainSalaryEmployeePLoans->is_archived == 1)
                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-archive mr-1"></i> مؤرشف</span>
                    @else
                        <span class="badge badge-success px-2 py-1"><i class="fas fa-folder-open mr-1"></i> نشط</span>
                    @endif
                </td>
                <th class="bg-light">تفاصيل الأرشفة</th>
                <td>
                    @if ($mainSalaryEmployeePLoans->is_archived == 1)
                        <span class="small font-weight-bold text-muted">
                            بواسطة: {{ $mainSalaryEmployeePLoans->archivedBy->name ?? '---' }}
                            <br>
                            بتاريخ: {{ $mainSalaryEmployeePLoans->archived_at }}
                        </span>
                    @else
                        <span class="text-secondary">---</span>
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
                        <th>حالة الأرشفة</th>
                        <th>طريقة السداد / الدفع</th>
                        <th>ملاحظات</th>
                        <th>تفاصيل الإدخال</th>
                        <th>الإجراءات</th>
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
                                @if ($installment->is_archived == 1)
                                    <span class="badge badge-danger px-2 py-1 mb-1">
                                        <i class="fas fa-archive mr-1"></i> مؤرشف
                                    </span>
                                    @if ($installment->archivedBy)
                                        <small class="d-block text-muted">
                                            بواسطة: {{ $installment->archivedBy->name }}
                                        </small>
                                    @endif
                                    @if ($installment->archived_at)
                                        <small class="d-block text-muted font-italic">
                                            {{ date('Y-m-d h:i A', strtotime($installment->archived_at)) }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge badge-light border border-success text-success px-2 py-1">
                                        <i class="fas fa-folder-open mr-1"></i> نشط
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($installment->installment_status == '1')
                                    <span class="small font-weight-bold text-dark">
                                        خصم من راتب شهر: {{ $installment->mainSalaryEmployee->year_and_month ?? '---' }}
                                    </span>
                                @elseif ($installment->installment_status == '2')
                                    <span class="small font-weight-bold text-info">
                                        سداد نقدي مباشر
                                    </span>
                                @else
                                    <span class="small text-secondary">
                                        لم يسدد بعد
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small font-italic text-secondary">
                                    {{ $installment->notes ?? '---' }}
                                </span>
                            </td>
                            <td class="text-right" style="min-width: 150px;">
                                <small class="d-block text-muted">
                                    <strong>أضيف بواسطة:</strong> {{ $installment->addedBy->name ?? '---' }}
                                </small>
                                <small class="d-block text-muted">
                                    <strong>بتاريخ:</strong> {{ $installment->created_at ? $installment->created_at->format('Y-m-d h:i A') : '---' }}
                                </small>
                                @if ($installment->updatedBy)
                                    <hr class="my-1">
                                    <small class="d-block text-muted">
                                        <strong>تعديل بواسطة:</strong> {{ $installment->updatedBy->name }}
                                    </small>
                                    <small class="d-block text-muted">
                                        <strong>بتاريخ:</strong> {{ $installment->updated_at ? $installment->updated_at->format('Y-m-d h:i A') : '---' }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if ($installment->can_pay_cash)
                                    <button type="button" class="btn btn-sm btn-success pay_installment_cash_btn shadow-sm" 
                                        data-id="{{ $installment->id }}" 
                                        title="دفع نقداً">
                                        <i class="fas fa-money-bill-wave"></i> دفع كاش
                                    </button>
                                @else
                                    <span class="text-muted small">---</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
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
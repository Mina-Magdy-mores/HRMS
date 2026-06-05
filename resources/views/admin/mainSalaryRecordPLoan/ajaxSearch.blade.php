  <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center align-middle">

                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>الموظف</th>
                                <th>الراتب الأساسي</th>
                                <th>إجمالى مبلغ السلفة</th>
                                <th>عدد الأقساط</th>
                                <th>القسط الشهري</th>
                                <th>تاريخ البدء</th>
                                <th>المدفوع</th>
                                <th>المتبقي</th>
                                <th>حالة الصرف</th>
                                <th>حالة الأرشفة</th>
                                <th>تفاصيل الإدخال</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($mainSalaryEmployeePLoans as $loan)
                                <tr>
                                    <td>
                                        {{ $loop->iteration + ($mainSalaryEmployeePLoans->currentPage() - 1) * $mainSalaryEmployeePLoans->perPage() }}
                                    </td>

                                    <td>
                                        <span class="font-weight-bold text-dark d-block">
                                            {{ $loan->employee->name ?? '---' }}
                                        </span>
                                        <span class="badge badge-secondary font-weight-normal px-2 py-1 mt-1">
                                            كود: {{ $loan->employee->employee_code ?? '---' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-secondary font-weight-bold">
                                            {{ number_format($loan->employee_basic_salary, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-success font-weight-bold">
                                            {{ number_format($loan->amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge badge-dark px-3 py-1 font-weight-normal">
                                            {{ $loan->number_of_installment_months }} شهر
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-primary font-weight-bold">
                                            {{ number_format($loan->installment_amount_monthly, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-dark font-weight-bold">
                                            {{ $loan->next_installment_date }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-info font-weight-bold">
                                            {{ number_format($loan->paid_amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        <span class="text-danger font-weight-bold">
                                            {{ number_format($loan->remaining_amount, 2) }} ج.م
                                        </span>
                                    </td>

                                    <td>
                                        @if ($loan->is_disbursed == 1)
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-check-circle mr-1"></i> تم الصرف
                                            </span>
                                            @if ($loan->disbursedBy)
                                                <small class="d-block text-muted mt-1 font-italic"
                                                    title="{{ $loan->disbursed_at }}">
                                                    بواسطة: {{ $loan->disbursedBy->name }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning px-3 py-2 text-white">
                                                <i class="fas fa-hourglass-half mr-1"></i> قيد الانتظار
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($loan->is_archived == 1)
                                            <span class="badge badge-danger px-3 py-2">
                                                <i class="fas fa-archive mr-1"></i> مؤرشف
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-light border border-success text-success px-3 py-2">
                                                <i class="fas fa-folder-open mr-1"></i> نشط
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-right" style="min-width: 180px;">
                                        <small class="d-block text-muted">
                                            <strong>أضيف بواسطة:</strong> {{ optional($loan->addedBy)->name ?? '---' }}
                                        </small>
                                        <small class="d-block text-muted">
                                            <strong>بتاريخ:</strong>
                                            {{ $loan->created_at ? $loan->created_at->format('Y-m-d h:i A') : '---' }}
                                        </small>
                                        @if ($loan->updatedBy)
                                            <hr class="my-1">
                                            <small class="d-block text-muted">
                                                <strong>تعديل بواسطة:</strong> {{ $loan->updatedBy->name }}
                                            </small>
                                            <small class="d-block text-muted">
                                                <strong>بتاريخ:</strong>
                                                {{ $loan->updated_at ? $loan->updated_at->format('Y-m-d h:i A') : '---' }}
                                            </small>
                                        @endif
                                    </td>

                                    <td>
                                         <div class="d-flex justify-content-center align-items-center gap-1">
                                            <button class="btn btn-sm btn-info m-1 show_employee_loan_details_btn"
                                                title="عرض الأقساط والتفاصيل" data-id="{{ $loan->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning m-1 edit_employee_loan_btn" title="تعديل"
                                                data-id="{{ $loan->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger m-1 delete_employee_loan_btn"
                                                title="حذف" data-id="{{ $loan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                           @if ($loan->is_disbursed == 0)
                                                <button type="button" class="btn btn-success btn-sm shadow-sm m-1"
                                                    id="disburseBtn" data-id="{{ $loan->id }}" title="صرف السلفة">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13">
                                        <div class="alert alert-warning mb-0 py-3">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            لا توجد بيانات سلف مستديمة (قروض) حالياً
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3" id="ajax-pagination">
                    {{ $mainSalaryEmployeePLoans->links() }}
                </div>
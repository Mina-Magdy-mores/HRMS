<div class="p-4">
    <!-- Header Brief Info -->
    <div class="row bg-light rounded p-3 mb-4 border align-items-center justify-content-end text-center">
        <div class="col-md-3 border-right">
            <span class="text-muted d-block small">الموظف</span>
            <strong class="text-primary font-weight-bold" style="font-size: 1.1rem;">{{ $record->employee_name }}</strong>
        </div>
        <div class="col-md-2 border-right text-center">
            <span class="text-muted d-block small">كود الموظف</span>
            <strong class="text-secondary">{{ $employee->employee_code ?? '---' }}</strong>
        </div>
        <div class="col-md-3 border-right text-center">
            <span class="text-muted d-block small">الشهر المالي</span>
            <strong class="text-secondary">{{ $financeMonthlyCalendar->month->name }} (السنة:
                {{ $financeMonthlyCalendar->finance_yr }})</strong>
        </div>
        <div class="col-md-2 border-right text-center">
            <span class="text-muted d-block small">طريقة الدفع</span>
            @if ($record->payment_method == 1)
                <span class="badge badge-info px-2 py-1"><i class="fas fa-university mr-1"></i> بنكي</span>
            @else
                <span class="badge badge-secondary px-2 py-1"><i class="fas fa-wallet mr-1"></i> نقدي</span>
            @endif
        </div>
        <div class="col-md-2 text-center">
            <span class="text-muted d-block small">أجر اليوم المالي</span>
            <strong class="text-dark">{{ number_format($record->employee_per_day_salary, 2) }} ج.م</strong>
        </div>
    </div>

    <!-- Finalization Warning Alert -->
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4 p-3">
        <div>
            <h6 class="font-weight-bold text-dark mb-1 text-left">تنبيه هام جداً قبل الأرشفة:</h6>
            <p class="text-muted small mb-0 text-left">
                أرشفة وتأكيد الراتب تعني تجميد هذا السجل المالي نهائياً للشهر المحدد. لن تتمكن من تعديل البدلات،
                الجزاءات، الغياب، أو الإضافات الخاصة بهذا الموظف في هذا الشهر بعد المتابعة.
            </p>
        </div>
    </div>

    <div class="row">
        <!-- Benefits Section -->
        <div class="col-md-6 pr-md-3">
            <div class="card card-outline card-success shadow-sm border">
                <div class="card-header bg-success-light w-100">
                    <h6 class="text-success font-weight-bold mb-0 text-center">
                        <i class="fas fa-plus-circle mr-1"></i> الاستحقاقات (+)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0 text-center">
                        <tbody>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">الراتب الأساسي</td>
                                <td class="font-weight-bold text-dark">{{ number_format($record->employee_salary, 2) }}
                                    ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">الحوافز التشجيعية</td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($record->motivation_amount, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">البدلات الثابتة</td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($record->fixed_allowance, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">البدلات المتغيرة</td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($record->employee_total_allowance, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">المكافآت والجوائز</td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($record->employee_total_bonus, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">أيام العمل الإضافي (<span
                                        class="badge badge-success px-1">{{ $record->employee_additions_days_counter * 1 }}</span>
                                    أيام)</td>
                                <td class="font-weight-bold text-success">
                                    {{ number_format($record->employee_additions_payment_total, 2) }} ج.م</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-success-light font-weight-bold">
                                <td class="text-left pr-3 text-success">إجمالي الاستحقاقات</td>
                                <td class="text-success text-center font-weight-bolder" style="font-size: 1.05rem;">
                                    {{ number_format($record->total_benefits, 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Deductions Section -->
        <div class="col-md-6 pl-md-3">
            <div class="card card-outline card-danger shadow-sm border">
                <div class="card-header bg-danger-light">
                    <h6 class="text-danger font-weight-bold mb-0 text-center">
                        <i class="fas fa-minus-circle mr-1"></i> الاستقطاعات والخصومات (-)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-striped mb-0 text-center">
                        <tbody>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">تأمينات اجتماعية</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->social_insurance_amount, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">تأمين طبي للموظف</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->medical_insurance_amount, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">أيام الغياب (<span
                                        class="badge badge-danger px-1">{{ $record->employee_absences_days_counter * 1 }}</span>
                                    أيام)</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->employee_absences_payment_total, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">الجزاءات العامة والخصومات
                                    (<span
                                        class="badge badge-danger px-1">{{ $record->employee_deductions_days_counter * 1 }}</span>
                                    أيام)</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->employee_deductions_payment_total, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">الخصومات المالية (عقوبات
                                    إدارية)</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->employee_total_deduction_type, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">سلف وقروض شهرية</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->monthly_loan_amount, 2) }} ج.م</td>
                            </tr>
                            <tr>
                                <td class="text-left pr-3 font-weight-normal text-muted">أقساط سلف مستديمة</td>
                                <td class="font-weight-bold text-danger">
                                    {{ number_format($record->permanent_loan_amount, 2) }} ج.م</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-danger-light font-weight-bold">
                                <td class="text-left pr-3 text-danger">إجمالي الاستقطاعات</td>
                                <td class="text-danger text-center font-weight-bolder" style="font-size: 1.05rem;">
                                    {{ number_format($record->total_deductions, 2) }} ج.م</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary & Net Salary Box -->
    @php
        $netSalary = $record->employee_net_salary;
        $absNetSalary = abs($netSalary);
    @endphp

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="bg-gradient-light border rounded p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="text-left">
                        <span class="text-dark font-weight-bold d-block mb-1" style="font-size: 0.95rem;">صافي الراتب
                            النهائي المستحق بعد الترحيل:</span>
                        <div class="d-flex align-items-center justify-content-end">
                            @if ($netSalary >= 0)
                                <span class="badge badge-success px-3 py-2 mr-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-arrow-down mr-1"></i> دائن (مستحق له)
                                </span>
                            @else
                                <span class="badge badge-danger px-3 py-2 mr-2" style="font-size: 0.9rem;">
                                    <i class="fas fa-arrow-up mr-1"></i> مدين (مستحق عليه)
                                </span>
                            @endif
                            <h3 class="text-primary font-weight-bolder mb-0" style="font-size: 2rem;">
                                {{ number_format($absNetSalary, 2) }}
                                <span class="small" style="font-size: 1.1rem;">ج.م</span>
                            </h3>
                        </div>
                    </div>
                    <div>
                        <span class="text-muted d-block small font-weight-bold">المرتجع/المرحل من الشهر الماضي:</span>
                        <strong class="text-secondary font-weight-bold"
                            style="font-size: 1.2rem;">{{ number_format($record->employee_rollover_amount, 2) }}</strong>
                        <span class="text-muted small">ج.م</span>
                    </div>
                </div>

                <!-- Settlement/Disbursement Section -->
                <div class="row mt-4 border-top pt-4">
                    <div class="col-md-6 text-left d-flex flex-column justify-content-center border-right">
                        <span class="text-muted d-block small font-weight-bold">المبلغ المتبقي للترحيل للشهر
                            القادم:</span>
                        <h3 class="text-primary font-weight-bolder mb-0 mt-2" id="remaining_rollover_display"
                            style="font-size: 2rem;">
                            @if ($netSalary >= 0)
                                0.00
                            @else
                                {{ number_format($absNetSalary, 2) }}
                            @endif
                            <span class="small" style="font-size: 1.1rem;">ج.م</span>
                        </h3>
                        <span id="remaining_status_display"
                            class="badge mt-2 align-self-start px-2 py-1 {{ $netSalary >= 0 ? 'badge-secondary' : 'badge-danger' }}">
                            @if ($netSalary >= 0)
                                لا يوجد ترحيل (تم الصرف بالكامل)
                            @else
                                مدين (مستحق عليه مرحل للشهر القادم)
                            @endif
                        </span>
                    </div>
                    <div class="col-md-6 text-left">
                        <div class="form-group mb-0">
                            @if ($netSalary >= 0)
                                <label class="font-weight-bold text-success mb-2" for="disbursed_amount">
                                    <i class="fas fa-money-bill-wave mr-1"></i> المبلغ المراد صرفه للموظف حالياً:
                                </label>
                                <input type="number" step="0.01" min="0" max="{{ $absNetSalary }}"
                                    class="form-control form-control-lg font-weight-bold text-center"
                                    id="disbursed_amount" name="disbursed_amount" value="{{ $absNetSalary }}">
                                <small class="text-muted d-block mt-1">الحد الأقصى للصرف هو الصافي المستحق له:
                                    {{ number_format($absNetSalary, 2) }} ج.م</small>
                            @else
                                <label class="font-weight-bold text-danger mb-2" for="disbursed_amount">
                                    <i class="fas fa-hand-holding-usd mr-1"></i> المبلغ المراد تحصيله من الموظف حالياً:
                                </label>
                                <input type="number" step="0.01" min="0" max="{{ $absNetSalary }}"
                                    class="form-control form-control-lg font-weight-bold text-center"
                                    id="disbursed_amount" name="disbursed_amount" value="0">
                                <small class="text-muted d-block mt-1">يمكنك تحصيل جزء أو كامل المبلغ المستحق عليه:
                                    {{ number_format($absNetSalary, 2) }} ج.م</small>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-light border-top p-3 d-flex justify-content-between">
   
    <button type="button" class="btn btn-primary px-4 shadow-sm" id="submit_archive_salary_btn"
        data-id="{{ $record->id }}">
        <i class="fas fa-check-circle mr-1"></i> تأكيد وأرشفة الراتب الآن
    </button>
     <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">
        <i class="fas fa-times-circle mr-1"></i> إلغاء وإغلاق
    </button>
</div>

<script>
    $(document).ready(function() {
        var netSalary = {{ $netSalary }};
        var absNetSalary = {{ $absNetSalary }};

        $('#disbursed_amount').on('input', function() {
            var val = parseFloat($(this).val()) || 0;
            if (val < 0) {
                val = 0;
                $(this).val(0);
            }
            if (val > absNetSalary) {
                val = absNetSalary;
                $(this).val(absNetSalary);
            }

            var remaining = absNetSalary - val;
            $('#remaining_rollover_display').html(remaining.toFixed(2) +
                ' <span class="small" style="font-size: 1.1rem;">ج.م</span>');

            if (netSalary >= 0) {
                if (remaining > 0) {
                    $('#remaining_status_display')
                        .removeClass('badge-secondary badge-danger')
                        .addClass('badge-success')
                        .text('دائن (مستحق له مرحل للشهر القادم)');
                } else {
                    $('#remaining_status_display')
                        .removeClass('badge-success badge-danger')
                        .addClass('badge-secondary')
                        .text('لا يوجد ترحيل (تم الصرف بالكامل)');
                }
            } else {
                if (remaining > 0) {
                    $('#remaining_status_display')
                        .removeClass('badge-secondary badge-success')
                        .addClass('badge-danger')
                        .text('مدين (مستحق عليه مرحل للشهر القادم)');
                } else {
                    $('#remaining_status_display')
                        .removeClass('badge-danger badge-success')
                        .addClass('badge-secondary')
                        .text('لا يوجد ترحيل (تم السداد بالكامل)');
                }
            }
        });
    });
</script>

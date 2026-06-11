<div class="container-fluid p-3">

    @php
        $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
        $maritalStatusLabels = [1 => 'أعزب', 2 => 'متزوج', 3 => 'مخطوب', 4 => 'أرمل', 5 => 'مطلق'];
        $militaryStatusLabels = [1 => 'نشط', 2 => 'مستقيل', 3 => 'مُنهي الخدمة'];
        $graduationGradeLabels = [1 => 'ممتاز', 2 => 'جيد جدا', 3 => 'جيد', 4 => 'مقبول', 5 => 'ضعيف'];
        $motivationTypeLabels = [0 => 'لا يوجد', 1 => 'ثابت', 2 => 'متغير'];
        $paymentMethodLabels = [1 => 'كاش', 2 => 'تحويل بنكي', 3 => 'شيك'];
        $yesNoLabels = [0 => 'لا', 1 => 'نعم'];
        $employmentStatusLabels = [1 => 'نشط', 0 => 'غير نشط'];
    @endphp
    <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning mb-3" title="تعديل">
        <i class="fas fa-edit"></i>
        تعديل
    </a>
    <!-- البيانات الأساسية -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header bg-primary text-white text-end">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-user-circle"></i>
                البيانات الأساسية
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>الإسم:</strong>
                    <p class="mb-0 mt-1">{{ $employee->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>كود الموظف:</strong>
                    <p class="mb-0 mt-1">{{ $employee->employee_code ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>كود البصمة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->fingerprint_code ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تاريخ الميلاد:</strong>
                    <p class="mb-0 mt-1">{{ $employee->birth_date ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الجنسية:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->nationality)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الجنس:</strong>
                    <p class="mb-0 mt-1">{{ $genderLabels[$employee->gender] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الديانة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->religion)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>اللغة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->language)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الحالة الاجتماعية:</strong>
                    <p class="mb-0 mt-1">{{ $maritalStatusLabels[$employee->marital_status] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>عدد الأطفال:</strong>
                    <p class="mb-0 mt-1">{{ $employee->children_count ?? 0 }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>فصيلة الدم:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->bloodGroup)->name ?? '---' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>العنوان الحالى:</strong>
                    <p class="mb-0 mt-1">{{ $employee->stable_address ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم البطاقة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->nationality_number ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>صلاحية رقم البطاقة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->nationality_expiry_date ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>جهة إصدار رقم البطاقة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->nationality_place_of_issue ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بيانات الاتصال والعنوان -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-phone-alt"></i>
                بيانات الاتصال والعنوان
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>البريد الإلكتروني:</strong>
                    <p class="mb-0 mt-1">{{ $employee->email ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>هاتف المنزل:</strong>
                    <p class="mb-0 mt-1">{{ $employee->home_telephone ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>هاتف العمل:</strong>
                    <p class="mb-0 mt-1">{{ $employee->work_telephone ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>جهة اتصال طارئ:</strong>
                    <p class="mb-0 mt-1">{{ $employee->urgent_contact_details ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الدولة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->country)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>المحافظة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->governorate)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>المدينة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->city)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>العنوان السكني:</strong>
                    <p class="mb-0 mt-1">{{ $employee->home_address ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- المؤهلات -->
    <div class="card card-outline card-warning shadow-sm mb-3">
        <div class="card-header bg-warning text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-graduation-cap"></i>
                المؤهلات
            </h5>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <strong>المؤهل:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->qualification)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>سنة التخرج:</strong>
                    <p class="mb-0 mt-1">{{ $employee->qualification_year ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تقدير التخرج:</strong>
                    <p class="mb-0 mt-1">{{ $graduationGradeLabels[$employee->graduation_grade] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تخصص التخرج:</strong>
                    <p class="mb-0 mt-1">{{ $employee->graduation_specialization ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بيانات العمل والراتب -->
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-briefcase"></i>
                بيانات العمل والراتب
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>تاريخ التعيين:</strong>
                    <p class="mb-0 mt-1">{{ $employee->hire_date ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>القسم:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->department)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الوظيفة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->job)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الفرع:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->branch)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>حالة التوظيف:</strong>
                    <p class="mb-0 mt-1">{{ $employmentStatusLabels[$employee->employment_status] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الراتب:</strong>
                    <div class="d-flex align-items-center mt-1">
                        <p class="mb-0" style="margin-left: 8px; margin-right: 8px;">{{ number_format($employee->salary, 2) ?? '---' }}</p>
                        <button type="button" class="btn btn-xs btn-outline-info" data-toggle="modal" data-target="#salaryArchiveModal" title="أرشيف الرواتب">
                            <i class="fas fa-history"></i> أرشيف الرواتب
                        </button>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الدفع اليومي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->payment_per_day ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>نوع الوردية الثابتة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->fixed_shift] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>عدد ساعات العمل اليومية:</strong>
                    <p class="mb-0 mt-1">{{ $employee->daily_work_hours ?? '---' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>نوع الوردية:</strong>
                    <p class="mb-0 mt-1">
                        @if ($employee->shiftType)

                            @if ($employee->shiftType->type == 1)
                                شفت نهاري
                            @elseif($employee->shiftType->type == 2)
                                شفت ليلي
                            @elseif($employee->shiftType->type == 3)
                                شفت كامل اليوم
                            @endif
                            -
                            @php
                                $start_time = new DateTime($employee->shiftType->start_time);
                                $start_time = $start_time->format('h:i A');
                            @endphp

                            {{ $start_time }}
                            الى
                            @php
                                $end_time = new DateTime($employee->shiftType->end_time);
                                $end_time = $end_time->format('h:i A');
                            @endphp

                            {{ $end_time }}
                            -عدد الساعات
                            {{ $employee->shiftType->total_hours }}
                        @else
                            '---'
                        @endif
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- الحالة العسكرية -->
    @if ($employee->gender == 1)
        <div class="card card-outline card-secondary shadow-sm mb-3">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0" style="float:none;">
                    <i class="fas fa-shield-alt"></i>
                    الحالة العسكرية
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <strong>الحالة العسكرية:</strong>
                        <p class="mb-0 mt-1">{{ $militaryStatusLabels[$employee->military_status] ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>تاريخ بداية الخدمة:</strong>
                        <p class="mb-0 mt-1">{{ $employee->military_start_date ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>تاريخ نهاية الخدمة:</strong>
                        <p class="mb-0 mt-1">{{ $employee->military_end_date ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>السلاح:</strong>
                        <p class="mb-0 mt-1">{{ $employee->military_weapon ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>تاريخ الإعفاء:</strong>
                        <p class="mb-0 mt-1">{{ $employee->military_exemption_date ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>سبب الإعفاء:</strong>
                        <p class="mb-0 mt-1">{{ $employee->military_exemption_reason ?? '---' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong>سبب التأجيل:</strong>
                        <p class="mb-0 mt-1">{{ $employee->postponement_reason ?? '---' }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- معلومات التأمينات -->
    <div class="card card-outline card-danger shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-file-invoice"></i>
                التأمينات
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>تأمين اجتماعي:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_social_insurance] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>قيمة التأمين الاجتماعي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->social_insurance_amount ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم التأمين الاجتماعي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->social_insurance_number ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تأمين طبي:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_medical_insurance] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>قيمة التأمين الطبي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->medical_insurance_amount ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم التأمين الطبي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->medical_insurance_number ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الدفع والحافز -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-money-bill-wave"></i>
                الدفع والحافز
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>نوع الحافز:</strong>
                    <p class="mb-0 mt-1">{{ $motivationTypeLabels[$employee->motivation_type] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>قيمة الحافز:</strong>
                    <p class="mb-0 mt-1">{{ $employee->motivation_amount ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>طريقة الدفع:</strong>
                    <p class="mb-0 mt-1">{{ $paymentMethodLabels[$employee->payment_method] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم الحساب البنكي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->bank_account_number ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تفعيل الحضور والغياب:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_attendance] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>صيغة الإجازة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->vacation_formula] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تفعيل للإجازة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->active_for_vacation] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>بيانات حساسة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_sensitive_data] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>بدل ثابت:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->fixed_allowance] ?? '---' }}</p>
                </div>
                @if ($employee->fixed_allowance == 1)
                    <div class="col-md-3 mb-3">
                        <strong>إضافة وعرض البدلات الثابتة:</strong>
                        <p class="m-1">
                            <button id="load_add_allowance_modal" class="btn btn-sm btn-success mb-2"
                                data-toggle="modal" data-target="#addAllowanceModal">
                                <i class="fas fa-file-upload"></i> إضافة وعرض البدلات الثابتة
                            </button>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- معلومات الاستقالة والترك -->
    <div class="card card-outline card-outline shadow-sm mb-3">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-sign-out-alt"></i>
                معلومات الاستقالة والترك
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>نوع الاستقالة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->resignation)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تاريخ الاستقالة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->resignation_date ?? '---' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>سبب الاستقالة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->resignation_reason ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات رخصة القيادة والجواز -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-id-card"></i>
                رخصة القيادة والجواز
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>رخصة قيادة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->driving_license] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>نوع الرخصة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->drivingLicenseType)->type ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم الرخصة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->driving_license_number ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم الجواز:</strong>
                    <p class="mb-0 mt-1">{{ $employee->passport_number ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>صلاحية الجواز:</strong>
                    <p class="mb-0 mt-1">{{ $employee->passport_expiry_date ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>جهة إصدار الجواز:</strong>
                    <p class="mb-0 mt-1">{{ $employee->passport_place_of_issue ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>اسم الكفيل:</strong>
                    <p class="mb-0 mt-1">{{ $employee->sponsor_name ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات المستخدم -->
    <div class="card card-outline card-light shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-users-cog"></i>
                معلومات الإضافة والتحديث
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>أضيف بواسطة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->addedBy)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>آخر تحديث بواسطة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->updatedBy)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>تاريخ الإضافة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->created_at ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>آخر تحديث:</strong>
                    <p class="mb-0 mt-1">{{ $employee->updated_at ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>كود الشركة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->company_id ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- البيانات الصحية والعائلية -->
    <div class="card card-outline card-warning shadow-sm mb-3">
        <div class="card-header bg-warning text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-heartbeat"></i>
                البيانات الصحية والعائلية
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>إعاقة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_disability] ?? '---' }}</p>
                </div>
                <div class="col-md-9 mb-3">
                    <strong>وصف الإعاقة:</strong>
                    <p class="mb-0 mt-1">{{ $employee->disability_description ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>وجود أقارب في الشركة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_relative] ?? '---' }}</p>
                </div>
                <div class="col-md-9 mb-3">
                    <strong>وصف الأقارب:</strong>
                    <p class="mb-0 mt-1">{{ $employee->relative_description ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- الملفات والملاحظات -->
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0" style="float:none;">
                <i class="fas fa-file-alt"></i>
                الملفات والملاحظات
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>الصورة:</strong>
                    <p class="m-1">
                        @if ($employee->image)
                            <a href="{{ asset('storage/' . $employee->image) }}" target="_blank"
                                class="btn btn-sm btn-primary mb-2">
                                <i class="fas fa-image"></i> عرض الصورة
                            </a>
                            <a href="{{ route('admin.employees.download', ['id' => $employee->id, 'type' => 'image']) }}"
                                target="_blank" class="btn btn-sm btn-secondary  mb-2">
                                <i class="fas fa-image"></i> تحميل الصورة
                            </a>
                        @else
                            ---
                        @endif
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>السيرة الذاتية:</strong>
                    <p class="m-1">
                        @if ($employee->cv)
                            <a href="{{ asset('storage/' . $employee->cv) }}" target="_blank"
                                class="btn btn-sm btn-primary mb-2">
                                <i class="fas fa-file-pdf"></i> عرض السيرة الذاتية
                            </a>
                            <a href="{{ route('admin.employees.download', ['id' => $employee->id, 'type' => 'cv']) }}"
                                target="_blank" class="btn btn-sm btn-secondary mb-2">
                                <i class="fas fa-file-pdf"></i> تحميل السيرة الذاتية
                            </a>
                        @else
                            ---
                        @endif
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>إضافة وعرض الملفات المرفقة:</strong>
                    <p class="m-1">
                        <button id="load_add_file_modal" class="btn btn-sm btn-success mb-2" data-toggle="modal"
                            data-target="#addFileModal">
                            <i class="fas fa-file-upload"></i> إضافة و عرض الملفات المرفقة
                        </button>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>الملاحظات:</strong>
                    <p class="mb-0 mt-1">{{ $employee->notes ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>


    <!-- File Modal -->
    <div class="modal    fade " id="addFileModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow">

                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-alt"></i>
                        إضافة مرفقات للموظف
                    </h5>

                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- BODY = EMPTY -->
                <div class="modal-body" id="add_file_modal_body">
                    <div class="card">
                        <div class="card-header">

                            <form action="{{ route('admin.employees.add-file', $employee->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>اسم الملف</label><span class="text-danger h4">*</span>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                            placeholder="أدخل اسم الملف">
                                        @include('admin.errors.errors', ['value' => 'name'])
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>رابط الملف</label>
                                        <input type="file" name="file"
                                            class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}"
                                            accept="*">
                                        @include('admin.errors.errors', ['value' => 'file'])
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success shadow px-4">
                                        <i class="fas fa-save"></i>
                                        حفظ البيانات
                                    </button>
                                    <button type="button" data-dismiss="modal" class="btn btn-danger shadow px-4">
                                        <i class="fas fa-times-circle"></i>
                                        إلغاء
                                    </button>
                                </div>


                            </form>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center align-middle">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>اسم الملف</th>
                                            <th>عرض الملف</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($employee->files as $file)
                                            <tr>
                                                <td>{{ $file->id }}</td>
                                                <td>{{ $file->name ?? '---' }}</td>
                                                <td>
                                                    @if ($file->path)
                                                        <img src="{{ asset('storage/' . $file->path) }}"
                                                            alt="صورة الملف" class="img-thumbnail"
                                                            style="max-width: 90px; max-height: 90px;">
                                                        <small class="form-text text-muted">
                                                            الملف الحالي: <a
                                                                href="{{ asset('storage/' . $employee->image) }}"
                                                                target="_blank">عرض الملف</a> - <a
                                                                href="{{ route('admin.employees.download', ['id' => $employee->id, 'type' => 'file', 'file' => $file->id]) }}"
                                                                target="_blank">تحميل الملف</a> -
                                                            <a href="{{ route('admin.employees.delete', ['id' => $file->id, 'employee_id' => $employee->id]) }}"
                                                                class="text-danger"
                                                                onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الملف؟')">
                                                                حذف الملف
                                                            </a>
                                                        </small>
                                                    @else
                                                        <span>---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12">
                                                    <div class="alert alert-warning mb-0">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                        لا توجد بيانات ملفات حالياً
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Allowance Modal -->
    <div class="modal    fade " id="addAllowanceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow">

                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-alt"></i>
                        إضافة بدلات ثابتة للموظف
                    </h5>

                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- BODY = EMPTY -->
                <div class="modal-body" id="add_allowance_modal_body">
                    <div class="card">
                        <div class="card-header">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>نوع البدل</label><span class="text-danger h4">*</span>
                                    <select name="allowance_type_id" id="allowance_type_id" class="form-control select2">
                                        <option value="">اختر نوع البدل</option>
                                        @foreach ($allowances as $allowance)
                                            <option value="{{ $allowance->id }}">{{ $allowance->name }}</option>
                                        @endforeach
                                    </select>
                                    @include('admin.errors.errors', ['value' => 'type'])
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>قيمة البدل</label>
                                    <input type="number" name="amount" step="0.01" id="allowance_amount"
                                        class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}"
                                        placeholder="أدخل قيمة البدل">
                                    @include('admin.errors.errors', ['value' => 'amount'])
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" id="editing_allowance_id" value="">
                                <button type="submit" id="add_allowance_btn" class="btn btn-success shadow px-4">
                                    <i class="fas fa-save"></i>
                                    <span id="submit_btn_text">حفظ البيانات</span>
                                </button>
                                <button type="button" id="cancel_edit_btn" class="btn btn-warning shadow px-4" style="display: none;">
                                    <i class="fas fa-undo"></i>
                                    تراجع
                                </button>
                                <button type="button" data-dismiss="modal" class="btn btn-danger shadow px-4">
                                    <i class="fas fa-times-circle"></i>
                                    إلغاء
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center align-middle">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>نوع البدل</th>
                                            <th>قيمة البدل</th>
                                            <th>تاريخ الاضافة</th>
                                            <th>تاريخ التحديث</th>
                                            <th>الإجراءات</th>



                                        </tr>
                                    </thead>

                                    <tbody id="allowances_table_body">
                                        @include('admin.employees.allowances_rows', ['fixedAllowances' => $employee->employeeFixedAllowances])
                                    </tbody>

                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Salary Archive Modal -->
    <div class="modal fade" id="salaryArchiveModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <!-- Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-history"></i>
                        أرشيف الرواتب القديمة للموظف: {{ $employee->name }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>#</th>
                                    <th>قيمة الراتب القديم</th>
                                    <th>أضيف بواسطة</th>
                                    <th>آخر تحديث بواسطة</th>
                                    <th>تاريخ التعديل (الأرشفة)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employee->employeeSalaryArchives as $archive)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="text-success font-weight-bold">{{ number_format($archive->amount, 2) }}</td>
                                        <td>{{ optional($archive->addedBy)->name ?? '---' }}</td>
                                        <td>{{ optional($archive->updatedBy)->name ?? '---' }}</td>
                                        <td>{{ $archive->created_at->format('Y-m-d h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="alert alert-warning mb-0 text-center">
                                                <i class="fas fa-exclamation-circle"></i>
                                                لا توجد رواتب مؤرشفة مسبقاً لهذا الموظف
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Function to reset edit mode
            function resetAllowanceForm() {
                $('#editing_allowance_id').val('');
                $('#allowance_amount').val('');
                $('#allowance_type_id').val('').trigger('change');
                $('#submit_btn_text').text('حفظ البيانات');
                $('#cancel_edit_btn').hide();
            }

            // Edit Allowance button click
            $(document).off('click', '.edit_allowance_btn');
            $(document).on('click', '.edit_allowance_btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var typeId = $(this).data('type-id');
                var amount = $(this).data('amount');

                $('#editing_allowance_id').val(id);
                $('#allowance_type_id').val(typeId).trigger('change');
                $('#allowance_amount').val(amount);
                $('#submit_btn_text').text('تعديل البيانات');
                $('#cancel_edit_btn').show();
                
                // Focus on the select element
                $('#allowance_type_id').focus();
            });

            // Cancel Edit button click
            $(document).off('click', '#cancel_edit_btn');
            $(document).on('click', '#cancel_edit_btn', function(e) {
                e.preventDefault();
                resetAllowanceForm();
            });

            // Add or Update Allowance
            $(document).off('click', '#add_allowance_btn');
            $(document).on('click', '#add_allowance_btn', function(e) {
                e.preventDefault();
                
                var allowance_id = $("#editing_allowance_id").val();
                var allowance_type_id = $("#allowance_type_id").val();
                var allowance_amount = $("#allowance_amount").val();
                var employee_id = "{{ $employee->id }}";
                
                if (allowance_type_id == '' || allowance_type_id == null) {
                    alert('الرجاء اختيار نوع البدل');
                    return;
                }
                
                if (allowance_amount == '' || allowance_amount <= 0) {
                    alert('الرجاء إدخال قيمة بدل صحيحة أكبر من الصفر');
                    return;
                }

                var isEdit = allowance_id !== '';
                var targetUrl = isEdit ? "{{ route('admin.employees.update-allowance') }}" : "{{ route('admin.employees.add-allowance') }}";
                var ajaxData = {
                    _token: "{{ csrf_token() }}",
                    employee_id: employee_id,
                    allowance_type_id: allowance_type_id,
                    amount: allowance_amount
                };

                if (isEdit) {
                    ajaxData.id = allowance_id;
                }

                $.ajax({
                    url: targetUrl,
                    type: 'POST',
                    dataType: 'json',
                    data: ajaxData,
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#allowances_table_body').html(response.html);
                            resetAllowanceForm();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            alert(response.message);
                        } else {
                            alert(isEdit ? 'حدث خطأ أثناء تعديل البدل' : 'حدث خطأ أثناء إضافة البدل');
                        }
                    }
                });
            });

            // Delete Allowance
            $(document).off('click', '.delete_allowance_btn');
            $(document).on('click', '.delete_allowance_btn', function(e) {
                e.preventDefault();
                
                if (!confirm('هل أنت متأكد من حذف هذا البدل الثابت؟')) {
                    return;
                }

                var allowance_id = $(this).data('id');
                var employee_id = "{{ $employee->id }}";

                $.ajax({
                    url: "{{ route('admin.employees.delete-allowance') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: allowance_id,
                        employee_id: employee_id
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#allowances_table_body').html(response.html);
                            
                            // If the deleted allowance was being edited, reset the form
                            if ($('#editing_allowance_id').val() == allowance_id) {
                                resetAllowanceForm();
                            }
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('حدث خطأ أثناء حذف البدل');
                    }
                });
            });
        });
    </script>

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

    <!-- البيانات الأساسية -->
    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
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
                    <strong>الجنس:</strong>
                    <p class="mb-0 mt-1">{{ $genderLabels[$employee->gender] ?? '---' }}</p>
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
                    <strong>العنوان الثابت:</strong>
                    <p class="mb-0 mt-1">{{ $employee->stable_address ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بيانات الاتصال والعنوان -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0">
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
            </div>
        </div>
    </div>

    <!-- المؤهلات والديانة -->
    <div class="card card-outline card-warning shadow-sm mb-3">
        <div class="card-header bg-warning text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-graduation-cap"></i>
                المؤهلات والديانة
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>الديانة:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->religion)->name ?? '---' }}</p>
                </div>
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
                <div class="col-md-6 mb-3">
                    <strong>تخصص التخرج:</strong>
                    <p class="mb-0 mt-1">{{ $employee->graduation_specialization ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- بيانات العمل والراتب -->
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-header bg-info text-white">
            <h5 class="card-title mb-0">
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
                    <strong>حالة التوظيف:</strong>
                    <p class="mb-0 mt-1">{{ $employmentStatusLabels[$employee->employment_status] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الراتب:</strong>
                    <p class="mb-0 mt-1">{{ number_format($employee->salary, 2) ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الدفع اليومي:</strong>
                    <p class="mb-0 mt-1">{{ $employee->payment_per_day ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>الجنسية:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->nationality)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رقم الهوية:</strong>
                    <p class="mb-0 mt-1">{{ $employee->nationality_number ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- الحالة العسكرية -->
    @if($employee->gender == 1)
    <div class="card card-outline card-secondary shadow-sm mb-3">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">
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
            </div>
        </div>
    </div>
    @endif

    <!-- معلومات التأمينات -->
    <div class="card card-outline card-danger shadow-sm mb-3">
        <div class="card-header bg-danger text-white">
            <h5 class="card-title mb-0">
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
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="card card-outline card-dark shadow-sm mb-3">
        <div class="card-header bg-dark text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-info-circle"></i>
                معلومات إضافية
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <strong>الفرع:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->branch)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>نوع الوردية:</strong>
                    <p class="mb-0 mt-1">{{ optional($employee->shiftType)->name ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>رخصة قيادة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->driving_license] ?? '---' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <strong>إعاقة:</strong>
                    <p class="mb-0 mt-1">{{ $yesNoLabels[$employee->has_disability] ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات المستخدم -->
    <div class="card card-outline card-light shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="card-title mb-0">
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
            </div>
        </div>
    </div>

</div>

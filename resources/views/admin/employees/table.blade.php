<div class="container-fluid">

    @php
    $genderLabels = [1 => 'ذكر', 2 => 'أنثى'];
    $maritalStatusLabels = [1 => 'أعزب', 2 => 'متزوج', 3 => 'مخطوب', 4 => 'أرمل', 5 => 'مطلق'];
    $militaryStatusLabels = [1 => 'نشط', 2 => 'مستقيل', 3 => 'مُنهي الخدمة'];
    $graduationGradeLabels = [1 => 'ممتاز', 2 => 'جيد', 3 => 'مقبول', 4 => 'ضعيف'];
    $motivationTypeLabels = [0 => 'لا يوجد', 1 => 'ثابت', 2 => 'متغير'];
    $paymentMethodLabels = [1 => 'كاش', 2 => 'تحويل بنكي', 3 => 'شيك'];
    $yesNoLabels = [0 => 'لا', 1 => 'نعم'];
    $employmentStatusLabels = [1 => 'نشط', 0 => 'غير نشط'];
    @endphp

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close text-white text-right" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-times-circle"></i>
        {{ session('error') }}
        <button type="button" class="close text-white text-right" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-primary card-outline shadow">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h3 class="card-title mb-0">
                            <i class="fas fa-users"></i>
                            قائمة الموظفين
                        </h3>
                        <p class="text-muted mb-0">عرض جميع الموظفين بتنسيق منظم ومقسّم حسب نوع البيانات.</p>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-primary px-4 py-2">
                            عدد الموظفين: {{ $employees->total() ?? $employees->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @forelse ($employees as $employee)
    <div class="card card-outline shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="card-title mb-0">
                    <i class="fas fa-id-card"></i>
                    {{ $employee->name ?? 'بدون اسم' }}
                </h3>
                <small class="text-muted">رقم الموظف: {{ $employee->employee_code ?? '---' }} | معرف البصمة: {{
                    $employee->fingerprint_code ?? '---' }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning shadow-sm">
                    <i class="fas fa-edit"></i>
                    تعديل
                </a>
                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger are_you_sure shadow-sm">
                        <i class="fas fa-trash"></i>
                        حذف
                    </button>
                </form>
            </div>
        </div>

        <div class="card-body">

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-info-circle"></i>
                        البيانات الأساسية
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الإسم</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">كود الموظف</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->employee_code ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">كود البصمة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->fingerprint_code ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ الميلاد</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->birth_date ?
                        \Carbon\Carbon::parse($employee->birth_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الجنس</label>
                    <p class="form-control-plaintext mb-0">{{ $genderLabels[$employee->gender] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الحالة الاجتماعية</label>
                    <p class="form-control-plaintext mb-0">{{ $maritalStatusLabels[$employee->marital_status] ?? '---'
                        }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">معرف الموظف</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->id ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">عدد الأطفال</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->children_count ?? 0 }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رخصة قيادة</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->driving_license] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم رخصة القيادة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->driving_license_number ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">فئة الدم</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->blood_group_id ?? '---' }}</p>
                </div>
                <div class="col-lg-6 col-md-8 col-12 mb-3">
                    <label class="font-weight-bold">العنوان الثابت</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->stable_address ?? '---' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-phone"></i>
                        بيانات الاتصال والعنوان
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">البريد الإلكتروني</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->email ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">هاتف المنزل</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->home_telephone ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">هاتف العمل</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->work_telephone ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تفاصيل الاتصال الطارئ</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->urgent_contact_details ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الدولة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->country_id ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">المحافظة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->governorate_id ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">المدينة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->city_id ?? '---' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-shield-alt"></i>
                        الحالة العسكرية
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الحالة العسكرية</label>
                    <p class="form-control-plaintext mb-0">{{ $militaryStatusLabels[$employee->military_status] ?? '---'
                        }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ بداية الخدمة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->military_start_date ?
                        \Carbon\Carbon::parse($employee->military_start_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ إنهاء الخدمة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->military_end_date ?
                        \Carbon\Carbon::parse($employee->military_end_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">السلاح</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->military_weapon ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ الإعفاء</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->military_exemption_date ?
                        \Carbon\Carbon::parse($employee->military_exemption_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-9 col-md-8 col-12 mb-3">
                    <label class="font-weight-bold">سبب الإعفاء</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->military_exemption_reason ?? '---' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-graduation-cap"></i>
                        المؤهل والوثائق
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الديانة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->religion)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">المؤهل</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->qualification)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">سنة التخرج</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->qualification_year ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تقدير التخرج</label>
                    <p class="form-control-plaintext mb-0">{{ $graduationGradeLabels[$employee->graduation_grade] ??
                        '---' }}</p>
                </div>
                <div class="col-lg-6 col-md-8 col-12 mb-3">
                    <label class="font-weight-bold">تخصص التخرج</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->graduation_specialization ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">اسم الوظيفة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->job)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">القسم</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->department)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الجنسية</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->nationality)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم الهوية</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->nationality_number ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ انتهاء الهوية</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->nationality_expiry_date ?
                        \Carbon\Carbon::parse($employee->nationality_expiry_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">مكان إصدار الهوية</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->nationality_place_of_issue ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">اسم الكفيل</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->sponsor_name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم الجواز</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->passport_number ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ انتهاء الجواز</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->passport_expiry_date ?
                        \Carbon\Carbon::parse($employee->passport_expiry_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">مكان إصدار الجواز</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->passport_place_of_issue ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الصورة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->image ?? '---' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-briefcase"></i>
                        بيانات العمل والراتب
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ التعيين</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->hire_date ?
                        \Carbon\Carbon::parse($employee->hire_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ التعيين (يوم/شهر/سنة)</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->hire_date_day_month_year ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">حالة التوظيف</label>
                    <p class="form-control-plaintext mb-0">{{ $employmentStatusLabels[$employee->employment_status] ??
                        '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">نوع التحفيز</label>
                    <p class="form-control-plaintext mb-0">{{ $motivationTypeLabels[$employee->motivation_type] ?? '---'
                        }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">قيمة التحفيز</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->motivation_amount ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">طريقة الدفع</label>
                    <p class="form-control-plaintext mb-0">{{ $paymentMethodLabels[$employee->payment_method] ?? '---'
                        }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم الحساب البنكي</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->bank_account_number ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الراتب</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->salary !== null ?
                        number_format($employee->salary, 2) : '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">الدفع اليومي</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->payment_per_day ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">ثابت المرتب</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->fixed_allowance] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">معاش اجتماعي</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_social_insurance] ?? '---' }}
                    </p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">قيمة التأمين الاجتماعي</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->social_insurance_amount ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم التأمين الاجتماعي</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->social_insurance_number ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تأمين طبي</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_medical_insurance] ?? '---' }}
                    </p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">قيمة التأمين الطبي</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->medical_insurance_amount ?? '---' }}</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-cogs"></i>
                        إعدادات العمل الإضافية
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">ورشة ثابتة</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->fixed_shift] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">نوع الورشة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->shiftType)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">يحتسب حضور</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_attendance] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">معادلة الإجازة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->vacation_formula ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">نشط للإجازة</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->active_for_vacation] ?? '---' }}
                    </p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">إعاقة</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_disability] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">وصف الإعاقة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->disability_description ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">أحد الأقارب</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_relative] ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تفاصيل الأقارب</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->relative_description ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">ساعات العمل اليومية</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->daily_work_hours ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">بيانات حساسة</label>
                    <p class="form-control-plaintext mb-0">{{ $yesNoLabels[$employee->has_sensitive_data] ?? '---' }}
                    </p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم الفرع</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->branch_id ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">رقم الشركة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->company_id ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">اللغة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->lang_id ?? '---' }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5 class="mb-3 text-primary">
                        <i class="fas fa-history"></i>
                        معلومات الإدارة والتواريخ
                    </h5>
                </div>

                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">أضيف بواسطة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->addedBy)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">آخر تحديث بواسطة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->updatedBy)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ الإضافة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->created_at)->format('Y-m-d H:i') ??
                        '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ التحديث</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->updated_at)->format('Y-m-d H:i') ??
                        '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">سبب الاستقالة</label>
                    <p class="form-control-plaintext mb-0">{{ optional($employee->resignation)->name ?? '---' }}</p>
                </div>
                <div class="col-lg-3 col-md-4 col-12 mb-3">
                    <label class="font-weight-bold">تاريخ الاستقالة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->resignation_date ?
                        \Carbon\Carbon::parse($employee->resignation_date)->format('Y-m-d') : '---' }}</p>
                </div>
                <div class="col-lg-6 col-md-8 col-12 mb-3">
                    <label class="font-weight-bold">تفاصيل الاستقالة</label>
                    <p class="form-control-plaintext mb-0">{{ $employee->resignation_reason ?? '---' }}</p>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-circle"></i>
        لا توجد بيانات موظفين حالياً
    </div>
    @endforelse

    <div class="d-flex justify-content-center">
        {{ $employees->links() }}
    </div>
</div>

@section('js')
@endsection
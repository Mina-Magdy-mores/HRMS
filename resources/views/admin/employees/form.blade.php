<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إضافة موظف جديد</span>
                    <span class="info-box-number">الموظفين</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">حالة الصفحة</span>
                    <span class="info-box-number">إنشاء جديد</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">المستخدم الحالي</span>
                    <span class="info-box-number">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-id-badge"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">كود الشركة</span>
                    <span class="info-box-number">{{ auth()->user()->company_id }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle"></i>
                إضافة موظف جديد
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.employees.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
            <input type="hidden" name="added_by" value="{{ auth()->id() }}">

            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h5>
                        <i class="fas fa-exclamation-circle"></i>
                        يوجد أخطاء في البيانات
                    </h5>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle"></i>
                    {{ session('error') }}
                </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-user"></i>
                            بيانات الموظف الرئيسية
                        </h5>
                    </div>

                    <div class="col-12">
                        <ul class="nav nav-tabs" id="employee-form-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tab-basic-tab" data-toggle="pill" href="#tab-basic"
                                    role="tab" aria-controls="tab-basic" aria-selected="true">أساسي</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-military-tab" data-toggle="pill" href="#tab-military"
                                    role="tab" aria-controls="tab-military" aria-selected="false">عسكري</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-job-tab" data-toggle="pill" href="#tab-job" role="tab"
                                    aria-controls="tab-job" aria-selected="false">وظيفة وتعليم</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-financial-tab" data-toggle="pill" href="#tab-financial"
                                    role="tab" aria-controls="tab-financial" aria-selected="false">مالي وتأمين</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tab-extra-tab" data-toggle="pill" href="#tab-extra" role="tab"
                                    aria-controls="tab-extra" aria-selected="false">بيانات إضافية</a>
                            </li>
                        </ul>

                        <div class="tab-content pt-4" id="employee-form-tabs-content">

                            <div class="tab-pane fade show active" id="tab-basic" role="tabpanel"
                                aria-labelledby="tab-basic-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>كود الموظف</label>
                                            <input autofocus type="number" name="employee_code"
                                                value="{{ old('employee_code') }}"
                                                class="form-control {{ $errors->has('employee_code') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل كود الموظف">
                                            @include('admin.errors.errors', ['value' => 'employee_code'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>معرف البصمة</label>
                                            <input type="text" name="fingerprint_code"
                                                value="{{ old('fingerprint_code') }}"
                                                class="form-control {{ $errors->has('fingerprint_code') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل معرف البصمة">
                                            @include('admin.errors.errors', ['value' => 'fingerprint_code'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اسم الموظف</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل اسم الموظف">
                                            @include('admin.errors.errors', ['value' => 'name'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ الميلاد</label>
                                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                                class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'birth_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الجنسية</label>
                                            <select name="nationality_id"
                                                class="form-control select2 {{ $errors->has('nationality_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الجنسية</option>
                                                @foreach($nationalities as $nationality)
                                                <option value="{{ $nationality->id }}" {{
                                                    old('nationality_id')==$nationality->id ? 'selected' : '' }}>
                                                    {{ $nationality->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'nationality_id'])
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الجنس</label>
                                            <select name="gender"
                                                class="form-control select2 {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الجنس</option>
                                                <option value="1" {{ old('gender')=='1' ? 'selected' : '' }}>ذكر
                                                </option>
                                                <option value="2" {{ old('gender')=='2' ? 'selected' : '' }}>أنثى
                                                </option>
                                                <option value="3" {{ old('gender')=='3' ? 'selected' : '' }}>آخر
                                                </option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'gender'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الديانة</label>
                                            <select name="religion_id"
                                                class="form-control select2 {{ $errors->has('religion_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الديانة</option>
                                                @foreach($religions as $religion)
                                                <option value="{{ $religion->id }}" {{ old('religion_id')==$religion->id
                                                    ? 'selected' : '' }}>
                                                    {{ $religion->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'religion_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم البطاقة</label>
                                            <input type="text" name="nationality_number"
                                                value="{{ old('nationality_number') }}"
                                                class="form-control {{ $errors->has('nationality_number') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل رقم البطاقة">
                                            @include('admin.errors.errors', ['value' => 'nationality_number'])
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>انتهاء البطاقة</label>
                                            <input type="date" name="nationality_expiry_date"
                                                value="{{ old('nationality_expiry_date') }}"
                                                class="form-control {{ $errors->has('nationality_expiry_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'nationality_expiry_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مكان إصدار البطاقة</label>
                                            <input type="text" name="nationality_place_of_issue"
                                                value="{{ old('nationality_place_of_issue') }}"
                                                class="form-control {{ $errors->has('nationality_place_of_issue') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مكان الإصدار">
                                            @include('admin.errors.errors', ['value' => 'nationality_place_of_issue'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>البريد الالكتروني</label>
                                            <input type="email" name="email" value="{{ old('email') }}"
                                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل البريد الالكتروني">
                                            @include('admin.errors.errors', ['value' => 'email'])
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هاتف المنزل</label>
                                            <input type="text" name="home_telephone" value="{{ old('home_telephone') }}"
                                                class="form-control {{ $errors->has('home_telephone') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل هاتف المنزل">
                                            @include('admin.errors.errors', ['value' => 'home_telephone'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هاتف العمل</label>
                                            <input type="text" name="work_telephone" value="{{ old('work_telephone') }}"
                                                class="form-control {{ $errors->has('work_telephone') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل هاتف العمل">
                                            @include('admin.errors.errors', ['value' => 'work_telephone'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الحالة الاجتماعية</label>
                                            <select name="marital_status"
                                                class="form-control select2 {{ $errors->has('marital_status') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الحالة</option>
                                                <option value="1" {{ old('marital_status')=='1' ? 'selected' : '' }}>
                                                    أعزب</option>
                                                <option value="2" {{ old('marital_status')=='2' ? 'selected' : '' }}>
                                                    متزوج</option>
                                                <option value="3" {{ old('marital_status')=='3' ? 'selected' : '' }}>
                                                    مخطوب</option>
                                                <option value="4" {{ old('marital_status')=='4' ? 'selected' : '' }}>
                                                    أرمل</option>
                                                <option value="5" {{ old('marital_status')=='5' ? 'selected' : '' }}>
                                                    مطلق</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'marital_status'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>عدد الأطفال</label>
                                            <input type="number" min="0" name="children_count"
                                                value="{{ old('children_count', 0) }}"
                                                class="form-control {{ $errors->has('children_count') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'children_count'])
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>العنوان الثابت</label>
                                            <input type="text" name="stable_address" value="{{ old('stable_address') }}"
                                                class="form-control {{ $errors->has('stable_address') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل العنوان الثابت">
                                            @include('admin.errors.errors', ['value' => 'stable_address'])
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الدولة</label>
                                            <select name="country_id" id="country_id"
                                                class="form-control select2 {{ $errors->has('country_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الدولة</option>
                                                @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ old('country_id')==$country->id ?
                                                    'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'country_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" id="ajax_responce_for_governorate_list">
                                            <label>المحافظة</label>
                                            <select name="governorate_id" id="governorate_id"
                                                class="form-control select2 {{ $errors->has('governorate_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر المحافظة</option>
                                                @foreach($governorates as $governorate)
                                                <option value="{{ $governorate->id }}" {{
                                                    old('governorate_id')==$governorate->id ? 'selected' : '' }}>
                                                    {{ $governorate->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'governorate_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group" id="ajax_responce_for_cities_list">
                                            <label>المدينة</label>
                                            <select name="city_id" id="city_id"
                                                class="form-control select2 {{ $errors->has('city_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر المدينة</option>
                                                @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('city_id')==$city->id ?
                                                    'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'city_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>فصيلة الدم</label>
                                            <select name="blood_group_id"
                                                class="form-control select2 {{ $errors->has('blood_group_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر فصيلة الدم</option>
                                                @foreach($bloodGroups as $bloodGroup)
                                                <option value="{{ $bloodGroup->id }}" {{
                                                    old('blood_group_id')==$bloodGroup->id ? 'selected' : '' }}>
                                                    {{ $bloodGroup->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'blood_group_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رخصة القيادة</label>
                                            <select name="driving_license"
                                                class="form-control select2 {{ $errors->has('driving_license') ? 'is-invalid' : '' }}">
                                                <option value="0" {{ old('driving_license')=='0' ? 'selected' : '' }}>لا
                                                </option>
                                                <option value="1" {{ old('driving_license')=='1' ? 'selected' : '' }}>
                                                    نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'driving_license'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم رخصة القيادة</label>
                                            <input type="text" name="driving_license_number"
                                                value="{{ old('driving_license_number') }}"
                                                class="form-control {{ $errors->has('driving_license_number') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل رقم رخصة القيادة">
                                            @include('admin.errors.errors', ['value' => 'driving_license_number'])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-military" role="tabpanel"
                                aria-labelledby="tab-military-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الحالة العسكرية</label>
                                            <select name="military_status"
                                                class="form-control select2 {{ $errors->has('military_status') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الحالة</option>
                                                <option value="1" {{ old('military_status')=='1' ? 'selected' : '' }}>
                                                    نشط</option>
                                                <option value="2" {{ old('military_status')=='2' ? 'selected' : '' }}>
                                                    استقال</option>
                                                <option value="3" {{ old('military_status')=='3' ? 'selected' : '' }}>
                                                    مسرح</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'military_status'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>بداية الخدمة العسكرية</label>
                                            <input type="date" name="military_start_date"
                                                value="{{ old('military_start_date') }}"
                                                class="form-control {{ $errors->has('military_start_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'military_start_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>نهاية الخدمة العسكرية</label>
                                            <input type="date" name="military_end_date"
                                                value="{{ old('military_end_date') }}"
                                                class="form-control {{ $errors->has('military_end_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'military_end_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>سلاح الخدمة</label>
                                            <input type="text" name="military_weapon"
                                                value="{{ old('military_weapon') }}"
                                                class="form-control {{ $errors->has('military_weapon') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل سلاح الخدمة">
                                            @include('admin.errors.errors', ['value' => 'military_weapon'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ الإعفاء</label>
                                            <input type="date" name="military_exemption_date"
                                                value="{{ old('military_exemption_date') }}"
                                                class="form-control {{ $errors->has('military_exemption_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'military_exemption_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>سبب الإعفاء</label>
                                            <input type="text" name="military_exemption_reason"
                                                value="{{ old('military_exemption_reason') }}"
                                                class="form-control {{ $errors->has('military_exemption_reason') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل سبب الإعفاء">
                                            @include('admin.errors.errors', ['value' => 'military_exemption_reason'])
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-job" role="tabpanel" aria-labelledby="tab-job-tab">
                                <div class="row">


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>المؤهل</label>
                                            <select name="qualifications_id"
                                                class="form-control select2 {{ $errors->has('qualifications_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر المؤهل</option>
                                                @foreach($qualifications as $qualification)
                                                <option value="{{ $qualification->id }}" {{
                                                    old('qualifications_id')==$qualification->id ? 'selected' : '' }}>
                                                    {{ $qualification->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'qualifications_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>سنة التخرج</label>
                                            <input type="text" name="qualification_year"
                                                value="{{ old('qualification_year') }}"
                                                class="form-control {{ $errors->has('qualification_year') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل سنة التخرج">
                                            @include('admin.errors.errors', ['value' => 'qualification_year'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تقدير التخرج</label>
                                            <select name="graduation_grade"
                                                class="form-control select2 {{ $errors->has('graduation_grade') ? 'is-invalid' : '' }}">
                                                <option value="">اختر التقدير</option>
                                                <option value="1" {{ old('graduation_grade')=='1' ? 'selected' : '' }}>
                                                    ممتاز</option>
                                                <option value="2" {{ old('graduation_grade')=='2' ? 'selected' : '' }}>
                                                    جيد جدا</option>
                                                <option value="3" {{ old('graduation_grade')=='3' ? 'selected' : '' }}>
                                                    جيد</option>
                                                <option value="4" {{ old('graduation_grade')=='4' ? 'selected' : '' }}>
                                                    مقبول</option>
                                                <option value="5" {{ old('graduation_grade')=='5' ? 'selected' : '' }}>
                                                    ضعيف</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'graduation_grade'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تخصص التخرج</label>
                                            <input type="text" name="graduation_specialization"
                                                value="{{ old('graduation_specialization') }}"
                                                class="form-control {{ $errors->has('graduation_specialization') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل التخصص">
                                            @include('admin.errors.errors', ['value' => 'graduation_specialization'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الوظيفة</label>
                                            <select name="job_id"
                                                class="form-control select2 {{ $errors->has('job_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الوظيفة</option>
                                                @foreach($jobs as $job)
                                                <option value="{{ $job->id }}" {{ old('job_id')==$job->id ? 'selected' :
                                                    '' }}>
                                                    {{ $job->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'job_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الإدارة</label>
                                            <select name="department_id"
                                                class="form-control select2 {{ $errors->has('department_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الإدارة</option>
                                                @foreach($departments as $department)
                                                <option value="{{ $department->id }}" {{
                                                    old('department_id')==$department->id ? 'selected' : '' }}>
                                                    {{ $department->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'department_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الفرع</label>
                                            <select name="branch_id"
                                                class="form-control select2 {{ $errors->has('branch_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر الفرع</option>
                                                @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('branch_id')==$branch->id ?
                                                    'selected' : '' }}>
                                                    {{ $branch->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'branch_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ التعيين</label>
                                            <input type="date" name="hire_date" value="{{ old('hire_date') }}"
                                                class="form-control {{ $errors->has('hire_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'hire_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ التعيين (يوم/شهر/سنة)</label>
                                            <input type="date" name="hire_date_day_month_year"
                                                value="{{ old('hire_date_day_month_year') }}"
                                                class="form-control {{ $errors->has('hire_date_day_month_year') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'hire_date_day_month_year'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>حالة التوظيف</label>
                                            <select name="employment_status"
                                                class="form-control select2 {{ $errors->has('employment_status') ? 'is-invalid' : '' }}">
                                                <option value="1" {{ old('employment_status', '1' )=='1' ? 'selected'
                                                    : '' }}>نشط</option>
                                                <option value="0" {{ old('employment_status')=='0' ? 'selected' : '' }}>
                                                    غير نشط</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'employment_status'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>استقالة</label>
                                            <select name="resignation_id"
                                                class="form-control select2 {{ $errors->has('resignation_id') ? 'is-invalid' : '' }}">
                                                <option value="">اختر حالة الاستقالة</option>
                                                @foreach($resignations as $resignation)
                                                <option value="{{ $resignation->id }}" {{
                                                    old('resignation_id')==$resignation->id ? 'selected' : '' }}>
                                                    {{ $resignation->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'resignation_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاريخ الاستقالة</label>
                                            <input type="date" name="resignation_date"
                                                value="{{ old('resignation_date') }}"
                                                class="form-control {{ $errors->has('resignation_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'resignation_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>سبب الاستقالة</label>
                                            <input type="text" name="resignation_reason"
                                                value="{{ old('resignation_reason') }}"
                                                class="form-control {{ $errors->has('resignation_reason') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل سبب الاستقالة">
                                            @include('admin.errors.errors', ['value' => 'resignation_reason'])
                                        </div>
                                    </div>




                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-financial" role="tabpanel"
                                aria-labelledby="tab-financial-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الراتب</label>
                                            <input type="number" step="0.01" name="salary"
                                                value="{{ old('salary', 0.00) }}"
                                                class="form-control {{ $errors->has('salary') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل الراتب">
                                            @include('admin.errors.errors', ['value' => 'salary'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>نوع الحافز</label>
                                            <select name="motivation_type"
                                                class="form-control select2 {{ $errors->has('motivation_type') ? 'is-invalid' : '' }}">
                                                <option value="">اختر النوع</option>
                                                <option value="0" {{ old('motivation_type')=='0' ? 'selected' : '' }}>
                                                    بدون</option>
                                                <option value="1" {{ old('motivation_type')=='1' ? 'selected' : '' }}>
                                                    ثابت</option>
                                                <option value="2" {{ old('motivation_type')=='2' ? 'selected' : '' }}>
                                                    متغير</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'motivation_type'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مبلغ الحافز</label>
                                            <input type="number" step="0.01" name="motivation_amount"
                                                value="{{ old('motivation_amount') }}"
                                                class="form-control {{ $errors->has('motivation_amount') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مبلغ الحافز">
                                            @include('admin.errors.errors', ['value' => 'motivation_amount'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>طريقة الدفع</label>
                                            <select name="payment_method"
                                                class="form-control select2 {{ $errors->has('payment_method') ? 'is-invalid' : '' }}">
                                                <option value="">اختر طريقة الدفع</option>
                                                <option value="1" {{ old('payment_method')=='1' ? 'selected' : '' }}>
                                                    نقداً</option>
                                                <option value="2" {{ old('payment_method')=='2' ? 'selected' : '' }}>
                                                    تحويل بنكي</option>
                                                <option value="3" {{ old('payment_method')=='3' ? 'selected' : '' }}>شيك
                                                </option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'payment_method'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم الحساب البنكي</label>
                                            <input type="text" name="bank_account_number"
                                                value="{{ old('bank_account_number') }}"
                                                class="form-control {{ $errors->has('bank_account_number') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل رقم الحساب البنكي">
                                            @include('admin.errors.errors', ['value' => 'bank_account_number'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>الدفع لكل يوم</label>
                                            <input type="number" step="0.01" name="payment_per_day"
                                                value="{{ old('payment_per_day') }}"
                                                class="form-control {{ $errors->has('payment_per_day') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مبلغ اليوم الواحد">
                                            @include('admin.errors.errors', ['value' => 'payment_per_day'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له تأمين اجتماعي</label>
                                            <select name="has_social_insurance"
                                                class="form-control select2 {{ $errors->has('has_social_insurance') ? 'is-invalid' : '' }}">
                                                <option value="">اختر</option>
                                                <option value="0" {{ old('has_social_insurance')=='0' ? 'selected' : ''
                                                    }}>لا</option>
                                                <option value="1" {{ old('has_social_insurance')=='1' ? 'selected' : ''
                                                    }}>نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_social_insurance'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مبلغ التأمين الاجتماعي</label>
                                            <input type="number" step="0.01" name="social_insurance_amount"
                                                value="{{ old('social_insurance_amount') }}"
                                                class="form-control {{ $errors->has('social_insurance_amount') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مبلغ التأمين الاجتماعي">
                                            @include('admin.errors.errors', ['value' => 'social_insurance_amount'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم التأمين الاجتماعي</label>
                                            <input type="text" name="social_insurance_number"
                                                value="{{ old('social_insurance_number') }}"
                                                class="form-control {{ $errors->has('social_insurance_number') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل رقم التأمين الاجتماعي">
                                            @include('admin.errors.errors', ['value' => 'social_insurance_number'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>هل له تأمين طبي</label>
                                            <select name="has_medical_insurance"
                                                class="form-control select2 {{ $errors->has('has_medical_insurance') ? 'is-invalid' : '' }}">
                                                <option value="">اختر</option>
                                                <option value="0" {{ old('has_medical_insurance')=='0' ? 'selected' : ''
                                                    }}>لا</option>
                                                <option value="1" {{ old('has_medical_insurance')=='1' ? 'selected' : ''
                                                    }}>نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_medical_insurance'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مبلغ التأمين الطبي</label>
                                            <input type="number" step="0.01" name="medical_insurance_amount"
                                                value="{{ old('medical_insurance_amount') }}"
                                                class="form-control {{ $errors->has('medical_insurance_amount') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مبلغ التأمين الطبي">
                                            @include('admin.errors.errors', ['value' => 'medical_insurance_amount'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>بدل ثابت</label>
                                            <select name="fixed_allowance"
                                                class="form-control select2 {{ $errors->has('fixed_allowance') ? 'is-invalid' : '' }}">
                                                <option value="0" {{ old('fixed_allowance', '0' )=='0' ? 'selected' : ''
                                                    }}>لا</option>
                                                <option value="1" {{ old('fixed_allowance')=='1' ? 'selected' : '' }}>
                                                    نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'fixed_allowance'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>حضور</label>
                                            <select name="has_attendance"
                                                class="form-control select2 {{ $errors->has('has_attendance') ? 'is-invalid' : '' }}">
                                                <option value="1" {{ old('has_attendance', '1' )=='1' ? 'selected' : ''
                                                    }}>نعم</option>
                                                <option value="0" {{ old('has_attendance')=='0' ? 'selected' : '' }}>لا
                                                </option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_attendance'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>صيغة الإجازة</label>
                                            <input type="number" name="vacation_formula"
                                                value="{{ old('vacation_formula') }}"
                                                class="form-control {{ $errors->has('vacation_formula') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل صيغة الإجازة">
                                            @include('admin.errors.errors', ['value' => 'vacation_formula'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>نشط للإجازة</label>
                                            <select name="active_for_vacation"
                                                class="form-control select2 {{ $errors->has('active_for_vacation') ? 'is-invalid' : '' }}">
                                                <option value="0" {{ old('active_for_vacation', '0' )=='0' ? 'selected'
                                                    : '' }}>لا</option>
                                                <option value="1" {{ old('active_for_vacation')=='1' ? 'selected' : ''
                                                    }}>نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'active_for_vacation'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>بيانات حساسة</label>
                                            <select name="has_sensitive_data"
                                                class="form-control select2 {{ $errors->has('has_sensitive_data') ? 'is-invalid' : '' }}">
                                                <option value="0" {{ old('has_sensitive_data', '0' )=='0' ? 'selected'
                                                    : '' }}>لا</option>
                                                <option value="1" {{ old('has_sensitive_data')=='1' ? 'selected' : ''
                                                    }}>نعم</option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_sensitive_data'])
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>عدد ساعات العمل اليومية</label>
                                            <input type="number" step="0.01" name="daily_work_hours"
                                                value="{{ old('daily_work_hours') }}"
                                                class="form-control {{ $errors->has('daily_work_hours') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل ساعات العمل اليومية">
                                            @include('admin.errors.errors', ['value' => 'daily_work_hours'])
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="tab-extra" role="tabpanel" aria-labelledby="tab-extra-tab">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>اسم الكفيل</label>
                                            <input type="text" name="sponsor_name" value="{{ old('sponsor_name') }}"
                                                class="form-control {{ $errors->has('sponsor_name') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل اسم الكفيل">
                                            @include('admin.errors.errors', ['value' => 'sponsor_name'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رقم الجواز</label>
                                            <input type="text" name="passport_number"
                                                value="{{ old('passport_number') }}"
                                                class="form-control {{ $errors->has('passport_number') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل رقم الجواز">
                                            @include('admin.errors.errors', ['value' => 'passport_number'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>انتهاء الجواز</label>
                                            <input type="date" name="passport_expiry_date"
                                                value="{{ old('passport_expiry_date') }}"
                                                class="form-control {{ $errors->has('passport_expiry_date') ? 'is-invalid' : '' }}">
                                            @include('admin.errors.errors', ['value' => 'passport_expiry_date'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>مكان إصدار الجواز</label>
                                            <input type="text" name="passport_place_of_issue"
                                                value="{{ old('passport_place_of_issue') }}"
                                                class="form-control {{ $errors->has('passport_place_of_issue') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل مكان الإصدار">
                                            @include('admin.errors.errors', ['value' => 'passport_place_of_issue'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>رابط الصورة / الملف</label>
                                            <input type="file" name="image"
                                                class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                                accept="image/*">
                                            @include('admin.errors.errors', ['value' => 'image'])
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>لغة</label>
                                            <input type="number" name="lang_id" value="{{ old('lang_id') }}"
                                                class="form-control {{ $errors->has('lang_id') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل معرف اللغة">
                                            @include('admin.errors.errors', ['value' => 'lang_id'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>لديه إعاقة</label>
                                            <select name="has_disability"
                                                class="form-control select2 {{ $errors->has('has_disability') ? 'is-invalid' : '' }}">
                                                <option value="">اختر</option>
                                                <option value="0" {{ old('has_disability')=='0' ? 'selected' : '' }}>لا
                                                </option>
                                                <option value="1" {{ old('has_disability')=='1' ? 'selected' : '' }}>نعم
                                                </option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_disability'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>وصف الإعاقة</label>
                                            <input type="text" name="disability_description"
                                                value="{{ old('disability_description') }}"
                                                class="form-control {{ $errors->has('disability_description') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل وصف الإعاقة">
                                            @include('admin.errors.errors', ['value' => 'disability_description'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>له قريب</label>
                                            <select name="has_relative"
                                                class="form-control select2 {{ $errors->has('has_relative') ? 'is-invalid' : '' }}">
                                                <option value="">اختر</option>
                                                <option value="0" {{ old('has_relative')=='0' ? 'selected' : '' }}>لا
                                                </option>
                                                <option value="1" {{ old('has_relative')=='1' ? 'selected' : '' }}>نعم
                                                </option>
                                            </select>
                                            @include('admin.errors.errors', ['value' => 'has_relative'])
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>وصف القريب</label>
                                            <input type="text" name="relative_description"
                                                value="{{ old('relative_description') }}"
                                                class="form-control {{ $errors->has('relative_description') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل وصف القريب">
                                            @include('admin.errors.errors', ['value' => 'relative_description'])
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>بيانات الاتصال العاجلة</label>
                                            <textarea name="urgent_contact_details" rows="3"
                                                class="form-control {{ $errors->has('urgent_contact_details') ? 'is-invalid' : '' }}"
                                                placeholder="أدخل تفاصيل الاتصال العاجلة">{{ old('urgent_contact_details') }}</textarea>
                                            @include('admin.errors.errors', ['value' => 'urgent_contact_details'])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>

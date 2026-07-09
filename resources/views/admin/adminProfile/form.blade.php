<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-user-plus"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إضافة مستخدم جديد</span>
                    <span class="info-box-number">المستخدمين</span>
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
                    <span class="info-box-number">
                        {{ auth()->user()->name }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-building"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">كود الشركة</span>
                    <span class="info-box-number">
                        {{ auth()->user()->company_id }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-plus-circle"></i>
                إضافة مستخدم جديد
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.admin-profiles.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>

        </div>

        <form action="{{ route('admin.admin-profiles.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="card-body">

                <!-- Validation Errors -->
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

                <!-- بيانات الأدمن -->
                <div class="row">

                    <div class="col-12">
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-user-circle"></i>
                            البيانات الأساسية
                        </h5>
                    </div>

                    <!-- صورة الملف الشخصي -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group text-center">
                            <label>صورة الملف الشخصي</label>
                            <div class="mt-2">
                                <img id="imagePreview" src="{{ asset('assets/img/user-default.png') }}"
                                    class="rounded-circle shadow mb-2"
                                    width="100" height="100"
                                    style="object-fit: cover; display:block; margin: 0 auto;">
                                <input type="file" name="image" id="imageInput"
                                    class="form-control mt-2 {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                    accept="image/*">
                                @include('admin.errors.errors', ['value' => 'image'])
                            </div>
                        </div>
                    </div>

                    <!-- الاسم -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="أدخل الاسم الكامل">
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <!-- اسم المستخدم -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>اسم المستخدم <span class="text-danger">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                placeholder="أدخل اسم المستخدم">
                            @include('admin.errors.errors', ['value' => 'username'])
                        </div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="أدخل البريد الإلكتروني">
                            @include('admin.errors.errors', ['value' => 'email'])
                        </div>
                    </div>

                    <!-- كلمة المرور -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="أدخل كلمة المرور">
                            @include('admin.errors.errors', ['value' => 'password'])
                        </div>
                    </div>

                    <!-- تأكيد كلمة المرور -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="أعد إدخال كلمة المرور">
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>مفعّل</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>معطّل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'status'])
                        </div>
                    </div>

                    <!-- مدير رئيسي (ماستر) -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>مدير رئيسي (ماستر) <span class="text-danger">*</span></label>
                            <select name="is_master_admin" id="is_master_admin" class="form-control {{ $errors->has('is_master_admin') ? 'is-invalid' : '' }}">
                                <option value="0" {{ old('is_master_admin') == 0 ? 'selected' : '' }}>لا</option>
                                <option value="1" {{ old('is_master_admin') == 1 ? 'selected' : '' }}>نعم (كامل الصلاحيات)</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_master_admin'])
                        </div>
                    </div>

                    <!-- دور الصلاحية -->
                    <div class="col-md-4" id="role_container">
                        <div class="form-group">
                            <label>دور الصلاحية</label>
                            <select name="permission_role_id" class="form-control {{ $errors->has('permission_role_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر الدور</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('permission_role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'permission_role_id'])
                        </div>
                    </div>

                    <!-- هل هو موظف؟ -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>هل هو موظف؟ <span class="text-danger">*</span></label>
                            <select name="is_employee" id="is_employee" class="form-control {{ $errors->has('is_employee') ? 'is-invalid' : '' }}">
                                <option value="0" {{ old('is_employee') == 0 ? 'selected' : '' }}>لا (مستخدم لوحة التحكم)</option>
                                <option value="1" {{ old('is_employee') == 1 ? 'selected' : '' }}>نعم (موظف في الشركة)</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_employee'])
                        </div>
                    </div>

                    <!-- سماحية الدخول للموظف -->
                    <div class="col-md-4" id="allow_login_container" style="display: none;">
                        <div class="form-group">
                            <label>سماحية الدخول إلى النظام <span class="text-danger">*</span></label>
                            <select name="allow_login" class="form-control {{ $errors->has('allow_login') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('allow_login', 1) == 1 ? 'selected' : '' }}>نعم (مسموح بالدخول)</option>
                                <option value="0" {{ old('allow_login') === 0 || old('allow_login') === '0' ? 'selected' : '' }}>لا (حظر الدخول)</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'allow_login'])
                        </div>
                    </div>

                    <!-- الموظف المرتبط -->
                    <div class="col-md-4" id="employee_container" style="display: none;">
                        <div class="form-group">
                            <label>اختر الموظف <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-control {{ $errors->has('employee_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر الموظف</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" 
                                        data-name="{{ $emp->name }}"
                                        data-email="{{ $emp->email }}"
                                        data-birth-date="{{ $emp->birth_date }}"
                                        data-gender="{{ $emp->gender == 1 ? 'male' : ($emp->gender == 2 ? 'female' : '') }}"
                                        data-national-id="{{ $emp->nationality_number }}"
                                        data-phone="{{ $emp->work_telephone ?: $emp->home_telephone }}"
                                        data-address="{{ $emp->home_address ?: $emp->stable_address }}"
                                        {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} (كود: {{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'employee_id'])
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-id-card"></i>
                            البيانات الشخصية
                        </h5>
                    </div>

                    <!-- رقم الهاتف -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                placeholder="أدخل رقم الهاتف">
                            @include('admin.errors.errors', ['value' => 'phone'])
                        </div>
                    </div>

                    <!-- الرقم القومي -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الرقم القومي</label>
                            <input type="text" name="national_id" value="{{ old('national_id') }}"
                                class="form-control {{ $errors->has('national_id') ? 'is-invalid' : '' }}"
                                placeholder="أدخل الرقم القومي">
                            @include('admin.errors.errors', ['value' => 'national_id'])
                        </div>
                    </div>

                    <!-- الجنس -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الجنس</label>
                            <select name="gender" class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}">
                                <option value="">اختر الجنس</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'gender'])
                        </div>
                    </div>

                    <!-- تاريخ الميلاد -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تاريخ الميلاد</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'birth_date'])
                        </div>
                    </div>

                    <!-- العنوان -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                placeholder="أدخل العنوان">
                            @include('admin.errors.errors', ['value' => 'address'])
                        </div>
                    </div>

                    <!-- نبذة شخصية -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>نبذة شخصية</label>
                            <textarea name="bio" rows="3"
                                class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}"
                                placeholder="أدخل نبذة شخصية مختصرة">{{ old('bio') }}</textarea>
                            @include('admin.errors.errors', ['value' => 'bio'])
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="card-footer text-left">

                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>

                <a href="{{ route('admin.admin-profiles.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>

            </div>

        </form>

    </div>

</div>

@push('js')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    const isMasterSelect = document.getElementById('is_master_admin');
    const roleContainer = document.getElementById('role_container');
    function toggleRoleSelect() {
        if (isMasterSelect.value == '1') {
            roleContainer.style.display = 'none';
        } else {
            roleContainer.style.display = 'block';
        }
    }
    isMasterSelect.addEventListener('change', toggleRoleSelect);
    toggleRoleSelect();

    const isEmployeeSelect = document.getElementById('is_employee');
    const employeeContainer = document.getElementById('employee_container');
    const allowLoginContainer = document.getElementById('allow_login_container');
    const employeeSelect = document.getElementById('employee_id');

    function toggleEmployeeSelect() {
        if (isEmployeeSelect.value == '1') {
            employeeContainer.style.display = 'block';
            allowLoginContainer.style.display = 'block';
        } else {
            employeeContainer.style.display = 'none';
            allowLoginContainer.style.display = 'none';
        }
    }
    isEmployeeSelect.addEventListener('change', toggleEmployeeSelect);
    toggleEmployeeSelect();

    employeeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.value !== "") {
            const name = selectedOption.getAttribute('data-name') || '';
            const email = selectedOption.getAttribute('data-email') || '';
            const birthDate = selectedOption.getAttribute('data-birth-date') || '';
            const gender = selectedOption.getAttribute('data-gender') || '';
            const nationalId = selectedOption.getAttribute('data-national-id') || '';
            const phone = selectedOption.getAttribute('data-phone') || '';
            const address = selectedOption.getAttribute('data-address') || '';

            document.querySelector('input[name="name"]').value = name;
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="birth_date"]').value = birthDate;
            document.querySelector('select[name="gender"]').value = gender;
            document.querySelector('input[name="national_id"]').value = nationalId;
            document.querySelector('input[name="phone"]').value = phone;
            document.querySelector('input[name="address"]').value = address;
        }
    });
</script>
@endpush

<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-user-edit"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">تعديل أدمن</span>
                    <span class="info-box-number">{{ $admin->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                @if($admin->status == 1)
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                @else
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                @endif
                <div class="info-box-content">
                    <span class="info-box-text">الحالة الحالية</span>
                    <span class="info-box-number">
                        {{ $admin->status == 1 ? 'مفعّل' : 'معطّل' }}
                    </span>
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
                <i class="fas fa-user-edit"></i>
                تعديل بيانات أدمن
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.admin-profiles.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>

        </div>

        <form action="{{ route('admin.admin-profiles.update', $admin->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

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
                                @if($admin->image)
                                <img id="imagePreview" src="{{ asset('storage/' . $admin->image) }}"
                                    class="rounded-circle shadow mb-2"
                                    width="100" height="100"
                                    style="object-fit: cover; display:block; margin: 0 auto;">
                                @else
                                <img id="imagePreview" src="{{ asset('assets/img/user-default.png') }}"
                                    class="rounded-circle shadow mb-2"
                                    width="100" height="100"
                                    style="object-fit: cover; display:block; margin: 0 auto;">
                                @endif
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
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <!-- اسم المستخدم -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>اسم المستخدم <span class="text-danger">*</span></label>
                            <input type="text" name="username" value="{{ old('username', $admin->username) }}"
                                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'username'])
                        </div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'email'])
                        </div>
                    </div>

                    <!-- تغيير كلمة المرور -->
                    <div class="col-12 mt-2">
                        <h5 class="mb-3 text-warning">
                            <i class="fas fa-lock"></i>
                            تغيير كلمة المرور <small class="text-muted" style="font-size:13px;">(اتركها فارغة إذا لا تريد التغيير)</small>
                        </h5>
                    </div>

                    <!-- كلمة المرور الحالية -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كلمة المرور الحالية</label>
                            <input type="password" name="current_password"
                                class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                placeholder="أدخل كلمة المرور الحالية">
                            @include('admin.errors.errors', ['value' => 'current_password'])
                        </div>
                    </div>

                    <!-- كلمة المرور الجديدة -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كلمة المرور الجديدة</label>
                            <input type="password" name="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="أدخل كلمة المرور الجديدة">
                            @include('admin.errors.errors', ['value' => 'password'])
                        </div>
                    </div>

                    <!-- تأكيد كلمة المرور الجديدة -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="أعد إدخال كلمة المرور الجديدة">
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option disabled value="">اختر الحالة</option>
                                <option value="1" {{ old('status', $admin->status) == 1 ? 'selected' : '' }}>مفعّل</option>
                                <option value="0" {{ old('status', $admin->status) == 0 ? 'selected' : '' }}>معطّل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'status'])
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
                            <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                                class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                placeholder="أدخل رقم الهاتف">
                            @include('admin.errors.errors', ['value' => 'phone'])
                        </div>
                    </div>

                    <!-- الرقم القومي -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الرقم القومي</label>
                            <input type="text" name="national_id" value="{{ old('national_id', $admin->national_id) }}"
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
                                <option value="male" {{ old('gender', $admin->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender', $admin->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'gender'])
                        </div>
                    </div>

                    <!-- تاريخ الميلاد -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تاريخ الميلاد</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $admin->birth_date) }}"
                                class="form-control {{ $errors->has('birth_date') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'birth_date'])
                        </div>
                    </div>

                    <!-- العنوان -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="address" value="{{ old('address', $admin->address) }}"
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
                                placeholder="أدخل نبذة شخصية مختصرة">{{ old('bio', $admin->bio) }}</textarea>
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
</script>
@endpush

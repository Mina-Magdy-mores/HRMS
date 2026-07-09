@extends('admin.layouts.admin')

@section('title', 'الملف الشخصي')

@section('contentHeader')
    <i class="fas fa-user-circle"></i>
    الملف الشخصي
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="#">الملف الشخصي</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
<div class="container-fluid">

    <!-- Info Boxes for current status and settings (Read-only) -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-user-shield"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">نوع الحساب</span>
                    <span class="info-box-number text-primary">
                        @if($admin->is_master_admin == 1)
                            مدير رئيسي (ماستر)
                        @else
                            {{ $admin->permissionRole->name ?? 'لا يوجد دور محدد' }}
                        @endif
                    </span>
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
                    <i class="fas fa-id-badge"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">اسم المستخدم</span>
                    <span class="info-box-number text-warning">
                        {{ $admin->username }}
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
                        {{ $admin->company_id }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Profile Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-edit mr-1"></i>
                تعديل بيانات الملف الشخصي
            </h3>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">

                <!-- Success & Error Alert system monitoring -->
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> تم بنجاح!</h5>
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> تنبيه!</h5>
                    {{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> يوجد أخطاء في البيانات المدخلة</h5>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- بيانات الملف الشخصي -->
                <div class="row">

                    <div class="col-12">
                        <h5 class="mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-user-circle"></i>
                            البيانات الأساسية للمستخدم
                        </h5>
                    </div>

                    <!-- صورة الملف الشخصي -->
                    <div class="col-md-12 mb-4 text-center">
                        <div class="form-group">
                            <label class="d-block">صورة الملف الشخصي</label>
                            <div class="mt-2">
                                @if($admin->image)
                                <img id="imagePreview" src="{{ asset('storage/' . $admin->image) }}"
                                    class="rounded-circle shadow border-info"
                                    width="120" height="120"
                                    style="object-fit: cover; display:block; margin: 0 auto; border: 3px solid;">
                                @else
                                <img id="imagePreview" src="{{ asset('assets/dist/img/user2-160x160.jpg') }}"
                                    class="rounded-circle shadow border-secondary"
                                    width="120" height="120"
                                    style="object-fit: cover; display:block; margin: 0 auto; border: 3px solid;">
                                @endif
                                <div class="d-flex justify-content-center mt-3">
                                    <div class="custom-file" style="max-width: 300px;">
                                        <input type="file" name="image" id="imageInput"
                                            class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                            accept="image/*">
                                        <label class="custom-file-label text-right" for="imageInput">اختر صورة جديدة</label>
                                    </div>
                                </div>
                                @include('admin.errors.errors', ['value' => 'image'])
                            </div>
                        </div>
                    </div>

                    <!-- الاسم الكامل -->
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
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-warning border-bottom pb-2">
                            <i class="fas fa-lock"></i>
                            أمن الحساب وتغيير كلمة المرور <small class="text-muted" style="font-size:13px;">(اترك الحقول فارغة لتجنب التغيير)</small>
                        </h5>
                    </div>

                    <!-- كلمة المرور الحالية -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كلمة المرور الحالية</label>
                            <input type="password" name="current_password"
                                class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}"
                                placeholder="أدخل كلمة المرور الحالية لتأكيد التغيير">
                            @include('admin.errors.errors', ['value' => 'current_password'])
                        </div>
                    </div>

                    <!-- كلمة المرور الجديدة -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كلمة المرور الجديدة</label>
                            <input type="password" name="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="أدخل كلمة المرور الجديدة (8 خانات كحد أدنى)">
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

                    <!-- البيانات الشخصية -->
                    <div class="col-12 mt-4">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            <i class="fas fa-id-card"></i>
                            البيانات الشخصية والإضافية
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
                                placeholder="أدخل العنوان التفصيلي">
                            @include('admin.errors.errors', ['value' => 'address'])
                        </div>
                    </div>

                    <!-- نبذة شخصية -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>نبذة شخصية</label>
                            <textarea name="bio" rows="3"
                                class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}"
                                placeholder="اكتب نبذة شخصية مختصرة عنك...">{{ old('bio', $admin->bio) }}</textarea>
                            @include('admin.errors.errors', ['value' => 'bio'])
                        </div>
                    </div>

                </div>

            </div>

            <!-- Footer buttons -->
            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-5">
                    <i class="fas fa-save mr-1"></i>
                    حفظ التغييرات
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger shadow px-5 mr-2">
                    <i class="fas fa-times-circle mr-1"></i>
                    إلغاء
                </a>
            </div>

        </form>

    </div>

</div>
@endsection

@section('js')
<script>
    // Live image upload preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('imagePreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
            
            // Update label to filename
            let fileName = file.name;
            let nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        }
    });
</script>
@endsection

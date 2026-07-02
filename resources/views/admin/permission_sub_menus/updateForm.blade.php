<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-list-ul"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">تعديل قائمة فرعية</span>
                    <span class="info-box-number">{{ $submenu->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                @if($submenu->is_active == 1)
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
                    <span class="info-box-number">{{ $submenu->is_active == 1 ? 'مفعل' : 'معطل' }}</span>
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
                <i class="fas fa-user-edit"></i>
                تعديل بيانات القائمة الفرعية
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission-sub-menus.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.permission-sub-menus.update', $submenu->id) }}" method="POST">
            @csrf
            @method('PUT')
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
                            <i class="fas fa-list-ul"></i>
                            بيانات القائمة
                        </h5>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>القائمة الرئيسية <span class="text-danger">*</span></label>
                            <select name="permission_main_menu_id" class="form-control {{ $errors->has('permission_main_menu_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر القائمة الرئيسية</option>
                                @foreach($mainMenus as $menu)
                                    <option value="{{ $menu->id }}" {{ old('permission_main_menu_id', $submenu->permission_main_menu_id) == $menu->id ? 'selected' : '' }}>{{ $menu->name }}</option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'permission_main_menu_id'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>اسم القائمة الفرعية <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $submenu->name) }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="أدخل اسم القائمة الفرعية">
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('is_active', $submenu->is_active) == 1 ? 'selected' : '' }}>مفعل</option>
                                <option value="0" {{ old('is_active', $submenu->is_active) == 0 ? 'selected' : '' }}>معطل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_active'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>
                <a href="{{ route('admin.permission-sub-menus.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

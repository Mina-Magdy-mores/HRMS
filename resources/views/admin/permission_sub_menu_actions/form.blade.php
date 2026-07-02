<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-running"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إضافة حركات جديدة</span>
                    <span class="info-box-number">إضافة جماعية</span>
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
                إضافة حركات جديدة للقائمة الفرعية (جماعي)
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission-sub-menu-actions.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.permission-sub-menu-actions.store') }}" method="POST">
            @csrf
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
                        <h5 class="mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-list-ul"></i>
                            تحديد القائمة والعمليات المطلوبة
                        </h5>
                    </div>

                    <!-- القائمة الفرعية -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">القائمة الفرعية التابعة <span class="text-danger">*</span></label>
                            <select name="permission_sub_menu_id" class="form-control {{ $errors->has('permission_sub_menu_id') ? 'is-invalid' : '' }}">
                                <option value="">اختر القائمة الفرعية</option>
                                @foreach($subMenus as $subMenu)
                                    <option value="{{ $subMenu->id }}" {{ old('permission_sub_menu_id') == $subMenu->id ? 'selected' : '' }}>
                                        {{ $subMenu->mainMenu ? $subMenu->mainMenu->name : '' }} &raquo; {{ $subMenu->name }}
                                    </option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'permission_sub_menu_id'])
                        </div>
                    </div>

                    <!-- الحالة -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">الحالة الافتراضية للحركات <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>مفعل</option>
                                <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>معطل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_active'])
                        </div>
                    </div>

                    <!-- اختيار حركات متعددة -->
                    <div class="col-12 mb-4">
                        <div class="card card-outline card-info shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="card-title font-weight-bold text-dark mb-0">
                                    <i class="fas fa-check-square text-info mr-1"></i>
                                    اختر العمليات الشائعة (يمكنك تحديد عناصر متعددة)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $standardActions = [
                                            'عرض', 
                                            'إضافة', 
                                            'تعديل', 
                                            'حذف', 
                                            'طباعة', 
                                            'أرشيف', 
                                            'تمييز', 
                                            'رفع بصمة', 
                                            'تعديل حركة يوم', 
                                            'صرف السلفة', 
                                            'دفع قسط', 
                                            'أرشفة وإغلاق الشهر بالكامل لكافة الموظفين', 
                                            'فتح الشهر المالي'
                                        ];
                                    @endphp
                                    @foreach($standardActions as $index => $stdAction)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                            <div class="custom-control custom-checkbox p-2 border rounded bg-white shadow-xs">
                                                <input type="checkbox" name="names[]" value="{{ $stdAction }}" id="std_{{ $index }}" class="custom-control-input"
                                                    {{ is_array(old('names')) && in_array($stdAction, old('names')) ? 'checked' : '' }}>
                                                <label class="custom-control-label text-secondary w-100 cursor-pointer" for="std_{{ $index }}">
                                                    {{ $stdAction }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- حركات مخصصة -->
                    <div class="col-12 mb-3">
                        <div class="form-group">
                            <label class="font-weight-bold">حركات إضافية مخصصة (تفصل بين كل حركة بفاصلة " , ")</label>
                            <textarea name="custom_names" rows="3" class="form-control {{ $errors->has('custom_names') ? 'is-invalid' : '' }}" 
                                      placeholder="مثال: تحديث أرصدة, إضافة بدلات ثابته, تعديل بدلات ثابته...">{{ old('custom_names') }}</textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> يمكنك كتابة أي حركات مخصصة أخرى غير مسجلة بالأعلى والفصل بينها بعلامة الفاصلة.
                            </small>
                            @include('admin.errors.errors', ['value' => 'custom_names'])
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>
                <a href="{{ route('admin.permission-sub-menu-actions.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row">
        <!-- (هذا الجزء للعرض فقط كما هو في كودك) -->
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">اسم الشركة</span>
                    <span class="info-box-number">{{ $general_settings->company_name }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success"><i class="fas fa-phone"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">الهاتف</span>
                    <span class="info-box-number">{{ $general_settings->phone }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">تاريخ الانشاء</span>
                    <span class="info-box-number">{{ $general_settings->created_at }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger"><i class="fas fa-toggle-on"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">حالة النظام</span>
                    <span class="info-box-number">{{ $general_settings->status == 1 ? 'مفعل' : 'معطل' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-cogs"></i>
                بيانات الضبط العام للنظام
            </h3>
            <div class="card-tools">
                <!-- زر التعديل الذي يقوم بتفعيل الحقول -->
                <button type="button" id="edit_enable_btn" class="btn btn-primary btn-sm" onclick="makeEditable()">
                    <i class="fas fa-edit"></i> تعديل البيانات
                </button>
                <a href="{{ route('admin.general-settings') }}"
                    class="btn btn-sm btn-secondary shadow-sm d-none back-btn">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- عرض رسائل الخطأ إن وجدت -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading"><i class="fas fa-exclamation-circle"></i> خطأ في البيانات</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- الفورم يوجه لروت التحديث الذي ستنشئه -->
            <form action="{{ route('admin.general-settings.update', $general_settings) }}" method="POST"
                enctype="multipart/form-data" id="settings_form">
                @csrf
                @method('PUT')
                <!-- بيانات الشركة -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-primary"><i class="fas fa-building"></i> بيانات الشركة</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>اسم الشركة</label>
                            <input type="text" name="company_name"
                                class="form-control editable-input {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                value="{{ old('company_name', $general_settings->company_name) }}" readonly>
                            @include('admin.errors.errors', ['value' => 'company_name'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>حالة النظام</label>
                            <select name="status"
                                class="form-control editable-input {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                disabled>
                                <option value="1"
                                    {{ old('status', $general_settings->status) == 1 ? 'selected' : '' }}>
                                    مفعل</option>
                                <option value="0"
                                    {{ old('status', $general_settings->status) == 0 ? 'selected' : '' }}>
                                    معطل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'status'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="text" name="email"
                                class="form-control editable-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                value="{{ old('email', $general_settings->email) }}" readonly>
                            @include('admin.errors.errors', ['value' => 'email'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رقم الهاتف</label>
                            <input type="text" name="phone"
                                class="form-control editable-input {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                value="{{ old('phone', $general_settings->phone) }}" readonly>
                            @include('admin.errors.errors', ['value' => 'phone'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="address"
                                class="form-control editable-input {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                value="{{ old('address', $general_settings->address) }}" readonly>
                            @include('admin.errors.errors', ['value' => 'address'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رابط الصورة</label>
                            <input type="file" name="image"
                                class="form-control editable-input {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                readonly>
                            @if ($general_settings->image)
                                <small class="form-text text-muted">
                                    الصورة الحالية: <a href="{{ asset('storage/' . $general_settings->image) }}"
                                        target="_blank">عرض الصورة</a> - <a
                                        href="{{ route('admin.general-settings.downloadImage', ['id' => $general_settings->id]) }}"
                                        target="_blank">تحميل الصورة</a>
                                </small>
                            @endif
                            @include('admin.errors.errors', ['value' => 'image'])
                        </div>
                    </div>
                </div>

                <hr>

                <!-- إعدادات الحضور والانصراف -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-success"><i class="fas fa-user-clock"></i> إعدادات الحضور والانصراف</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>دقائق التأخير المسموحة</label>
                            <input type="number " name="after_minute_calculate_delay"
                                class="form-control editable-input {{ $errors->has('after_minute_calculate_delay') ? 'is-invalid' : '' }}"
                                value="{{ old('after_minute_calculate_delay', $general_settings->after_minute_calculate_delay) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'after_minute_calculate_delay'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>دقائق الانصراف المبكر المسموحة</label>
                            <input type="number " name="after_minute_calculate_early_departure"
                                class="form-control editable-input {{ $errors->has('after_minute_calculate_early_departure') ? 'is-invalid' : '' }}"
                                value="{{ old('after_minute_calculate_early_departure', $general_settings->after_minute_calculate_early_departure) }}"
                                readonly>
                            @include('admin.errors.errors', [
                                'value' => 'after_minute_calculate_early_departure',
                            ])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ربع يوم خصم بعد (عدد مرات)</label>
                            <input type="number " name="after_minute_quarter_day_cut"
                                class="form-control editable-input {{ $errors->has('after_minute_quarter_day_cut') ? 'is-invalid' : '' }}"
                                value="{{ old('after_minute_quarter_day_cut', $general_settings->after_minute_quarter_day_cut) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'after_minute_quarter_day_cut'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>نصف يوم خصم بعد (عدد مرات)</label>
                            <input type="number " name="after_days_half_day_cut"
                                class="form-control editable-input {{ $errors->has('after_days_half_day_cut') ? 'is-invalid' : '' }}"
                                value="{{ old('after_days_half_day_cut', $general_settings->after_days_half_day_cut) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'after_days_half_day_cut'])
                        </div>
                    </div>
                    <div class="col-md-4 ">
                        <div class="form-group">
                            <label>يوم كامل خصم بعد (عدد مرات)</label>
                            <input type="number " name="after_days_allday_day_cut"
                                class="form-control editable-input {{ $errors->has('after_days_allday_day_cut') ? 'is-invalid' : '' }}"
                                value="{{ old('after_days_allday_day_cut', $general_settings->after_days_allday_day_cut) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'after_days_allday_day_cut'])
                        </div>
                    </div>
                </div>

                <hr>

                <!-- إعدادات الإجازات -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-warning"><i class="fas fa-plane-departure"></i> إعدادات الإجازات</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رصيد الإجازات الشهري</label>
                            <input type="number " name="monthly_vacation_balance"
                                class="form-control editable-input {{ $errors->has('monthly_vacation_balance') ? 'is-invalid' : '' }}"
                                value="{{ old('monthly_vacation_balance', $general_settings->monthly_vacation_balance) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'monthly_vacation_balance'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>نزول الرصيد بعد (يوم)</label>
                            <input type="number " name="after_days_begin_vacation"
                                class="form-control editable-input {{ $errors->has('after_days_begin_vacation') ? 'is-invalid' : '' }}"
                                value="{{ old('after_days_begin_vacation', $general_settings->after_days_begin_vacation) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'after_days_begin_vacation'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الرصيد الأولي</label>
                            <input type="number " name="first_balance_begin_vacation"
                                class="form-control editable-input {{ $errors->has('first_balance_begin_vacation') ? 'is-invalid' : '' }}"
                                value="{{ old('first_balance_begin_vacation', $general_settings->first_balance_begin_vacation) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'first_balance_begin_vacation'])
                        </div>
                    </div>
                </div>

                <hr>

                <!-- جزاءات الغياب -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-danger"><i class="fas fa-exclamation-triangle"></i> جزاءات الغياب</h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>قيمه خصم الايام بعد اول مرة غياب</label> </label>
                            <input type="number " name="sanctions_value_first_absence"
                                class="form-control editable-input {{ $errors->has('sanctions_value_first_absence') ? 'is-invalid' : '' }}"
                                value="{{ old('sanctions_value_first_absence', $general_settings->sanctions_value_first_absence) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'sanctions_value_first_absence'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>قيمه خصم الايام بعد ثاني مرة غياب</label>
                            <input type="number " name="sanctions_value_second_absence"
                                class="form-control editable-input {{ $errors->has('sanctions_value_second_absence') ? 'is-invalid' : '' }}"
                                value="{{ old('sanctions_value_second_absence', $general_settings->sanctions_value_second_absence) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'sanctions_value_second_absence'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>قيمه خصم الايام بعد ثالث مرة غياب</label>
                            <input type="number " name="sanctions_value_third_absence"
                                class="form-control editable-input {{ $errors->has('sanctions_value_third_absence') ? 'is-invalid' : '' }}"
                                value="{{ old('sanctions_value_third_absence', $general_settings->sanctions_value_third_absence) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'sanctions_value_third_absence'])
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>قيمه خصم الايام بعد رابع مرة غياب</label>
                            <input type="number " name="sanctions_value_fourth_absence"
                                class="form-control editable-input {{ $errors->has('sanctions_value_fourth_absence') ? 'is-invalid' : '' }}"
                                value="{{ old('sanctions_value_fourth_absence', $general_settings->sanctions_value_fourth_absence) }}"
                                readonly>
                            @include('admin.errors.errors', ['value' => 'sanctions_value_fourth_absence'])
                        </div>
                    </div>
                </div>

                <hr>



                <!-- معلومات إضافية -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-info">
                            <i class="fas fa-info-circle"></i>
                            معلومات إضافية
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>كود الشركه</label>
                            <span class="form-control bg-secondary ">{{ $general_settings->company_id }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تم الإنشاء بواسطة</label>
                            <span class="form-control bg-secondary ">{{ $general_settings->created_by }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>آخر تحديث بواسطة</label>
                            <span class="form-control bg-secondary ">{{ $general_settings->updated_by }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تاريخ آخر تحديث</label>
                            <span class="form-control bg-secondary ">{{ $general_settings->updated_at }}</span>
                        </div>
                    </div>
                </div>
                <!-- أزرار التحكم تظهر فقط عند الضغط على تعديل -->
                <div id="action_buttons" class="text-start mt-4  " style="display: none;">
                    <button type="submit" class="btn btn-success px-3 shadow">حفظ التعديلات</button>
                    <button type="button" class="btn btn-danger px-3 shadow"
                        onclick="location.reload()">إلغاء</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- JavaScript للتحكم في وضع التعديل -->

@section('js')
    <script>
        // Check if there are validation errors - if yes, enable edit mode automatically
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                makeEditable();
            });
        @endif

        function makeEditable() {
            // 1. فك القفل عن كل المدخلات
            const inputs = document.querySelectorAll('.editable-input');
            inputs.forEach(input => {
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
                input.style.border = "1px solid #28a745";
                input.style.backgroundColor = "#fff";
            });

            // 2. إظهار أزرار الحفظ والإلغاء
            document.getElementById('action_buttons').style.display = 'block';

            // 3. إخفاء زر التعديل العلوي
            document.getElementById('edit_enable_btn').style.display = 'none';

            // 4. تركيز المؤشر على أول حقل
            if (inputs.length > 0) inputs[0].focus();

            // 5. تغيير عنوان الصفحة
            document.title = 'تعديل البيانات';

            // 6. تعديل  edit-header
            const editHeader = document.querySelector('.edit-header');

            if (editHeader) {
                editHeader.textContent = 'تعديل';
            }
            // 7. اظهار زر الرجوع

            document.querySelector('.back-btn').classList.remove('d-none');
        }
    </script>
@endsection

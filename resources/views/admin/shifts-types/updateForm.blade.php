<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-edit"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">تعديل نوع شفت</span>
                    <span class="info-box-number">{{ $shiftsType->type == 1 ? 'شفت نهاري' : ($shiftsType->type == 2 ?
                        'شفت ليلي' : 'نوع غير معروف') }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                @if($shiftsType->status == 1)
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                @else
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                @endif
                <div class="info-box-content">
                    <span class="info-box-text">حالة الصفحة</span>
                    <span class="info-box-number">{{ $shiftsType->status == 1 ? 'مفعل' : 'معطل' }}</span>
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
                    <i class="fas fa-building"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">كود الشركه</span>
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
                تعديل نوع شفت
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.shifts-types.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.shifts-types.update', $shiftsType->id) }}" method="POST">
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
                            <i class="fas fa-calendar-alt"></i>
                            بيانات نوع الشفت
                        </h5>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>نوع الشفت</label>
                            <select name="type" class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                <option value="">اختر نوع الشفت</option>
                                <option value="1" {{ old('type', $shiftsType->type) == 1 ? 'selected' : '' }}>شفت نهاري
                                </option>
                                <option value="2" {{ old('type', $shiftsType->type) == 2 ? 'selected' : '' }}>شفت ليلي
                                </option>
                                <option value="3" {{ old('type', $shiftsType->type) == 3 ? 'selected' : '' }}>شفت كامل اليوم
                                </option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'type'])
                        </div>
                    </div>
                    @php
                    $start_time = date('H:i', strtotime($shiftsType->start_time));
                    $end_time = date('H:i', strtotime($shiftsType->end_time));
                    @endphp
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>وقت البداية</label>
                            <input type="time" name="start_time" value="{{ old('start_time', $start_time) }}"
                                class="form-control {{ $errors->has('start_time') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'start_time'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>وقت النهاية</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $end_time) }}"
                                class="form-control {{ $errors->has('end_time') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'end_time'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>إجمالي الساعات</label>
                            <input type="number" step="0.01" name="total_hours"
                                value="{{ old('total_hours', $shiftsType->total_hours) }}"
                                class="form-control {{ $errors->has('total_hours') ? 'is-invalid' : '' }}"
                                placeholder="0.00">
                            @include('admin.errors.errors', ['value' => 'total_hours'])
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('status', $shiftsType->status) == 1 ? 'selected' : '' }}>مفعل
                                </option>
                                <option value="0" {{ old('status', $shiftsType->status) == 0 ? 'selected' : '' }}>معطل
                                </option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'status'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>
                <a href="{{ route('admin.shifts-types.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>

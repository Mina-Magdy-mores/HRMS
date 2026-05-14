<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">

                <span class="info-box-icon bg-primary">
                    <i class="fas fa-edit"></i>
                </span>

                <div class="info-box-content">

                    <span class="info-box-text">تعديل فرع</span>

                    <span class="info-box-number">
                        {{ $branche->name }}
                    </span>

                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                @if($branche->status == 1)

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

                    <span class="info-box-number">
                        {{ $branche->status == 1 ? 'مفعل' : 'معطل' }}
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

                    <span class="info-box-text">رقم الشركة</span>

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

                تعديل بيانات فرع
            </h3>

            <div class="card-tools">

                <a href="{{ route('admin.branches.index') }}" class="btn btn-sm btn-secondary shadow-sm">

                    <i class="fas fa-arrow-right"></i>

                    رجوع

                </a>

            </div>

        </div>

        <form action="{{ route('admin.branches.update', $branche->id) }}" method="POST">

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

                <!-- بيانات السنة المالية -->
                <div class="row">

                    <div class="col-12">

                        <h5 class="mb-4 text-primary">

                            <i class="fas fa-calendar-alt"></i>

                            بيانات الفرع
                        </h5>

                    </div>

                    <!-- اسم الفرع -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>اسم الفرع</label>

                            <input type="text" name="name" value="{{ old('name', $branche->name) }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">

                            @include('admin.errors.errors', ['value' => 'name'])

                        </div>

                    </div>

                    <!-- عنوان الفرع -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>عنوان الفرع</label>

                            <input type="text" name="address" value="{{ old('address', $branche->address) }}"
                                class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}">

                            @include('admin.errors.errors', ['value' => 'address'])

                        </div>

                    </div>

                    <!-- رقم الهاتف-->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>رقم الهاتف</label>

                            <input type="text" name="phone" value="{{ old('phone', $branche->phone) }}"
                                class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'phone'])
                        </div>
                    </div>

                    <!-- البريد الالكتروني -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>البريد الالكتروني</label>
                            <input type="email" name="email" value="{{ old('email', $branche->email) }}"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'email'])
                        </div>
                    </div>

                        <!-- حالة الفرع -->
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>حالة الفرع</label>
                                <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                    <option disabled value="">اختر الحالة</option>
                                    <option value="1" {{ old('status', $branche->status) == 1 ? 'selected' : '' }}>
                                        مفعل
                                    </option>
                                    <option value="0" {{ old('status', $branche->status) == 0 ? 'selected' : '' }}>
                                        معطل
                                    </option>
                                </select>
                                @include('admin.errors.errors', ['value' => 'status'])
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

                <a href="{{ route('admin.branches.index') }}" class="btn btn-danger shadow px-4">

                    <i class="fas fa-times-circle"></i>

                    إلغاء

                </a>

            </div>

        </form>

    </div>

</div>
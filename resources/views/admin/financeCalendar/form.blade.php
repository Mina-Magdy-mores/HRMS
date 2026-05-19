<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">

                <span class="info-box-icon bg-primary">
                    <i class="fas fa-calendar-plus"></i>
                </span>

                <div class="info-box-content">

                    <span class="info-box-text">إضافة سنة مالية</span>

                    <span class="info-box-number">
                        Finance Year
                    </span>

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

                    <span class="info-box-number">
                        إنشاء جديد
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

                    <span class="info-box-text">كود الشركه</span>

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

                إضافة سنة مالية جديدة

            </h3>

            <div class="card-tools">

                <a href="{{ route('admin.financeCalendars.index') }}" class="btn btn-sm btn-secondary shadow-sm">

                    <i class="fas fa-arrow-right"></i>

                    رجوع

                </a>

            </div>

        </div>

        <form action="{{ route('admin.financeCalendars.store') }}" method="POST">

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

                <!-- بيانات السنة المالية -->
                <div class="row">

                    <div class="col-12">

                        <h5 class="mb-4 text-primary">

                            <i class="fas fa-calendar-alt"></i>

                            بيانات السنة المالية

                        </h5>

                    </div>

                    <!-- السنة المالية -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>كود السنة المالية</label>

                            <input type="number" name="finance_yr" value="{{ old('finance_yr') }}"
                                class="form-control {{ $errors->has('finance_yr') ? 'is-invalid' : '' }}"
                                placeholder="مثال : 2026">

                            @include('admin.errors.errors', ['value' => 'finance_yr'])

                        </div>

                    </div>

                    <!-- الوصف -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>وصف السنة المالية</label>

                            <input type="text" name="finance_yr_desc" value="{{ old('finance_yr_desc') }}"
                                class="form-control {{ $errors->has('finance_yr_desc') ? 'is-invalid' : '' }}"
                                placeholder="أدخل وصف السنة المالية">

                            @include('admin.errors.errors', ['value' => 'finance_yr_desc'])

                        </div>

                    </div>

                    <!-- تاريخ البداية -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>تاريخ البداية</label>

                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}">

                            @include('admin.errors.errors', ['value' => 'start_date'])

                        </div>

                    </div>

                    <!-- تاريخ النهاية -->
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>تاريخ النهاية</label>

                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}">

                            @include('admin.errors.errors', ['value' => 'end_date'])

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

                <a href="{{ route('admin.financeCalendars.index') }}" class="btn btn-danger shadow px-4">

                    <i class="fas fa-times-circle"></i>

                    إلغاء

                </a>

            </div>

        </form>

    </div>

</div>
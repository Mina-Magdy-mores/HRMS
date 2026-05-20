<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-map"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إضافة محافظة جديدة</span>
                    <span class="info-box-number">محافظة</span>
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
                إضافة محافظة جديدة
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.governorates.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.governorates.store') }}" method="POST">
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
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-map"></i>
                            بيانات المحافظة
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الدولة</label>
                            <select name="country_id"
                                class="form-control select2 {{ $errors->has('country_id') ? 'is-invalid' : '' }}">
                                <option value="">-- اختر الدولة --</option>
                                @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id')==$country->id ? 'selected' : ''
                                    }}>
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'country_id'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الإسم</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('status')==1 ? 'selected' : '' }}>مفعل</option>
                                <option value="0" {{ old('status')==0 ? 'selected' : '' }}>معطل</option>
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
                <a href="{{ route('admin.governorates.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>

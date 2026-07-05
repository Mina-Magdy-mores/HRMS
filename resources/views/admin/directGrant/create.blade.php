@extends('admin.layouts.admin')

@section('title', 'تسجيل منحة مباشرة')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('contentHeader')
    <i class="fas fa-gift text-primary"></i> تسجيل منحة مباشرة
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.direct-grants.index') }}">المنح المالية المباشرة</a>
@endsection
@section('contentHeaderActive', 'تسجيل')

@section('content')
<div class="container-fluid">
    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-white">
            <h3 class="card-title font-weight-bold text-dark mt-1">
                <i class="fas fa-plus-circle mr-1 text-primary"></i> تسجيل منحة مالية مباشرة جديدة خارج المرتب
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.direct-grants.index') }}" class="btn btn-sm btn-secondary shadow-sm font-weight-bold">
                    <i class="fas fa-arrow-right mr-1"></i> رجوع للكل
                </a>
            </div>
        </div>

        <form action="{{ route('admin.direct-grants.store') }}" method="POST">
            @csrf
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <h6 class="font-weight-bold"><i class="fas fa-exclamation-circle mr-1"></i> يرجى تصحيح الأخطاء التالية:</h6>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">الموظف <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror">
                            <option value="">اختر الموظف...</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 form-group">
                        <label class="font-weight-bold">الشهر المالي المفتوح حالياً <span class="text-danger">*</span></label>
                        <input type="text" class="form-control font-weight-bold text-success bg-light" readonly 
                               value="سنة {{ $activeMonth->finance_yr }} - شهر {{ optional($activeMonth->month)->name }}">
                        <input type="hidden" name="finance_monthly_calendar_id" value="{{ $activeMonth->id }}">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold">نوع المنحة <span class="text-danger">*</span></label>
                        <select name="salary_grant_type_id" id="salary_grant_type_id" class="form-control select2 @error('salary_grant_type_id') is-invalid @enderror">
                            <option value="">اختر النوع...</option>
                            @foreach ($grantTypes as $type)
                                <option value="{{ $type->id }}" {{ old('salary_grant_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('salary_grant_type_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold">المبلغ المالي <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}"
                                   class="form-control @error('amount') is-invalid @enderror" placeholder="0.00">
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold bg-light">ج.م</span>
                            </div>
                        </div>
                        @error('amount')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold">تاريخ الصرف والتحويل <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                               class="form-control @error('payment_date') is-invalid @enderror">
                        @error('payment_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-8 form-group">
                        <label class="font-weight-bold">ملاحظات إضافية</label>
                        <textarea name="notes" rows="2" class="form-control" placeholder="اكتب أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-md-4 form-group">
                        <label class="font-weight-bold">الحالة <span class="text-danger">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>معطل</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light text-left">
                <button type="submit" class="btn btn-success shadow px-4 font-weight-bold">
                    <i class="fas fa-save mr-1"></i> حفظ المنحة
                </button>
                <a href="{{ route('admin.direct-grants.index') }}" class="btn btn-secondary shadow px-4 font-weight-bold">
                    <i class="fas fa-times-circle mr-1"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
@endsection

@extends('admin.layouts.admin')

@section('title', 'تعديل نوع الطلب')
@section('contentHeader')
    <i class="fas fa-edit text-primary"></i>
    تعديل نوع الطلب
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.employee-request-types.index') }}">أنواع الطلبات</a>
@endsection
@section('contentHeaderActive', 'تعديل نوع الطلب')

@section('content')
<div class="container-fluid">

    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-white">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-edit"></i>
                تعديل بيانات نوع الطلب: {{ $type->name }}
            </h3>
        </div>

        <form action="{{ route('admin.employee-request-types.update', $type->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                @include('admin.includes.alerts.success')
                @include('admin.includes.alerts.error')

                <div class="row">
                    <!-- اسم نوع الطلب -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">اسم نوع الطلب <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $type->name) }}"
                                   class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   placeholder="أدخل اسم نوع الطلب" required>
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <!-- حالة التفعيل -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_active">حالة التفعيل <span class="text-danger">*</span></label>
                            <select name="is_active" id="is_active" class="form-control {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('is_active', $type->is_active) == 1 ? 'selected' : '' }}>نشط ومفعّل</option>
                                <option value="0" {{ old('is_active', $type->is_active) == 0 ? 'selected' : '' }}>غير نشط / معطّل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_active'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left bg-white">
                <button type="submit" class="btn btn-warning shadow text-white px-4">
                    <i class="fas fa-save"></i> تحديث البيانات
                </button>
                <a href="{{ route('admin.employee-request-types.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

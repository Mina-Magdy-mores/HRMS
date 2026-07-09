@extends('admin.layouts.admin')

@section('title', 'تقديم طلب جديد')
@section('contentHeader')
    <i class="fas fa-plus-circle text-primary"></i>
    تقديم طلب جديد
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.employee-requests.index') }}">طلبات الموظفين</a>
@endsection
@section('contentHeaderActive', 'تقديم طلب')

@section('content')
<div class="container-fluid">

    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-white">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-file-alt"></i>
                استمارة تقديم الطلب
            </h3>
        </div>

        <form action="{{ route('admin.employee-requests.store') }}" method="POST">
            @csrf
            
            <div class="card-body">
                @include('admin.includes.alerts.success')
                @include('admin.includes.alerts.error')

                <div class="row">
                    <!-- Admin Mode: Select Employee -->
                    @if(auth()->user()->is_employee == 0)
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="employee_id">الموظف صاحب الطلب <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-control select2 {{ $errors->has('employee_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">-- اختر الموظف --</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} (كود: {{ $emp->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @include('admin.errors.errors', ['value' => 'employee_id'])
                            </div>
                        </div>
                    @endif

                    <!-- Select Request Type -->
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="employee_request_type_id">نوع الطلب <span class="text-danger">*</span></label>
                            <select name="employee_request_type_id" id="employee_request_type_id" class="form-control {{ $errors->has('employee_request_type_id') ? 'is-invalid' : '' }}" required>
                                <option value="">-- اختر نوع الطلب --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('employee_request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'employee_request_type_id'])
                        </div>
                    </div>

                    <!-- Request Title -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="title">عنوان الطلب <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" 
                                   class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" 
                                   placeholder="أدخل عنواناً واضحاً للطلب (مثال: طلب إجازة طارئة لظروف عائلية)" required>
                            @include('admin.errors.errors', ['value' => 'title'])
                        </div>
                    </div>

                    <!-- Request Content -->
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="content">محتوى وتفاصيل الطلب <span class="text-danger">*</span></label>
                            <textarea name="content" id="content" rows="6" 
                                      class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}" 
                                      placeholder="اكتب تفاصيل طلبك بدقة، والتواريخ المطلوبة أو المبررات هنا..." required>{{ old('content') }}</textarea>
                            @include('admin.errors.errors', ['value' => 'content'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left bg-white">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i> تقديم الطلب
                </button>
                <a href="{{ route('admin.employee-requests.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i> إلغاء
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

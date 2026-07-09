@extends('admin.layouts.admin')

@section('title', 'تعديل المهمة')
@section('contentHeader')
    <i class="fas fa-tasks"></i>
    مهام الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employee-tasks.index') }}">مهام الموظفين</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-tasks"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">تعديل المهمة</span>
                    <span class="info-box-number">مهمة #{{ $task->id }}</span>
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
                    <span class="info-box-number">تحديث البيانات</span>
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
                <i class="fas fa-edit"></i>
                تعديل بيانات المهمة
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.employee-tasks.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.employee-tasks.update', $task->id) }}" method="POST">
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
                            <i class="fas fa-tasks"></i>
                            تفاصيل المهمة
                        </h5>
                    </div>

                    <!-- الموظف -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>اختر الموظف <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-control select2 {{ $errors->has('employee_id') ? 'is-invalid' : '' }}">
                                <option value="">-- اختر الموظف --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id', $task->employee_id) == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }} (كود: {{ $employee->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                            @include('admin.errors.errors', ['value' => 'employee_id'])
                        </div>
                    </div>

                    <!-- عنوان المهمة -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>عنوان المهمة <span class="text-danger">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $task->title) }}"
                                class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                placeholder="أدخل عنوان المهمة المختصر">
                            @include('admin.errors.errors', ['value' => 'title'])
                        </div>
                    </div>

                    <!-- محتوى المهمة -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>محتوى/تفاصيل المهمة <span class="text-danger">*</span></label>
                            <textarea name="content" rows="4"
                                class="form-control {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                placeholder="أدخل تفاصيل ومحتوى المهمة بالكامل">{{ old('content', $task->content) }}</textarea>
                            @include('admin.errors.errors', ['value' => 'content'])
                        </div>
                    </div>

                    <!-- حالة الإنجاز -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="is_completed" class="form-control {{ $errors->has('is_completed') ? 'is-invalid' : '' }}">
                                <option value="0" {{ old('is_completed', $task->is_completed) == 0 ? 'selected' : '' }}>لم تبدأ</option>
                                <option value="1" {{ old('is_completed', $task->is_completed) == 1 ? 'selected' : '' }}>قيد العمل</option>
                                <option value="2" {{ old('is_completed', $task->is_completed) == 2 ? 'selected' : '' }}>منتهية ومكتملة</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_completed'])
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ملاحظات إضافية</label>
                            <textarea name="notes" rows="2"
                                class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                placeholder="أي ملاحظات إضافية...">{{ old('notes', $task->notes) }}</textarea>
                            @include('admin.errors.errors', ['value' => 'notes'])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    تحديث البيانات
                </button>
                <a href="{{ route('admin.employee-tasks.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

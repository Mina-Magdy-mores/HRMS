@extends('admin.layouts.admin')

@section('title', 'مهام الموظفين')
@section('contentHeader')
    <i class="fas fa-tasks"></i>
    مهام الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employee-tasks.index') }}">مهام الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
<div class="container-fluid">

    <!-- Info Boxes / Summary -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-secondary">
                    <i class="fas fa-play"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">مهام لم تبدأ</span>
                    <span class="info-box-number">
                        {{ $notStartedCount }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-hourglass-half"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">مهام قيد العمل</span>
                    <span class="info-box-number">
                        {{ $inProgressCount }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-double"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">مهام مكتملة</span>
                    <span class="info-box-number">
                        {{ $completedCount }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-plus-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي المهام المعروضة</span>
                    <span class="info-box-number">{{ $tasks->total() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter"></i>
                فلاتر البحث والفرز
            </h3>
            
            <div class="card-tools">
                @if(check_permission('مهام الموظفين', 'إضافة'))
                <a href="{{ route('admin.employee-tasks.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة مهمة جديدة
                </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <form action="{{ route('admin.employee-tasks.index') }}" method="GET" class="mb-4">
                <!-- Keep archived tab state -->
                <input type="hidden" name="show_archived" value="{{ $showArchived }}">
                
                <div class="row">
                    <!-- Filter Employee -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تصفية حسب الموظف</label>
                            <select name="employee_id" class="form-control select2">
                                <option value="">-- كل الموظفين --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Filter Status -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>تصفية حسب حالة الإنجاز</label>
                            <select name="is_completed" class="form-control">
                                <option value="">-- كل الحالات --</option>
                                <option value="0" {{ $isCompleted === '0' ? 'selected' : '' }}>لم تبدأ</option>
                                <option value="1" {{ $isCompleted === '1' ? 'selected' : '' }}>قيد العمل</option>
                                <option value="2" {{ $isCompleted === '2' ? 'selected' : '' }}>منتهية ومكتملة</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary btn-block shadow-sm">
                                <i class="fas fa-search"></i>
                                تطبيق الفلترة
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Navigation Tabs for Active vs Archived -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="btn-group w-100 shadow-sm" role="group">
                        <a href="{{ route('admin.employee-tasks.index', ['show_archived' => 0, 'employee_id' => $employeeId, 'is_completed' => $isCompleted]) }}" 
                           class="btn {{ $showArchived == 0 ? 'btn-primary active font-weight-bold' : 'btn-outline-primary' }} w-50">
                            <i class="fas fa-folder-open mr-1"></i>
                            المهام النشطة الجارية
                        </a>
                        <a href="{{ route('admin.employee-tasks.index', ['show_archived' => 1, 'employee_id' => $employeeId, 'is_completed' => $isCompleted]) }}" 
                           class="btn {{ $showArchived == 1 ? 'btn-primary active font-weight-bold' : 'btn-outline-primary' }} w-50">
                            <i class="fas fa-archive mr-1"></i>
                            المهام مؤرشفة
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table section -->
            @include('admin.employeeTasks.table')

        </div>
    </div>

</div>
@endsection

@extends('admin.layouts.admin')

@section('title', 'طلبات الموظفين')
@section('contentHeader')
    <i class="fas fa-file-signature text-primary"></i>
    طلبات الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employee-requests.index') }}">طلبات الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض الكل')

@section('content')
<div class="container-fluid">

    <!-- Active vs Archived Tabs -->
    <div class="card card-primary card-outline shadow mb-4">
        <div class="card-body p-2">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ $isArchived == 0 ? 'active' : '' }}" href="{{ route('admin.employee-requests.index', ['is_archived' => 0]) }}">
                        <i class="fas fa-folder-open"></i> الطلبات الحالية والنشطة
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $isArchived == 1 ? 'active' : '' }}" href="{{ route('admin.employee-requests.index', ['is_archived' => 1]) }}">
                        <i class="fas fa-archive"></i> أرشيف الطلبات المنتهية
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Search Filters -->
    <div class="card shadow-sm border mb-4">
        <div class="card-body">
            <form action="{{ route('admin.employee-requests.index') }}" method="GET" class="row">
                <input type="hidden" name="is_archived" value="{{ $isArchived }}">

                <!-- Search Input -->
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-group mb-0">
                        <label class="small text-muted font-weight-bold">البحث بـ (العنوان، المحتوى، اسم الموظف، كود الموظف)</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="أدخل كلمة البحث...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="form-group mb-0">
                        <label class="small text-muted font-weight-bold">حالة الطلب</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">-- كل الحالات --</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>قيد الانتظار / جديد</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>تم القبول والموافقة</option>
                            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>مرفوض</option>
                        </select>
                    </div>
                </div>

                <!-- Request Type Filter -->
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="form-group mb-0">
                        <label class="small text-muted font-weight-bold">نوع الطلب</label>
                        <select name="employee_request_type_id" class="form-control form-control-sm">
                            <option value="">-- كل الأنواع --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ request('employee_request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="col-md-2 col-sm-6 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm mr-1">
                        <i class="fas fa-search"></i> تصفية
                    </button>
                    <a href="{{ route('admin.employee-requests.index', ['is_archived' => $isArchived]) }}" class="btn btn-secondary btn-sm shadow-sm">
                        إعادة_ضبط
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Card table -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header bg-white">
            <h3 class="card-title text-primary font-weight-bold">
                <i class="fas fa-table text-primary"></i>
                {{ $isArchived == 1 ? 'سجل أرشيف الطلبات' : 'سجل الطلبات الحالية' }}
            </h3>
            <div class="card-tools">
                @if(auth()->user()->is_master_admin || check_permission('طلبات الموظفين', 'إضافة'))
                <a href="{{ route('admin.employee-requests.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    تقديم طلب جديد
                </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            @include('admin.includes.alerts.success')
            @include('admin.includes.alerts.error')

            @include('admin.employeeRequests.table')
        </div>
    </div>

</div>
@endsection

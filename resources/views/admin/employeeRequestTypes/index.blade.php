@extends('admin.layouts.admin')

@section('title', 'أنواع طلبات الموظفين')
@section('contentHeader')
    <i class="fas fa-list-ul text-primary"></i>
    أنواع طلبات الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employee-request-types.index') }}">أنواع الطلبات</a>
@endsection
@section('contentHeaderActive', 'عرض الكل')

@section('content')
<div class="container-fluid">

    <!-- Info Box statistics -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-list-ul"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الأنواع</span>
                    <span class="info-box-number">{{ $types->total() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">نشط</span>
                    <span class="info-box-number">{{ $types->where('is_active', 1)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">غير نشط</span>
                    <span class="info-box-number">{{ $types->where('is_active', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول أنواع طلبات الموظفين
            </h3>
            <div class="card-tools">
                @if(auth()->user()->is_master_admin || check_permission('أنواع طلبات الموظفين', 'إضافة'))
                <a href="{{ route('admin.employee-request-types.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة نوع طلب جديد
                </a>
                @endif
            </div>
        </div>

        <div class="card-body">
            @include('admin.includes.alerts.success')
            @include('admin.includes.alerts.error')

            @include('admin.employeeRequestTypes.table')
        </div>
    </div>

</div>
@endsection

@extends('admin.layouts.admin')

@section('title', 'تسويات رواتب الموظفين المؤرشفة')
@section('contentHeader')
    <i class="fas fa-balance-scale"></i>
    تسويات رواتب الموظفين المؤرشفة
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-settlements.index') }}">تسويات الرواتب المؤرشفة</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordSettlement.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

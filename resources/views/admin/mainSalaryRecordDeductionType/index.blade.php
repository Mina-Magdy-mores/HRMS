@extends('admin.layouts.admin')

@section('title', 'خصومات الموظفين المالية')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    خصومات الموظفين المالية
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-deduction-types.index') }}">خصومات الموظفين المالية</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordDeductionType.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

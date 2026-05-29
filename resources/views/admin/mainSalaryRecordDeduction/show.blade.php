@extends('admin.layouts.admin')

@section('title', 'تفاصيل جزاءات الموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-calendar-alt"></i>
    تفاصيل جزاءات الموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-deductions.index') }}">جزاءات الموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordDeduction.show-table', [
    'financeMonthlyCalendar' => $financeMonthlyCalendar,
    'mainSalaryEmployeeDeductions' => $mainSalaryEmployeeDeductions
])

@endsection

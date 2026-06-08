@extends('admin.layouts.admin')

@section('title', 'الراتب الأساسي للموظف')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    الراتب الأساسي للموظف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee.index') }}">الراتب الأساسي للموظف</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryEmployee.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection


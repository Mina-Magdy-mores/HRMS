@extends('admin.layouts.admin')

@section('title', 'بدلات الموظفين')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    بدلات الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-allowances.index') }}">بدلات الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordAllowance.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

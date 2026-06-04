@extends('admin.layouts.admin')

@section('title', 'السلف الشهرية للموظفين')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    السلف الشهرية للموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-loans.index') }}">السلف الشهرية</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordLoan.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection



@extends('admin.layouts.admin')

@section('title', 'التحقيقات الإدارية')
@section('contentHeader')
    <i class="fas fa-search"></i>
    التحقيقات الإدارية
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-investigations.index') }}">التحقيقات الإدارية</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordInvestigation.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

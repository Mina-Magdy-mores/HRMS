@extends('admin.layouts.admin')

@section('title', 'جزاءات الموظفين')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    جزاءات الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-deductions.index') }}">جزاءات الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordDeduction.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

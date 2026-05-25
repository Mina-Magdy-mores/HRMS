@extends('admin.layouts.admin')

@section('title', 'بيانات رواتب الموظفين')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    بيانات رواتب الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-records.index') }}">بيانات رواتب الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

        @include('admin.mainSalaryRecord.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])
@endsection

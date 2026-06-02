@extends('admin.layouts.admin')

@section('title', 'غياب الموظفين')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    غياب الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-absences.index') }}">غياب الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordAbsence.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection


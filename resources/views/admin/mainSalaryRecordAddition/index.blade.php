@extends('admin.layouts.admin')

@section('title', 'أضافه الأيام اليدوي للموظفين')
@section('contentHeader')
    <i class="fas fa-calendar-plus"></i>
    أضافه الأيام اليدوي للموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-additions.index') }}">أضافه الأيام اليدوي</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordAddition.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

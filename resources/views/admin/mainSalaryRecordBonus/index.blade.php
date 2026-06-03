@extends('admin.layouts.admin')

@section('title', 'المكافآت المالية للموظفين')
@section('contentHeader')
    <i class="fas fa-trophy"></i>
    المكافآت المالية للموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-bonuses.index') }}">المكافآت المالية للموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.mainSalaryRecordBonus.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

@extends('admin.layouts.admin')

@section('title', 'تفاصيل خصومات الموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    تفاصيل خصومات الموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-deduction-types.index') }}">خصومات الموظفين المالية</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordDeductionType.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

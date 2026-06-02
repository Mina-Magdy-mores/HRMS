@extends('admin.layouts.admin')

@section('title', 'تفاصيل بدلات الموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    تفاصيل بدلات الموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-allowances.index') }}">بدلات الموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordAllowance.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

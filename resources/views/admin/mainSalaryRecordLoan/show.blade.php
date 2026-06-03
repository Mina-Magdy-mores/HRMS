@extends('admin.layouts.admin')

@section('title', 'تفاصيل السلف الشهرية للموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-hand-holding-usd"></i>
    تفاصيل السلف الشهرية للموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-loans.index') }}">السلف الشهرية</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordLoan.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

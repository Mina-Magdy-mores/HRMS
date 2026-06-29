@extends('admin.layouts.admin')

@section('title', 'تفاصيل التحقيقات الإدارية للشهر')
@section('contentHeader')
    <i class="fas fa-search"></i>
    تفاصيل التحقيقات الإدارية للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-investigations.index') }}">التحقيقات الإدارية</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordInvestigation.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

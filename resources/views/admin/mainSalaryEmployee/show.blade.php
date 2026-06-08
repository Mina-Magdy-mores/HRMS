@extends('admin.layouts.admin')

@section('title', 'تفاصيل الراتب الأساسي للموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-calendar-alt"></i>
    تفاصيل الراتب الأساسي للموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee.index') }}">الراتب الأساسي للموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryEmployee.show-table')  

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@extends('admin.layouts.admin')

@section('title', 'تفاصيل أضافه الأيام اليدوي للموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-calendar-plus"></i>
    تفاصيل أضافه الأيام اليدوي للموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-additions.index') }}">أضافه الأيام اليدوي</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordAddition.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@extends('admin.layouts.admin')

@section('title', 'تفاصيل المكافآت المالية للموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-trophy"></i>
    تفاصيل المكافآت المالية للموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-bonuses.index') }}">المكافآت المالية للموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordBonus.show-table')

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

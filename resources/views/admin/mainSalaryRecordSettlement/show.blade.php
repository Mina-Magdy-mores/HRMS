@extends('admin.layouts.admin')

@section('title', 'تفاصيل تسويات رواتب الموظفين المؤرشفة')
@section('contentHeader')
    <i class="fas fa-balance-scale"></i>
    تفاصيل تسويات رواتب الموظفين المؤرشفة للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-settlements.index') }}">تسويات رواتب الموظفين المؤرشفة</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.mainSalaryRecordSettlement.show-table')  

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

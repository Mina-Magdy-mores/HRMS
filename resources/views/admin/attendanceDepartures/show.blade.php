@extends('admin.layouts.admin')

@section('title', 'تفاصيل بصمة الموظفين للشهر')
@section('contentHeader')
    <i class="fas fa-fingerprint"></i>
    تفاصيل بصمة الموظفين للشهر
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.attendanceDepartures.index') }}">بصمة الموظفين</a>
@endsection
@section('contentHeaderActive', 'تفاصيل الشهر')

@section('content')

@include('admin.attendanceDepartures.show-table')  

@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

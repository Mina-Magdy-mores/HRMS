@extends('admin.layouts.admin')

@section('title', 'البصمة')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    جهاز البصمة
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.attendanceDepartures.index') }}">بصمة الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.attendanceDepartures.table', ['financeMonthlyCalendars' => $financeMonthlyCalendars])

@endsection

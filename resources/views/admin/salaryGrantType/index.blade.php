@extends('admin.layouts.admin')

@section('title', 'أنواع منح الرواتب')
@section('contentHeader')
<i class="fas fa-gift"></i>
أنواع منح الرواتب
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.salary-grant-types.index') }}">أنواع منح الرواتب</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.salaryGrantType.table', ['salaryGrantTypes' => $salaryGrantTypes])
@endsection

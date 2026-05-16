@extends('admin.layouts.admin')

@section('title', 'HRMS')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    لوحة التحكم
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
<div class="col-12 w-100 d-flex justify-content-center align-items-center"
style="
min-height: calc(100vh - 150px);
background: url('{{ asset('assets/images/login.jpg') }}') no-repeat center center fixed;
background-size: cover;
">
     <h1 class="text-white">Dashboard Page</h1>
</div>
@endsection

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
    <div class=" p-4 w-25" style="background-color: rgba(0, 0, 0, 0.25); border-radius: 10px;">
        <div class="d-flex justify-content-center align-items-center">
            <span class="badge bg-info">HRM</span>
            <h2 class="brand-text font-weight-light text-white">HRMS V1</h2>
        </div>
        <hr>
        <p class="text-center text-white">Welcome to HRMS V1</p>
        <div class="d-flex justify-content-center align-items-center">
            <a href="{{ route('admin.general-settings') }}" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</div>
@endsection

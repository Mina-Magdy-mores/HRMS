@extends('admin.layouts.admin')

@section('title', 'تعديل بيانات مستخدم')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    المستخدمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">المستخدمين</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
    @include('admin.adminProfile.updateForm')
@endsection

@section('js')
    @stack('js')
@endsection

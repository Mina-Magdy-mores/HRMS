@extends('admin.layouts.admin')

@section('title', 'إضافة مستخدم جديد')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    المستخدمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">المستخدمين</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')
    @include('admin.adminProfile.form')
@endsection

@section('js')
    @stack('js')
@endsection

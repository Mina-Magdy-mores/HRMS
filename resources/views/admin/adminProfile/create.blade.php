@extends('admin.layouts.admin')

@section('title', 'إضافة أدمن جديد')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    بروفايل الادمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">بروفايل الادمين</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')
    @include('admin.adminProfile.form')
@endsection

@section('js')
    @stack('js')
@endsection

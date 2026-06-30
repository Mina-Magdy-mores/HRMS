@extends('admin.layouts.admin')

@section('title', 'تعديل بيانات أدمن')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    بروفايل الادمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">بروفايل الادمين</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
    @include('admin.adminProfile.updateForm')
@endsection

@section('js')
    @stack('js')
@endsection

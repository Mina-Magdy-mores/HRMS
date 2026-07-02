@extends('admin.layouts.admin')

@section('title', 'تعديل دور وصلاحيات')
@section('contentHeader')
<i class="fas fa-users-cog"></i>
أدوار وصلاحيات المستخدمين
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-roles.index') }}">أدوار المستخدمين</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
@include('admin.permission_roles.updateForm')
@endsection

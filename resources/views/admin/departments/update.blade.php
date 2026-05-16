@extends('admin.layouts.admin')

@section('title', 'تعديل إدارة')
@section('contentHeader')
    <i class="fas fa-building"></i>
    الإدارات
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.departments.index') }}">الإدارات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.departments.updateForm')
@endsection
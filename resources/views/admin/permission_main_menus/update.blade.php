@extends('admin.layouts.admin')

@section('title', 'تعديل القائمة الرئيسية للصلاحيات')
@section('contentHeader')
<i class="fas fa-list"></i>
القوائم الرئيسية للصلاحيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-main-menus.index') }}">القوائم الرئيسية</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
@include('admin.permission_main_menus.updateForm')
@endsection

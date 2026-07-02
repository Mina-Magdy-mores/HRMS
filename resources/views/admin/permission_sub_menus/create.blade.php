@extends('admin.layouts.admin')

@section('title', 'إضافة قائمة فرعية جديدة')
@section('contentHeader')
<i class="fas fa-list-ul"></i>
القوائم الفرعية للصلاحيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-sub-menus.index') }}">القوائم الفرعية</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')
@include('admin.permission_sub_menus.form')
@endsection

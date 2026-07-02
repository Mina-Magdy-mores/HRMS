@extends('admin.layouts.admin')

@section('title', 'تعديل القائمة الفرعية للصلاحيات')
@section('contentHeader')
<i class="fas fa-list-ul"></i>
القوائم الفرعية للصلاحيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-sub-menus.index') }}">القوائم الفرعية</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
@include('admin.permission_sub_menus.updateForm')
@endsection

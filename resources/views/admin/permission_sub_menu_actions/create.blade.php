@extends('admin.layouts.admin')

@section('title', 'إضافة حركة جديدة')
@section('contentHeader')
<i class="fas fa-running"></i>
حركات القوائم الفرعية
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-sub-menu-actions.index') }}">حركات القوائم الفرعية</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')
@include('admin.permission_sub_menu_actions.form')
@endsection

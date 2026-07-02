@extends('admin.layouts.admin')

@section('title', 'تعديل حركة')
@section('contentHeader')
<i class="fas fa-running"></i>
حركات القوائم الفرعية
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-sub-menu-actions.index') }}">حركات القوائم الفرعية</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
@include('admin.permission_sub_menu_actions.updateForm')
@endsection

@extends('admin.layouts.admin')

@section('title', 'حركات القوائم الفرعية')
@section('contentHeader')
<i class="fas fa-running"></i>
حركات القوائم الفرعية
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.permission-sub-menu-actions.index') }}">حركات القوائم الفرعية</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
@include('admin.permission_sub_menu_actions.table', ['actions' => $actions])
@endsection

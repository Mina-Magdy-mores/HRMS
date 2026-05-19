@extends('admin.layouts.admin')

@section('title', 'إضافة استقالة جديدة')
@section('contentHeader')
<i class="fas fa-sign-out-alt"></i>
الاستقالات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.resignations.index') }}">الاستقالات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.resignations.form')
@endsection
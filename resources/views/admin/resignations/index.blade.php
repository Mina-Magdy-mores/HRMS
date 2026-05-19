@extends('admin.layouts.admin')

@section('title', 'استقالات')
@section('contentHeader')
<i class="fas fa-sign-out-alt"></i>
انواع الاستقالات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.resignations.index') }}">انواع الاستقالات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.resignations.table', ['resignations' => $resignations])
@endsection

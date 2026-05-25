@extends('admin.layouts.admin')

@section('title', 'إضافة نوع بدل جديد')
@section('contentHeader')
    <i class="fas fa-money-bill-wave"></i>
    أنواع البدل للراتب
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.allowance-types.index') }}">أنواع البدل للراتب</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

    @include('admin.allowanceType.form')
@endsection

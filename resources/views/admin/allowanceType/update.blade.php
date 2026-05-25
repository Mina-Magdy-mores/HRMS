@extends('admin.layouts.admin')

@section('title', 'تعديل نوع بدل')
@section('contentHeader')
    <i class="fas fa-money-bill-wave"></i>
    أنواع البدل للراتب
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.allowance-types.index') }}">أنواع البدل للراتب</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.allowanceType.updateForm')
@endsection

@extends('admin.layouts.admin')

@section('title', 'أنواع البدل للراتب')
@section('contentHeader')
    <i class="fas fa-money-bill-wave"></i>
    أنواع البدل للراتب
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.allowance-types.index') }}">أنواع البدل للراتب</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

    @include('admin.allowanceType.table', ['allowanceTypes' => $allowanceTypes])
@endsection

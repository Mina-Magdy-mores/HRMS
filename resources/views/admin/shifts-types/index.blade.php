@extends('admin.layouts.admin')

@section('title', 'أنواع الشفتات')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    أنواع الشفتات
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.shifts-types.index') }}">أنواع الشفتات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

        @include('admin.shifts-types.table', ['shiftsTypes' => $shiftsTypes])
@endsection

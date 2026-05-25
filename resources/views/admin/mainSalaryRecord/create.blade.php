@extends('admin.layouts.admin')

@section('title', 'إضافة نوع شفت جديد')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    أنواع الشفتات
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.shifts-types.index') }}">أنواع الشفتات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

    @include('admin.shifts-types.form')
@endsection
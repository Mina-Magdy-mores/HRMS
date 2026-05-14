@extends('admin.layouts.admin')

@section('title', 'تعديل نوع شفت')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    أنواع الشفتات
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.shifts-types.index') }}">أنواع الشفتات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.shifts-types.updateForm')
@endsection
@extends('admin.layouts.admin')

@section('title', 'الفروع')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    الفروع
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.branches.index') }}">الفروع</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.branches.updateForm')
@endsection
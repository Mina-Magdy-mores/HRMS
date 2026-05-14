@extends('admin.layouts.admin')

@section('title', 'اضافة فرع جديد')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    الفروع
    @endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.branches.index') }}">الفروع</a>
@endsection
@section('contentHeaderActive', 'اضافة')

@section('content')
    @include('admin.branches.form')
@endsection

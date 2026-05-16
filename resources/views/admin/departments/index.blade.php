@extends('admin.layouts.admin')

@section('title', 'إدارات الموظفين')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
إدارات الموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.departments.index') }}">إدارات الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

        @include('admin.departments.table', ['departments' => $departments])
@endsection

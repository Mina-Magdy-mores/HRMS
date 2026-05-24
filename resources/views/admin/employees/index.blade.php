@extends('admin.layouts.admin')

@section('title', 'الموظفين')

@section('contentHeader', 'الموظفين')
@section('contentHeaderActiveLink', 'الموظفين')
@section('contentHeaderActive', 'قائمة الموظفين')

@section('content')
@include('admin.employees.table', ['employees' => $employees])

@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

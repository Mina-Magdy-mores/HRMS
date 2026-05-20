@extends('admin.layouts.admin')

@section('title', 'الموظفين')

@section('contentHeader', 'الموظفين')
@section('contentHeaderActiveLink', 'الموظفين')
@section('contentHeaderActive', 'قائمة الموظفين')

@section('content')
@include('admin.employees.table', ['employees' => $employees])

@endsection

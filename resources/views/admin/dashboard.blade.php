@extends('admin.layouts.admin')
@section('title', 'HRMS')
@section('contentHeader', 'Dashboard')
@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.dashboard') }}">HRMS</a>
@endsection
@section('contentHeaderActive', 'Dashboard')
@section('content')
<h1>Dashboard Page</h1>
@endsection

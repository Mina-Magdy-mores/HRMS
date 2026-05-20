@extends('admin.layouts.admin')

@section('title', 'إضافة دولة جديدة')
@section('contentHeader')
<i class="fas fa-globe"></i>
الدول
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.countries.index') }}">الدول</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.country.form')
@endsection
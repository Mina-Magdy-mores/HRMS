@extends('admin.layouts.admin')

@section('title', 'تعديل استقالة')
@section('contentHeader')
<i class="fas fa-sign-out-alt"></i>
الاستقالات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.resignations.index') }}">الاستقالات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.resignations.updateForm')
@endsection
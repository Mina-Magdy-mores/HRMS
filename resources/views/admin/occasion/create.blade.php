@extends('admin.layouts.admin')

@section('title', 'إضافة مناسبة جديدة')
@section('contentHeader')
<i class="fas fa-gift"></i>
المناسبات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.occasions.index') }}">المناسبات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.occasion.form')
@endsection
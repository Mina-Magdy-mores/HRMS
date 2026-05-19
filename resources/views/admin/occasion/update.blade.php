@extends('admin.layouts.admin')

@section('title', 'تعديل مناسبة')
@section('contentHeader')
<i class="fas fa-gift"></i>
المناسبات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.occasions.index') }}">المناسبات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.occasion.updateForm')
@endsection
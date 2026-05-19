@extends('admin.layouts.admin')

@section('title', 'المناسبات')
@section('contentHeader')
<i class="fas fa-gift"></i>
المناسبات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.occasions.index') }}">المناسبات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.occasion.table', ['occasions' => $occasions])
@endsection

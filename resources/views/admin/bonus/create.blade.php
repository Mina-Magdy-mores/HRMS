@extends('admin.layouts.admin')

@section('title', 'إضافة مكافأة جديدة')
@section('contentHeader')
<i class="fas fa-gift"></i>
المكافآت
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.bonuses.index') }}">المكافآت</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.bonus.form')
@endsection

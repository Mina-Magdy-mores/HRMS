@extends('admin.layouts.admin')

@section('title', 'تعديل مكافأة')
@section('contentHeader')
<i class="fas fa-gift"></i>
المكافآت
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.bonuses.index') }}">المكافآت</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.bonus.updateForm')
@endsection

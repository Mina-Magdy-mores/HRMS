@extends('admin.layouts.admin')

@section('title', 'إضافة فصيلة دم جديدة')
@section('contentHeader')
<i class="fas fa-tint"></i>
فصائل الدم
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.blood-groups.index') }}">فصائل الدم</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.blood_group.form')
@endsection
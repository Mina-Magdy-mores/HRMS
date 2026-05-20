@extends('admin.layouts.admin')

@section('title', 'تعديل فصيلة دم')
@section('contentHeader')
<i class="fas fa-tint"></i>
فصائل الدم
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.blood-groups.index') }}">فصائل الدم</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.blood_group.updateForm')
@endsection
@extends('admin.layouts.admin')

@section('title', 'فصائل الدم')
@section('contentHeader')
<i class="fas fa-tint"></i>
فصائل الدم
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.blood-groups.index') }}">فصائل الدم</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.blood_group.table', ['bloodGroups' => $bloodGroups])
@endsection
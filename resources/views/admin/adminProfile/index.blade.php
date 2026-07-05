@extends('admin.layouts.admin')

@section('title', 'المستخدمين')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    المستخدمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">المستخدمين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
    @include('admin.adminProfile.table', ['admins' => $admins])
@endsection

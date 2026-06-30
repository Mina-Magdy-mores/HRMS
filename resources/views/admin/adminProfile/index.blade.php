@extends('admin.layouts.admin')

@section('title', 'بروفايل الادمين')
@section('contentHeader')
    <i class="fas fa-users-cog"></i>
    بروفايل الادمين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.admin-profiles.index') }}">بروفايل الادمين</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
    @include('admin.adminProfile.table', ['admins' => $admins])
@endsection

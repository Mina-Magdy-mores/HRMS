@extends('admin.layouts.admin')

@section('title', 'إضافة نوع إجازة جديد')
@section('contentHeader')
<i class="fas fa-calendar-alt"></i>
أنواع الإجازات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.vacation-types.index') }}">أنواع الإجازات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.vacationTypes.form')
@endsection

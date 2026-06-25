@extends('admin.layouts.admin')

@section('title', 'تعديل نوع إجازة')
@section('contentHeader')
<i class="fas fa-calendar-alt"></i>
أنواع الإجازات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.vacation-types.index') }}">أنواع الإجازات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.vacationTypes.updateForm', ['vacationType' => $vacationType])
@endsection

@extends('admin.layouts.admin')

@section('title', 'تعديل نوع خصم')
@section('contentHeader')
<i class="fas fa-minus-circle"></i>
أنواع الخصومات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.deduction-types.index') }}">أنواع الخصومات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.deductionType.updateForm')
@endsection

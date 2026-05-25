@extends('admin.layouts.admin')

@section('title', 'إضافة نوع خصم جديد')
@section('contentHeader')
<i class="fas fa-minus-circle"></i>
أنواع الخصومات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.deduction-types.index') }}">أنواع الخصومات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.deductionType.form')
@endsection

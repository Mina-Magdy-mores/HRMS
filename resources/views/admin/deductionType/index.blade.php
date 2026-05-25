@extends('admin.layouts.admin')

@section('title', 'أنواع الخصومات')
@section('contentHeader')
<i class="fas fa-minus-circle"></i>
أنواع الخصومات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.deduction-types.index') }}">أنواع الخصومات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.deductionType.table', ['deductionTypes' => $deductionTypes])
@endsection

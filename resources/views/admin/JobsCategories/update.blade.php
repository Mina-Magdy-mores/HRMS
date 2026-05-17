@extends('admin.layouts.admin')

@section('title', 'تعديل تصنيف وظيفي')
@section('contentHeader')
    <i class="fas fa-briefcase"></i>
    تصنيفات الوظائف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.jobCategories.index') }}">تصنيفات الوظائف</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.JobsCategories.updateForm')
@endsection
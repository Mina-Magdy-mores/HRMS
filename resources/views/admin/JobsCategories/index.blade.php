@extends('admin.layouts.admin')

@section('title', 'تصنيفات الوظائف')
@section('contentHeader')
    <i class="fas fa-briefcase"></i>
    تصنيفات الوظائف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.jobCategories.index') }}">تصنيفات الوظائف</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

    @include('admin.JobsCategories.table', ['jobCategories' => $jobCategories])
@endsection

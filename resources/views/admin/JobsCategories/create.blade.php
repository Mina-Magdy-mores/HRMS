@extends('admin.layouts.admin')

@section('title', 'إضافة تصنيف وظيفي جديد')
@section('contentHeader')
    <i class="fas fa-briefcase"></i>
    تصنيفات الوظائف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.jobCategories.index') }}">تصنيفات الوظائف</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

    @include('admin.JobsCategories.form')
@endsection

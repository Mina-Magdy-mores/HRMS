@extends('admin.layouts.admin')

@section('title', 'إضافة دين جديد')
@section('contentHeader')
<i class="fas fa-pray"></i>
الأديان
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.religions.index') }}">الأديان</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.religion.form')
@endsection
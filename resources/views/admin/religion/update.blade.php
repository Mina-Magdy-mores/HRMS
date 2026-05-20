@extends('admin.layouts.admin')

@section('title', 'تعديل دين')
@section('contentHeader')
<i class="fas fa-pray"></i>
الأديان
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.religions.index') }}">الأديان</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.religion.updateForm')
@endsection
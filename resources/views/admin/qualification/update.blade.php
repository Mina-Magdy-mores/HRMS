@extends('admin.layouts.admin')

@section('title', 'تعديل مؤهل')
@section('contentHeader')
<i class="fas fa-graduation-cap"></i>
المؤهلات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.qualifications.index') }}">المؤهلات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.qualification.updateForm')
@endsection
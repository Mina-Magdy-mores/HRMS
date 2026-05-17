@extends('admin.layouts.admin')

@section('title', 'إضافة مؤهل جديد')
@section('contentHeader')
<i class="fas fa-graduation-cap"></i>
المؤهلات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.qualifications.index') }}">المؤهلات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.qualification.form')
@endsection
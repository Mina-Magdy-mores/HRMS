@extends('admin.layouts.admin')

@section('title', 'المؤهلات')
@section('contentHeader')
<i class="fas fa-graduation-cap"></i>
المؤهلات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.qualifications.index') }}">المؤهلات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.qualification.table', ['qualifications' => $qualifications])
@endsection
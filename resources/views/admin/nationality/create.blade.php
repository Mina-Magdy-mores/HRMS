@extends('admin.layouts.admin')

@section('title', 'إضافة جنسية جديدة')
@section('contentHeader')
<i class="fas fa-globe"></i>
الجنسيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.nationalities.index') }}">الجنسيات</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.nationality.form')
@endsection
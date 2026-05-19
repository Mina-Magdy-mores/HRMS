@extends('admin.layouts.admin')

@section('title', 'تعديل جنسية')
@section('contentHeader')
<i class="fas fa-globe"></i>
الجنسيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.nationalities.index') }}">الجنسيات</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.nationality.updateForm')
@endsection
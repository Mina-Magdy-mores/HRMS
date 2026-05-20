@extends('admin.layouts.admin')

@section('title', 'تعديل دولة')
@section('contentHeader')
<i class="fas fa-globe"></i>
الدول
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.countries.index') }}">الدول</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

@include('admin.country.updateForm')
@endsection
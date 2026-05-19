@extends('admin.layouts.admin')

@section('title', 'الجنسيات')
@section('contentHeader')
<i class="fas fa-globe"></i>
الجنسيات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.nationalities.index') }}">الجنسيات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.nationality.table', ['nationalities' => $nationalities])
@endsection
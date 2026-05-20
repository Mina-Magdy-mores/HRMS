@extends('admin.layouts.admin')

@section('title', 'الدول')
@section('contentHeader')
<i class="fas fa-globe"></i>
الدول
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.countries.index') }}">الدول</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.country.table', ['countries' => $countries])
@endsection
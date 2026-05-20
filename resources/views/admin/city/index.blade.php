@extends('admin.layouts.admin')

@section('title', 'المدن')
@section('contentHeader')
<i class="fas fa-city"></i>
المدن
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.cities.index') }}">المدن</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.city.table', ['cities' => $cities])
@endsection
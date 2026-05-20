@extends('admin.layouts.admin')

@section('title', 'المحافظات')
@section('contentHeader')
<i class="fas fa-map"></i>
المحافظات
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.governorates.index') }}">المحافظات</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.governorate.table', ['governorates' => $governorates])
@endsection
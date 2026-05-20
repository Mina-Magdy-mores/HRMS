@extends('admin.layouts.admin')

@section('title', 'الأديان')
@section('contentHeader')
<i class="fas fa-pray"></i>
الأديان
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.religions.index') }}">الأديان</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.religion.table', ['religions' => $religions])
@endsection
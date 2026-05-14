@extends('admin.layouts.admin')

@section('title', 'الفروع')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    الفروع
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.branches.index') }}">الفروع</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

    @if (@isset($branches) && !@empty($branches))
        @include('admin.branches.table', ['branches' => $branches])
    @endif
@endsection

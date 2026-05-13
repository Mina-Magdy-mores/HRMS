@extends('admin.layouts.admin')

@section('title', 'السنوات المالية')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    السنوات المالية
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.financeCalendars.index') }}">السنوات المالية</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')

    @include('admin.financeCalendar.updateForm')
@endsection

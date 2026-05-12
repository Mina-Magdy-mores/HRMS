@extends('admin.layouts.admin')

@section('title', 'السنوات المالية')
@section('contentHeader')
    <i class="fas fa-calendar"></i>
    السنوات المالية
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.finance_calendars.index') }}">السنوات المالية</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

        @include('admin.finance_calendar.form')
@endsection
    
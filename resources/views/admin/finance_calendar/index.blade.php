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

    @if (@isset($finance_calendars) && !@empty($finance_calendars))
        @include('admin.finance_calendar.table', ['finance_calendars' => $finance_calendars])
    @endif
@endsection
    
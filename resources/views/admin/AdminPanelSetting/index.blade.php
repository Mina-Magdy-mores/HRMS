@extends('admin.layouts.admin')

@section('title', 'الضبط العام للنظام')
@section('contentHeader')
    <i class="fas fa-cogs"></i>
    قائمة الضبط
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.general-settings') }}">الضبط العام للنظام</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

    @if (@isset($general_settings) && !@empty($general_settings))
        @include('admin.AdminPanelSetting.form', ['general_settings' => $general_settings])
    @endif
@endsection
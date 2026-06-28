@extends('admin.layouts.admin')

@section('title', 'تفاصيل الموظف')
@section('contentHeader')
    <i class="fas fa-user text-info"></i>
    تفاصيل الموظف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.employees.index') }}">الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض التفاصيل')

@section('content')
    @include('admin.employees.modal_details', ['employee' => $employee, 'allowances' => $allowances])
@endsection

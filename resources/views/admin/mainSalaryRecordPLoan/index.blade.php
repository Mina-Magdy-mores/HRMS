@extends('admin.layouts.admin')

@section('title', 'السلف المستديمة للموظفين')
@section('contentHeader')
    <i class="fas fa-coins"></i>
    السلف المستديمة للموظفين
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.main-salary-employee-ploans.index') }}">السلف المستديمة</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')
    @include('admin.mainSalaryRecordPLoan.table', [
        'mainSalaryEmployeePLoans' => $mainSalaryEmployeePLoans,
        'total_count' => $total_count,
        'disbursed_count' => $disbursed_count,
        'non_disbursed_count' => $non_disbursed_count,
        'total_amount' => $total_amount,
        'employees_has_opened_monthly_record' => $employees_has_opened_monthly_record,
        'employees' => $employees,
    ])
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

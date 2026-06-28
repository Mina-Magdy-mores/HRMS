@extends('admin.layouts.admin')

@section('title', 'أرصدة إجازات الموظفين')

@section('contentHeader', 'أرصدة إجازات الموظفين')
@section('contentHeaderActiveLink', 'أرصدة إجازات الموظفين')
@section('contentHeaderActive', 'قائمة أرصدة الإجازات')

@section('content')
    @include('admin.mainEmployeesVacationsBalances.table', ['employees' => $employees])
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        function initSelect2() {
            $('.select2').each(function() {
                var $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }
                var $modal = $select.closest('.modal');
                if ($modal.length) {
                    $select.select2({
                        theme: 'bootstrap4',
                        dropdownParent: $modal
                    });
                } else {
                    $select.select2({
                        theme: 'bootstrap4'
                    });
                }
            });
        }
        $(document).ready(function() {
            initSelect2();
        });
    </script>
@endsection

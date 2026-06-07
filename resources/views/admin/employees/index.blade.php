@extends('admin.layouts.admin')

@section('title', 'الموظفين')

@section('contentHeader', 'الموظفين')
@section('contentHeaderActiveLink', 'الموظفين')
@section('contentHeaderActive', 'قائمة الموظفين')

@section('content')
    @include('admin.employees.table', ['employees' => $employees])

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

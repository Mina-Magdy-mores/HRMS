@extends('admin.layouts.admin')

@section('title', 'إضافة موظف جديد')
@section('contentHeader')
<i class="fas fa-user"></i>
الموظفين
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.employees.index') }}">الموظفين</a>
@endsection
@section('contentHeaderActive', 'إضافة')

@section('content')

@include('admin.employees.form')
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endsection
@section('js')
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>

</script>
<script>
    function initSelect2() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
        }
        $(document).ready(function () {
            initSelect2();
            $(document).on('change', '#country_id', function () {
                getGovernorate();
            })

            function getGovernorate() {
                var country_id = $('#country_id').val();
                $.ajax({
                    url: '{{ route('admin.employees.governorate-list') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        country_id: country_id
                    },
                    success: function (governorate) {
                        $('#ajax_responce_for_governorate_list').html(governorate);
                        initSelect2();
                    },
                    error: function (xhr) {
                        alert('حدث خطأ');
                    }
                });
            }
            initSelect2();
            $(document).on('change', '#governorate_id', function () {
                getCities();
            })
            function getCities() {
                var governorate_id = $('#governorate_id').val();
                $.ajax({
                    url: '{{ route('admin.employees.cities-list') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        governorate_id: governorate_id
                    },
                    success: function (cities) {
                        $('#ajax_responce_for_cities_list').html(cities);
                        initSelect2();
                    },
                    error: function (xhr) {
                        alert('حدث خطأ');
                    }
                });
            }
        })
</script>
@endsection

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


             $(document).on('change', '#military_status', function () {
                var emp_military_id = $(this).val();
                if (emp_military_id == 1) {
                    $('#military_start_date').show().find('input, select, textarea').prop('disabled', false);
                    $('#military_end_date').show().find('input, select, textarea').prop('disabled', false);
                    $('#military_weapon').show().find('input, select, textarea').prop('disabled', false);
                    $('#military_exemption_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_exemption_reason').hide().find('input, select, textarea').prop('disabled', true);
                    $('#postponement_reason').hide().find('input, select, textarea').prop('disabled', true);
                } else if (emp_military_id == 2) {
                    $('#military_exemption_date').show().find('input, select, textarea').prop('disabled', false);
                    $('#military_exemption_reason').show().find('input, select, textarea').prop('disabled', false);
                    $('#military_start_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_end_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_weapon').hide().find('input, select, textarea').prop('disabled', true);
                    $('#postponement_reason').hide().find('input, select, textarea').prop('disabled', true);
                }else if(emp_military_id == 3){
                    $('#military_start_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_end_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_weapon').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_exemption_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_exemption_reason').hide().find('input, select, textarea').prop('disabled', true);
                    $('#postponement_reason').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#military_start_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_end_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_weapon').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_exemption_date').hide().find('input, select, textarea').prop('disabled', true);
                    $('#military_exemption_reason').hide().find('input, select, textarea').prop('disabled', true);
                    $('#postponement_reason').hide().find('input, select, textarea').prop('disabled', true);

                }
            })
             $(document).on('change', '#driving_license', function () {
                var driving_license = $(this).val();
                if (driving_license == 1) {
                    $('#drivingLicenseTypeGroup').show().find('input, select, textarea').prop('disabled', false);
                    $('#drivingLicenseNumberGroup').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#drivingLicenseTypeGroup').hide().find('input, select, textarea').prop('disabled', true);
                    $('#drivingLicenseNumberGroup').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
             $(document).on('change', '#fixed_shift', function () {
                var fixed_shift = $(this).val();
                if (fixed_shift == '1') {
                    $('#shift_type_id').show().find('input, select, textarea').prop('disabled', false);
                    $('#daily_work_hours_group').hide().find('input, select, textarea').prop('disabled', true);
                }else if(fixed_shift == '0'){
                    $('#shift_type_id').hide().find('input, select, textarea').prop('disabled', true);
                    $('#daily_work_hours_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                     $('#shift_type_id').hide().find('input, select, textarea').prop('disabled', true);
                     $('#daily_work_hours_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
             $(document).on('change', '#has_disability', function () {
                var has_disability = $(this).val();
                if (has_disability == 1) {
                    $('#disability_description_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#disability_description_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
              $(document).on('change', '#has_relative', function () {
                var has_relative = $(this).val();
                if (has_relative == 1) {
                    $('#relative_description_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#relative_description_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
              $(document).on('change', '#resignation_id', function () {
                var resignation_id = $(this).val();
                if (resignation_id) {
                    $('#resignation_date_group').show().find('input, select, textarea').prop('disabled', false);
                    $('#resignation_reason_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#resignation_date_group').hide().find('input, select, textarea').prop('disabled', true);
                    $('#resignation_reason_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
                          $(document).on('change', '#motivation_type', function () {
                var motivation_type = $(this).val();
                if (motivation_type == '1') {
                    $('#motivation_amount_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#motivation_amount_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
                          $(document).on('change', '#payment_method', function () {
                var payment_method = $(this).val();
                if (payment_method == '2') {
                    $('#bank_account_number_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#bank_account_number_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
                          $(document).on('change', '#has_social_insurance', function () {
                var has_social_insurance = $(this).val();
                if (has_social_insurance == '1') {
                    $('#social_insurance_amount_group').show().find('input, select, textarea').prop('disabled', false);
                    $('#social_insurance_number_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#social_insurance_amount_group').hide().find('input, select, textarea').prop('disabled', true);
                    $('#social_insurance_number_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            })
                                      $(document).on('change', '#has_medical_insurance', function () {
                var has_medical_insurance = $(this).val();
                if (has_medical_insurance == '1') {
                    $('#medical_insurance_amount_group').show().find('input, select, textarea').prop('disabled', false);
                    $('#medical_insurance_number_group').show().find('input, select, textarea').prop('disabled', false);
                }else{
                    $('#medical_insurance_amount_group').hide().find('input, select, textarea').prop('disabled', true);
                    $('#medical_insurance_number_group').hide().find('input, select, textarea').prop('disabled', true);
                }
            });

            $('#military_status').trigger('change');
            $('#driving_license').trigger('change');
            $('#fixed_shift').trigger('change');
            $('#has_disability').trigger('change');
            $('#has_relative').trigger('change');
            $('#resignation_id').trigger('change');
            $('#motivation_type').trigger('change');
            $('#payment_method').trigger('change');
            $('#has_social_insurance').trigger('change');
            $('#has_medical_insurance').trigger('change');

            if ($('#country_id').val()) {
                getGovernorate();
            }
        });

</script>
@endsection

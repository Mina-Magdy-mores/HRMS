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
   $('.select2').select2({
     theme: 'bootstrap4'
   });

</script>

@endsection

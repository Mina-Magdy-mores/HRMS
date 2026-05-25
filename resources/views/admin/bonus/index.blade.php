@extends('admin.layouts.admin')

@section('title', 'المكافآت')
@section('contentHeader')
<i class="fas fa-gift"></i>
المكافآت
@endsection

@section('contentHeaderActiveLink')
<a class="active" href="{{ route('admin.bonuses.index') }}">المكافآت</a>
@endsection
@section('contentHeaderActive', 'عرض')

@section('content')

@include('admin.bonus.table', ['bonuses' => $bonuses])
@endsection

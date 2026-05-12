@extends('admin.layouts.admin')
@section('title', 'HRMS | 404')
@section('contentHeader', '404 Error Page')
@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.dashboard') }}">HRMS</a>
@endsection
@section('contentHeaderActive', '404 Error Page')

@section('content')
  <div class="error-page">
                <h2 class="headline text-warning">404</h2>
                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> الصفحة غير موجودة</h3>

                    <p>
                        لم نتمكن من إيجاد الصفحة التي تبحث عنها.
                        في الوقت نفسه, يمكنك <a href="{{ route('admin.dashboard') }}">العودة إلى لوحة التحكم</a> أو محاولة استخدام
                        نموذج البحث.
                    </p>
                </div>
            </div>
     @endsection

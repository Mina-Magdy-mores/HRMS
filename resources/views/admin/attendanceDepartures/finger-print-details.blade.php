@extends('admin.layouts.admin')

@section('title', 'تفاصيل بصمة الموظف')

@section('contentHeader')
    <i class="fas fa-fingerprint"></i>
    تفاصيل بصمة الموظف
@endsection

@section('contentHeaderActiveLink')
    <a class="active" href="{{ route('admin.attendanceDepartures.index') }}">بصمة الموظفين</a>
@endsection

@section('contentHeaderActive', 'تفاصيل البصمة')

@section('content')
<div class="container-fluid">

    <!-- Top Navigation & Actions Bar -->
    <div class="card card-outline card-info shadow mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-user-clock text-info mr-2"></i>
                        تفاصيل حركات بصمة الموظف: <span class="text-primary">{{ $employee->name }}</span>
                    </h5>
                </div>
                <div class="col-md-6 text-right">
                    <!-- Button to open archive modal -->
                    <button type="button" class="btn btn-primary btn-sm mr-2 shadow-sm" data-toggle="modal" data-target="#fingerprintArchiveModal">
                        <i class="fas fa-archive mr-1"></i> عرض سجل أرشيف البصمة كامل
                    </button>
                    <a href="{{ route('admin.attendanceDepartures.show', $financeMonthlyCalendar->id) }}"
                        class="btn btn-outline-secondary btn-sm shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i> العودة لجدول الموظفين
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Info Block -->
    <div class="card card-primary card-outline shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Employee Image -->
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if ($employee->image)
                        <img src="{{ asset('storage/' . $employee->image) }}" alt="صورة الموظف"
                            class="img-thumbnail rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #007bff;">
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle shadow-sm" 
                            style="width: 120px; height: 120px; border: 3px solid #dee2e6; font-size: 3rem;">
                            <i class="fas fa-user text-secondary"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Employee Details -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">كود الموظف</span>
                            <span class="badge badge-secondary px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $employee->employee_code ?? '---' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">كود البصمة</span>
                            <span class="badge badge-info px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $employee->fingerprint_code ?? 'لم يتم التعيين' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <span class="d-block text-muted small font-weight-bold">الشهر المالي الحالي</span>
                            <span class="badge badge-primary px-3 py-2 mt-1" style="font-size: 0.95rem;">
                                {{ $financeMonthlyCalendar->month->name }} ({{ $financeMonthlyCalendar->finance_yr }})
                            </span>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>الفرع: </strong> <span class="text-dark">{{ optional($employee->branch)->name ?? '---' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>الإدارة: </strong> <span class="text-dark">{{ optional($employee->department)->name ?? '---' }}</span>
                        </div>
                        <div class="col-md-4">
                            <strong>الوظيفة: </strong> <span class="text-dark">{{ optional($employee->job)->name ?? '---' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Month Fingerprint Actions -->
    <div class="card card-primary card-outline shadow mb-4">
        <div class="card-header">
            <h3 class="card-title text-primary font-weight-bold mb-0">
                <i class="fas fa-list-ul mr-2"></i>
                حركات البصمة المرفوعة للموظف خلال شهر: <span class="text-success">{{ $financeMonthlyCalendar->month->name }}</span>
            </h3>
        </div>
        <div class="card-body">
            @if ($fingerprintActions->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>تاريخ الحركة</th>
                                <th>وقت الحركة</th>
                                <th>نوع الحركة</th>
                                <th>تاريخ سحب البصمة</th>
                                <th>بواسطة</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fingerprintActions as $action)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="font-weight-bold">
                                        {{ \Carbon\Carbon::parse($action->dateTimeAction)->locale('ar')->translatedFormat('l') }} ،
                                        {{ \Carbon\Carbon::parse($action->dateTimeAction)->format('Y-m-d') }}
                                    </td>
                                    <td class="font-weight-bold text-primary">
                                        {{ \Carbon\Carbon::parse($action->dateTimeAction)->format('h:i A') }}
                                    </td>
                                    <td>
                                        @if ($action->type == 1)
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-sign-in-alt mr-1"></i> حضور
                                            </span>
                                        @elseif ($action->type == 2)
                                            <span class="badge badge-info px-3 py-2">
                                                <i class="fas fa-sign-out-alt mr-1"></i> انصراف
                                            </span>
                                        @else
                                            <span class="badge badge-secondary px-3 py-2">
                                                غير محدد
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $action->created_at ? $action->created_at->format('Y-m-d h:i A') : '---' }}
                                    </td>
                                    <td>{{ optional($action->addedBy)->name ?? 'النظام' }}</td>
                                    <td>{{ $action->notes ?? '---' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning text-center mb-0 py-4" style="border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                    لم يتم تسجيل أي حركات بصمة للموظف خلال هذا الشهر المالي حتى الآن.
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Complete Archive Modal -->
<div class="modal fade shadow-lg" id="fingerprintArchiveModal" tabindex="-1" role="dialog" aria-labelledby="fingerprintArchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="fingerprintArchiveModalLabel">
                    <i class="fas fa-history mr-2"></i>
                    سجل أرشيف البصمة كامل للموظف: {{ $employee->name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                @if ($allFingerprintArchive->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center align-middle mb-0">
                            <thead class="bg-dark text-white" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>الشهر المالي</th>
                                    <th>تاريخ الحركة</th>
                                    <th>وقت الحركة</th>
                                    <th>نوع الحركة</th>
                                    <th>تاريخ سحب البصمة</th>
                                    <th>بواسطة</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allFingerprintArchive as $archive)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold text-primary">
                                            @if ($archive->financeMonthlyCalendar)
                                                {{ $archive->financeMonthlyCalendar->month->name }} ({{ $archive->financeMonthlyCalendar->finance_yr }})
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td class="font-weight-bold">
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->locale('ar')->translatedFormat('l') }} ،
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->format('Y-m-d') }}
                                        </td>
                                        <td class="font-weight-bold text-primary">
                                            {{ \Carbon\Carbon::parse($archive->dateTimeAction)->format('h:i A') }}
                                        </td>
                                        <td>
                                            @if ($archive->type == 1)
                                                <span class="badge badge-success px-3 py-1">
                                                    حضور
                                                </span>
                                            @elseif ($archive->type == 2)
                                                <span class="badge badge-info px-3 py-1">
                                                    انصراف
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-1">
                                                    غير محدد
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-muted small">
                                            {{ $archive->created_at ? $archive->created_at->format('Y-m-d h:i A') : '---' }}
                                        </td>
                                        <td>{{ optional($archive->addedBy)->name ?? 'النظام' }}</td>
                                        <td>{{ $archive->notes ?? '---' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning text-center m-4 py-4" style="border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        لا توجد أي حركات بصمة مسجلة للموظف في الأرشيف بالكامل.
                    </div>
                @endif
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">إغلاق الأرشيف</button>
            </div>
        </div>
    </div>
</div>

@endsection

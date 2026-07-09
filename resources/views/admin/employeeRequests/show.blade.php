@extends('admin.layouts.admin')

@section('title', 'تفاصيل الطلب')
@section('contentHeader')
    <i class="fas fa-file-invoice text-primary"></i>
    تفاصيل الطلب رقم #{{ $requestObj->id }}
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.employee-requests.index') }}">طلبات الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض التفاصيل')

@section('content')
<div class="container-fluid">

    @include('admin.includes.alerts.success')
    @include('admin.includes.alerts.error')

    <div class="row">
        <!-- Right side: Request details -->
        <div class="col-md-8">
            <!-- Request Card -->
            <div class="card card-primary card-outline shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark">
                        <span class="badge badge-info px-2 py-1 mr-2">{{ $requestObj->type->name ?? '---' }}</span>
                        {{ $requestObj->title }}
                    </h3>
                    <span class="text-muted small"><i class="fas fa-calendar-alt"></i> {{ $requestObj->created_at ? $requestObj->created_at->format('Y-m-d H:i') : '---' }}</span>
                </div>
                <div class="card-body">
                    <h6 class="text-secondary font-weight-bold mb-2">تفاصيل الطلب:</h6>
                    <div class="p-3 bg-light rounded text-dark mb-4 style-content" style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.6;">{{ $requestObj->content }}</div>

                    <!-- Current Status badge -->
                    <div class="d-flex align-items-center">
                        <span class="font-weight-bold text-secondary mr-3">حالة الطلب الحالية:</span>
                        @if($requestObj->status == 0)
                            <span class="badge badge-warning text-white px-3 py-2">
                                <i class="fas fa-hourglass-half"></i> قيد الانتظار والمراجعة
                            </span>
                        @elseif($requestObj->status == 1)
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle"></i> تم القبول والموافقة
                            </span>
                        @elseif($requestObj->status == 2)
                            <span class="badge badge-danger px-3 py-2">
                                <i class="fas fa-times-circle"></i> مرفوض
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Comments/Conversation Timeline -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title text-secondary font-weight-bold">
                        <i class="fas fa-comments"></i>
                        المناقشات والتعليقات على الطلب ({{ $requestObj->comments->count() }})
                    </h3>
                </div>
                <div class="card-body bg-light" style="max-height: 500px; overflow-y: auto;">
                    @if($requestObj->comments->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="far fa-comment-dots fa-3x mb-3 text-secondary"></i>
                            <h5>لا توجد تعليقات أو ردود على هذا الطلب بعد.</h5>
                        </div>
                    @else
                        <div class="timeline timeline-inverse">
                            @foreach($requestObj->comments as $com)
                            <!-- Timeline item -->
                            <div>
                                <i class="fas {{ $com->admin->is_employee == 1 ? 'fa-user bg-info' : 'fa-user-shield bg-primary' }} text-white"></i>
                                <div class="timeline-item shadow-sm border mb-3">
                                    <span class="time small text-muted"><i class="fas fa-clock"></i> {{ $com->created_at ? $com->created_at->diffForHumans() : '---' }}</span>
                                    <h3 class="timeline-header font-weight-bold text-dark border-0">
                                        {{ $com->admin->name }} 
                                        @if($com->admin->is_employee == 1)
                                            <span class="badge badge-light badge-sm">(موظف)</span>
                                        @else
                                            <span class="badge badge-primary badge-sm">(الإدارة)</span>
                                        @endif
                                    </h3>
                                    <div class="timeline-body p-3 bg-white text-dark rounded-bottom" style="white-space: pre-wrap;">{{ $com->comment }}</div>
                                </div>
                            </div>
                            @endforeach
                            <div>
                                <i class="far fa-clock bg-gray"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Add Comment Form -->
                <div class="card-footer bg-white border-top">
                    <form action="{{ route('admin.employee-requests.comment', $requestObj->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="comment" class="font-weight-bold text-secondary">إضافة رد أو تعليق جديد:</label>
                            <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="اكتب ردك أو استفسارك هنا..." required></textarea>
                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary shadow-sm px-4">
                                <i class="fas fa-paper-plane"></i> إرسال الرد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Left side: Information and Admin actions -->
        <div class="col-md-4">
            <!-- Employee Info Card -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-user-tie"></i>
                        بيانات مقدم الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-0">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>الاسم:</span>
                            <strong class="text-primary">{{ $requestObj->employee->name ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>كود الموظف:</span>
                            <strong>{{ $requestObj->employee->employee_code ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>القسم:</span>
                            <strong>{{ $requestObj->employee->department->name ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>الوظيفة:</span>
                            <strong>{{ $requestObj->employee->jobCategory->name ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>الهاتف:</span>
                            <strong>{{ $requestObj->employee->work_telephone ?: ($requestObj->employee->home_telephone ?: '---') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Admin Actions Card -->
            @if(auth()->user()->is_employee == 0)
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title font-weight-bold text-warning">
                        <i class="fas fa-cogs"></i>
                        إجراءات الإدارة والتحكم
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Approve / Reject form -->
                    @if(auth()->user()->is_master_admin || check_permission('طلبات الموظفين', 'تغيير الحالة'))
                        <form action="{{ route('admin.employee-requests.change-status', $requestObj->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label for="status" class="small text-muted font-weight-bold">تحديث حالة الطلب:</label>
                                <select name="status" id="status" class="form-control mb-2" required>
                                    <option value="">-- اختر الإجراء --</option>
                                    <option value="1" {{ $requestObj->status == 1 ? 'selected' : '' }}>موافقة وقبول الطلب</option>
                                    <option value="2" {{ $requestObj->status == 2 ? 'selected' : '' }}>رفض الطلب</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block shadow-sm text-white font-weight-bold">
                                <i class="fas fa-check-double"></i> تحديث الحالة
                            </button>
                        </form>
                    @endif

                    <!-- Archive request -->
                    @if($requestObj->is_archived == 0 && (auth()->user()->is_master_admin || check_permission('طلبات الموظفين', 'أرشفة')))
                        <hr>
                        <form action="{{ route('admin.employee-requests.archive', $requestObj->id) }}" method="POST"
                              onsubmit="return confirm('هل تريد أرشفة الطلب؟ لن يظهر في القائمة المفتوحة.');">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-block shadow-sm font-weight-bold">
                                <i class="fas fa-archive"></i> نقل إلى الأرشيف
                            </button>
                        </form>
                    @endif

                    <!-- Archive details if already archived -->
                    @if($requestObj->is_archived == 1)
                        <div class="alert alert-secondary text-center small mb-0 mt-2">
                            <i class="fas fa-archive"></i> الطلب مؤرشف بواسطة:<br>
                            <strong>{{ $requestObj->archivedBy->name ?? '---' }}</strong><br>
                            بتاريخ: {{ $requestObj->archived_at }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

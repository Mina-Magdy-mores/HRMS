@extends('admin.layouts.admin')

@section('title', 'تفاصيل المهمة')
@section('contentHeader')
    <i class="fas fa-tasks text-primary"></i>
    تفاصيل المهمة رقم #{{ $task->id }}
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.employee-tasks.index') }}">مهام الموظفين</a>
@endsection
@section('contentHeaderActive', 'عرض التفاصيل')

@section('content')
<div class="container-fluid">

    @include('admin.includes.alerts.success')
    @include('admin.includes.alerts.error')

    <div class="row">
        <!-- Right side: Task details & Comments -->
        <div class="col-md-8">
            <!-- Task Details Card -->
            <div class="card card-primary card-outline shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold text-dark">
                        <span class="badge badge-primary px-2 py-1 mr-2"><i class="fas fa-thumbtack"></i> مهمة</span>
                        {{ $task->title }}
                    </h3>
                    <span class="text-muted small"><i class="fas fa-calendar-alt"></i> {{ $task->created_at ? $task->created_at->format('Y-m-d H:i') : '---' }}</span>
                </div>
                <div class="card-body">
                    <h6 class="text-secondary font-weight-bold mb-2">محتوى المهمة:</h6>
                    <div class="p-3 bg-light rounded text-dark mb-3 style-content" style="white-space: pre-wrap; font-size: 1.1rem; line-height: 1.6;">{{ $task->content }}</div>

                    @if($task->notes)
                        <h6 class="text-secondary font-weight-bold mb-2"><i class="fas fa-info-circle text-info"></i> ملاحظات إضافية:</h6>
                        <div class="p-2 border rounded bg-white text-muted mb-4 small">{{ $task->notes }}</div>
                    @endif

                    <!-- Current Status badge -->
                    <div class="d-flex align-items-center mt-3">
                        <span class="font-weight-bold text-secondary mr-3">حالة الإنجاز الحالية:</span>
                        @if($task->is_completed == 0)
                            <span class="badge badge-secondary px-3 py-2">
                                <i class="fas fa-play mr-1"></i> لم تبدأ بعد
                            </span>
                        @elseif($task->is_completed == 1)
                            <span class="badge badge-warning text-white px-3 py-2">
                                <i class="fas fa-hourglass-half mr-1"></i> قيد العمل والتحضير
                            </span>
                        @else
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle mr-1"></i> منتهية ومكتملة
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Comments/Conversation Timeline (Chat Room) -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h3 class="card-title text-secondary font-weight-bold">
                        <i class="fas fa-comments text-info"></i>
                        المناقشات والردود المتبادلة ({{ $task->comments->count() }})
                    </h3>
                </div>
                <div class="card-body bg-light" style="max-height: 500px; overflow-y: auto;">
                    @if($task->comments->isEmpty())
                        <div class="text-center text-muted py-4">
                            <i class="far fa-comments fa-3x mb-3 text-secondary"></i>
                            <h5>لا توجد ردود أو نقاشات متبادلة على هذه المهمة حتى الآن.</h5>
                            <p class="small text-muted">يمكن للموظف أو الإدارة كتابة رد أو استفسار بالأسفل.</p>
                        </div>
                    @else
                        <div class="timeline timeline-inverse">
                            @foreach($task->comments as $com)
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
                    <form action="{{ route('admin.employee-tasks.comment', $task->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="comment" class="font-weight-bold text-secondary">إضافة رد أو تعليق جديد (شات متبادل):</label>
                            <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="اكتب ردك أو تقرير الإنجاز هنا..." required></textarea>
                        </div>
                        <div class="text-left">
                            <button type="submit" class="btn btn-primary shadow-sm px-4">
                                <i class="fas fa-paper-plane mr-1"></i> إرسال الرد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Left side: Employee details & actions -->
        <div class="col-md-4">
            <!-- Employee Info Card -->
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title font-weight-bold text-dark">
                        <i class="fas fa-user-tie"></i>
                        بيانات الموظف المسؤول
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-unbordered mb-0">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>الاسم:</span>
                            <strong class="text-primary">{{ $task->employee->name ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>كود الموظف:</span>
                            <strong>{{ $task->employee->employee_code ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>القسم:</span>
                            <strong>{{ $task->employee->department->name ?? '---' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>الوظيفة:</span>
                            <strong>{{ $task->employee->jobCategory->name ?? '---' }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Task Actions Card -->
            <div class="card card-outline card-warning shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title font-weight-bold text-warning">
                        <i class="fas fa-cogs text-warning"></i>
                        إجراءات وحالة المهمة
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Toggle Status Button for Assigned Employee or Admin -->
                    @if(check_permission('مهام الموظفين', 'تعديل') || (auth()->user()->is_employee == 1 && auth()->user()->employee_id == $task->employee_id))
                        <label class="small text-muted font-weight-bold d-block mb-2">تحديث حالة إنجاز المهمة:</label>
                        <a href="{{ route('admin.employee-tasks.toggle-status', $task->id) }}" class="btn btn-warning btn-block text-white font-weight-bold shadow-sm mb-3">
                            <i class="fas fa-sync-alt mr-1"></i> تغيير حالة المهمة دورياً
                        </a>
                        <p class="text-center text-muted small mb-3">الضغط على الزر يقوم بالتبديل بين: (لم تبدأ ➡️ قيد العمل ➡️ منتهية).</p>
                    @endif

                    <!-- Archive Task form for Admins -->
                    @if(auth()->user()->is_employee == 0)
                        @if($task->is_archived == 0 && (auth()->user()->is_master_admin || check_permission('مهام الموظفين', 'أرشفة')))
                            <hr>
                            <a href="{{ route('admin.employee-tasks.archive', $task->id) }}" class="btn btn-secondary btn-block font-weight-bold shadow-sm are_you_sure">
                                <i class="fas fa-archive mr-1"></i> نقل إلى الأرشيف
                            </a>
                        @endif
                    @endif

                    <!-- Archive status details -->
                    @if($task->is_archived == 1)
                        <div class="alert alert-secondary text-center small mb-0 mt-2">
                            <i class="fas fa-archive"></i> المهمة مؤرشفة بواسطة:<br>
                            <strong>{{ $task->archivedBy->name ?? '---' }}</strong><br>
                            بتاريخ: {{ $task->archived_at ? \Carbon\Carbon::parse($task->archived_at)->format('Y-m-d H:i') : '---' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

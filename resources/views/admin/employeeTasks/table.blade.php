<div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th>#</th>
                <th>الموظف</th>
                <th>عنوان المهمة</th>
                <th>محتوى المهمة</th>
                <th>حالة الإنجاز</th>
                <th>تاريخ الإضافة</th>
                <th>بواسطة</th>
                @if($showArchived == 1)
                    <th>أرشفة بواسطة</th>
                    <th>تاريخ الأرشفة</th>
                @endif
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>
                    <span class="font-weight-bold text-dark">{{ $task->employee->name ?? '---' }}</span>
                    <br>
                    <span class="badge badge-secondary">كود: {{ $task->employee->employee_code ?? '---' }}</span>
                </td>
                <td>{{ $task->title }}</td>
                <td style="max-width: 280px; white-space: normal; text-align: right;">
                    <div class="font-weight-bold mb-1">{{ $task->content }}</div>
                    @if($task->notes)
                        <div class="mt-1">
                            <small class="text-muted"><i class="fas fa-comment"></i> <strong>ملاحظات:</strong> {{ $task->notes }}</small>
                        </div>
                    @endif
                    @if($task->employee_reply)
                        <div class="mt-2 p-1 border rounded bg-light text-right">
                            <small class="text-success font-weight-bold"><i class="fas fa-reply"></i> رد الموظف:</small>
                            <div class="text-dark small pr-2">{{ $task->employee_reply }}</div>
                            @if($task->employee_replied_at)
                                <span class="text-muted d-block text-left" style="font-size: 9px; direction: ltr;">
                                    {{ \Carbon\Carbon::parse($task->employee_replied_at)->format('Y-m-d H:i') }}
                                </span>
                            @endif
                        </div>
                    @endif
                    @if($showArchived == 0 && auth()->user()->is_employee == 1 && auth()->user()->employee_id == $task->employee_id)
                        <div class="mt-2 text-left">
                            <button type="button" class="btn btn-xs btn-outline-info" onclick="toggleReplyForm({{ $task->id }})">
                                <i class="fas fa-reply"></i> {{ $task->employee_reply ? 'تحديث الرد' : 'إضافة رد الموظف' }}
                            </button>
                        </div>
                        <form id="reply-form-{{ $task->id }}" action="{{ route('admin.employee-tasks.reply', $task->id) }}" method="POST" class="mt-2 text-right" style="display: none;">
                            @csrf
                            <div class="form-group mb-1">
                                <textarea name="employee_reply" class="form-control form-control-sm" rows="2" placeholder="اكتب ردك هنا..." required>{{ $task->employee_reply }}</textarea>
                            </div>
                            <button class="btn btn-xs btn-success shadow-sm" type="submit">إرسال الرد</button>
                        </form>
                    @endif
                </td>
                <td>
                    @if($showArchived == 0 && (check_permission('مهام الموظفين', 'تعديل') || (auth()->user()->is_employee == 1 && auth()->user()->employee_id == $task->employee_id)))
                        <a href="{{ route('admin.employee-tasks.toggle-status', $task->id) }}" class="btn-link">
                            @if($task->is_completed == 0)
                                <span class="badge badge-secondary px-3 py-2 shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                    <i class="fas fa-play"></i> لم تبدأ
                                </span>
                            @elseif($task->is_completed == 1)
                                <span class="badge badge-warning px-3 py-2 text-white shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                    <i class="fas fa-hourglass-half"></i> قيد العمل
                                </span>
                            @else
                                <span class="badge badge-success px-3 py-2 shadow-sm" title="اضغط لتغيير الحالة (دوري لم تبدأ -> قيد العمل -> مكتملة)">
                                    <i class="fas fa-check-circle"></i> منتهية
                                </span>
                            @endif
                        </a>
                    @else
                        @if($task->is_completed == 0)
                            <span class="badge badge-secondary px-3 py-2">
                                <i class="fas fa-play"></i> لم تبدأ
                            </span>
                        @elseif($task->is_completed == 1)
                            <span class="badge badge-warning px-3 py-2 text-white">
                                <i class="fas fa-hourglass-half"></i> قيد العمل
                            </span>
                        @else
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle"></i> منتهية
                            </span>
                        @endif
                    @endif
                </td>
                <td>{{ $task->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $task->addedBy->name ?? '---' }}</td>
                
                @if($showArchived == 1)
                    <td>{{ $task->archivedBy->name ?? '---' }}</td>
                    <td>{{ $task->archived_at ? \Carbon\Carbon::parse($task->archived_at)->format('Y-m-d H:i') : '---' }}</td>
                @endif

                <td>
                    <div class="d-flex justify-content-center align-items-center gap-1">
                        <a href="{{ route('admin.employee-tasks.show', $task->id) }}"
                            class="btn btn-sm btn-info m-1" title="عرض التفاصيل والردود">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($showArchived == 0)
                            @if(check_permission('مهام الموظفين', 'تعديل'))
                            <a href="{{ route('admin.employee-tasks.edit', $task->id) }}"
                                class="btn btn-sm btn-warning m-1" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif

                            @if(check_permission('مهام الموظفين', 'أرشفة'))
                            <a href="{{ route('admin.employee-tasks.archive', $task->id) }}"
                                class="btn btn-sm btn-info m-1 are_you_sure" title="أرشفة المهمة">
                                <i class="fas fa-archive"></i>
                            </a>
                            @endif
                        @endif

                        @if(check_permission('مهام الموظفين', 'حذف'))
                        <form action="{{ route('admin.employee-tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1" title="حذف نهائي">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ $showArchived == 1 ? 10 : 8 }}">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle"></i>
                        لا توجد أي مهام مسجلة حالياً
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $tasks->appends(request()->query())->links() }}
</div>

<script>
function toggleReplyForm(id) {
    var form = document.getElementById('reply-form-' + id);
    if (form) {
        if (form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
}
</script>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 50px;">#</th>
                <th>المسؤول المنفذ</th>
                <th>الإجراء</th>
                <th>معرف السجل</th>
                <th>اسم السجل المستهدف</th>
                <th style="width: 300px;">التفاصيل الأصلية للحركة</th>
                <th>التاريخ والوقت</th>
                <th style="width: 100px;">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php
                $actionBadges = [
                    'حذف سجل مراقبة' => 'danger',
                    'تمييز سجل مراقبة' => 'warning text-white',
                    'إلغاء تمييز سجل مراقبة' => 'info',
                ];
            @endphp
            @forelse ($selfLogs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td class="font-weight-bold text-dark">
                        <i class="fas fa-user-shield text-secondary mr-1"></i>
                        {{ optional($log->admin)->name ?? '---' }}
                    </td>
                    <td>
                        @php
                            $badgeColor = $actionBadges[$log->action] ?? 'light';
                        @endphp
                        <span class="badge badge-{{ $badgeColor }} px-3 py-2">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-secondary">#{{ $log->target_log_id }}</span>
                    </td>
                    <td class="text-right font-weight-bold text-dark">{{ $log->target_log_name }}</td>
                    <td class="text-right">
                        <div class="p-2 bg-light rounded text-muted small" style="max-height: 80px; overflow-y: auto; white-space: pre-wrap;">
                            {{ $log->target_log_content }}
                        </div>
                    </td>
                    <td>
                        <div class="text-secondary small">
                            <i class="far fa-calendar-alt mr-1"></i> {{ $log->created_at->format('Y-m-d') }}
                        </div>
                        <div class="text-muted small">
                            <i class="far fa-clock mr-1"></i> {{ $log->created_at->format('h:i A') }}
                        </div>
                    </td>
                    <td>
                        <form action="{{ route('admin.system-monitoring.destroy-self-log', $log->id) }}" method="POST" class="d-inline-block delete-self-log-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger shadow-sm delete-btn" title="حذف السجل" onclick="return confirm('هل أنت متأكد من حذف هذا السجل من المراقبة الذاتية؟')">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                        <p class="mb-0 font-weight-bold">لا توجد سجلات مراقبة ذاتية مسجلة حالياً</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $selfLogs->links() }}
</div>

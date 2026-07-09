<div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 70px;">#</th>
                <th>الموظف</th>
                <th>نوع الطلب</th>
                <th>عنوان الطلب</th>
                <th>حالة الطلب</th>
                <th>أضيف بواسطة</th>
                <th>تاريخ التقديم</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>
                    <span class="font-weight-bold">{{ $req->employee->name ?? '---' }}</span>
                    <br>
                    <small class="text-muted">(كود: {{ $req->employee->employee_code ?? '---' }})</small>
                </td>
                <td>
                    <span class="badge badge-info px-2 py-1">{{ $req->type->name ?? '---' }}</span>
                </td>
                <td class="text-right font-weight-bold">{{ $req->title }}</td>
                <td>
                    @if($req->status == 0)
                        <span class="badge badge-warning text-white px-3 py-2">
                            <i class="fas fa-hourglass-half"></i> قيد الانتظار
                        </span>
                    @elseif($req->status == 1)
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-check-circle"></i> مقبول وموافق عليه
                        </span>
                    @elseif($req->status == 2)
                        <span class="badge badge-danger px-3 py-2">
                            <i class="fas fa-times-circle"></i> مرفوض
                        </span>
                    @endif
                </td>
                <td>{{ $req->addedBy->name ?? '---' }}</td>
                <td>{{ $req->created_at ? $req->created_at->format('Y-m-d H:i') : '---' }}</td>
                <td>
                    <div class="d-flex justify-content-center gap-1">
                        @if(auth()->user()->is_master_admin || check_permission('طلبات الموظفين', 'عرض'))
                        <a href="{{ route('admin.employee-requests.show', $req->id) }}" 
                           class="btn btn-info btn-sm shadow-sm">
                            <i class="fas fa-comments"></i> التفاصيل والردود
                        </a>
                        @endif

                        @if($req->is_archived == 0 && (auth()->user()->is_master_admin || check_permission('طلبات الموظفين', 'أرشفة')))
                        <form action="{{ route('admin.employee-requests.archive', $req->id) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('هل تريد بالتأكيد أرشفة هذا الطلب؟ لن يظهر في القائمة الحالية بعد الأرشفة.');">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-archive"></i> أرشفة
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-muted text-center py-4">
                    <h5><i class="fas fa-info-circle"></i> لا توجد طلبات متطابقة مع خيارات التصفية الحالية.</h5>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $requests->links() }}
</div>

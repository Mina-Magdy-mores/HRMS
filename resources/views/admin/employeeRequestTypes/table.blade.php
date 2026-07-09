<div class="table-responsive">
    <table class="table table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 80px;">#</th>
                <th>اسم نوع الطلب</th>
                <th>الحالة</th>
                <th>أضيف بواسطة</th>
                <th>تاريخ الإضافة</th>
                <th>آخر تعديل بواسطة</th>
                <th>تاريخ التعديل</th>
                <th style="width: 200px;">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($types as $type)
            <tr>
                <td>{{ $type->id }}</td>
                <td class="font-weight-bold text-dark">{{ $type->name }}</td>
                <td>
                    @if($type->is_active == 1)
                        <span class="badge badge-success px-3 py-2">
                            <i class="fas fa-check-circle"></i> نشط
                        </span>
                    @else
                        <span class="badge badge-danger px-3 py-2">
                            <i class="fas fa-times-circle"></i> غير نشط
                        </span>
                    @endif
                </td>
                <td>{{ $type->addedBy->name ?? '---' }}</td>
                <td>{{ $type->created_at ? $type->created_at->format('Y-m-d H:i') : '---' }}</td>
                <td>{{ $type->updatedBy->name ?? '---' }}</td>
                <td>{{ $type->updated_at ? $type->updated_at->format('Y-m-d H:i') : '---' }}</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        @if(auth()->user()->is_master_admin || check_permission('أنواع طلبات الموظفين', 'تعديل'))
                        <a href="{{ route('admin.employee-request-types.edit', $type->id) }}" 
                           class="btn btn-warning btn-sm shadow-sm text-white mx-1">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        @endif

                        @if(auth()->user()->is_master_admin || check_permission('أنواع طلبات الموظفين', 'حذف'))
                        <form action="{{ route('admin.employee-request-types.destroy', $type->id) }}" method="POST" 
                              class="d-inline-block delete-form" 
                              onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف نوع الطلب هذا؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm shadow-sm mx-1">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-muted text-center py-4">
                    <h5><i class="fas fa-info-circle"></i> لا توجد أنواع طلبات مسجلة حالياً.</h5>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $types->links() }}
</div>

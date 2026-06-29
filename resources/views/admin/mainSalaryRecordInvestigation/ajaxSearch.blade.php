<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 50px;">#</th>
                <th>كود الموظف</th>
                <th>الموظف</th>
                <th>وصف التحقيق</th>
                <th>الإضافة</th>
                <th>تاريخ الإضافة</th>
                <th>تاريخ التعديل</th>
                <th>أضيف بواسطة</th>
                <th>ملاحظات</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($investigations as $investigation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                            {{ $investigation->employee->employee_code ?? '---' }}
                        </span>
                    </td>
                    <td class="font-weight-bold">
                        {{ $investigation->employee->name ?? '---' }}
                    </td>
                    <td style="max-width: 200px;">
                        <span class="small d-inline-block text-truncate" style="max-width: 180px;"
                            title="{{ $investigation->description }}">
                            {{ $investigation->description ?? '---' }}
                        </span>
                    </td>
                    <td>
                        @if ($investigation->is_auto == 1)
                            <span class="badge badge-info px-2 py-1">
                                <i class="fas fa-robot mr-1"></i> تلقائي
                            </span>
                        @else
                            <span class="badge badge-secondary px-2 py-1">
                                <i class="fas fa-keyboard mr-1"></i> يدوي
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="small text-muted d-block"
                            title="أضيف بواسطة: {{ optional($investigation->addedBy)->name ?? '---' }} في {{ $investigation->created_at }}">
                            {{ $investigation->created_at ? $investigation->created_at->format('Y-m-d') : '---' }}
                        </span>
                        <span class="small text-muted d-block font-italic"
                            title="أضيف بواسطة: {{ optional($investigation->addedBy)->name ?? '---' }} في {{ $investigation->created_at }}">
                            {{ $investigation->created_at ? $investigation->created_at->format('h:i A') : '' }}
                        </span>
                    </td>
                    <td>
                        @if ($investigation->updated_at)
                            <span class="small text-muted d-block"
                                title="عدل بواسطة: {{ optional($investigation->updatedBy)->name ?? '---' }} في {{ $investigation->updated_at }}">
                                {{ $investigation->updated_at->format('Y-m-d') }}
                            </span>
                            <span class="small text-muted d-block font-italic"
                                title="عدل بواسطة: {{ optional($investigation->updatedBy)->name ?? '---' }} في {{ $investigation->updated_at }}">
                                {{ $investigation->updated_at->format('h:i A') }}
                            </span>
                        @else
                            <span class="small text-secondary">لا يوجد تعديل</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-light border text-secondary px-2 py-1">
                            {{ optional($investigation->addedBy)->name ?? '---' }}
                        </span>
                    </td>
                    <td style="max-width: 100px;">
                        <span class="small font-italic text-secondary d-inline-block text-truncate"
                            style="max-width: 60px;" title="{{ $investigation->notes }}">
                            {{ $investigation->notes ?? '---' }}
                        </span>
                    </td>
                    <td>
                        @if ($investigation->is_archived == 1)
                            <span class="badge badge-danger px-3 py-2">
                                <i class="fas fa-times-circle"></i>
                                مؤرشف</span>
                        @else
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle"></i>
                                غير مؤرشف</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm delete-investigation" id="delete-investigation-btn"
                            data-id="{{ $investigation->id }}">
                            <i class="fas fa-trash mr-1"></i> حذف
                        </button>
                        <button class="btn btn-warning btn-sm edit-investigation" id="edit-investigation-btn"
                            data-id="{{ $investigation->id }}">
                            <i class="fas fa-edit mr-1"></i> تعديل
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11">
                        <div class="alert alert-warning mb-0 text-center py-3">
                            <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                            لا توجد سجلات تحقيقات إدارية للموظفين في هذا الشهر المالي حالياً.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{-- Pagination --}}
<div class="mt-3" id="ajax-pagination">
    {{ $investigations->links() }}
</div>

<table class="table table-bordered table-hover text-center align-middle">

    <thead class="bg-primary text-white">
        <tr>
            <th>#</th>
            <th>نوع الشفت</th>
            <th>وقت البداية</th>
            <th>وقت النهاية</th>
            <th>إجمالي الساعات</th>
            <th>الحالة</th>
            <th>كود الشركه</th>
            <th>أضيف بواسطة</th>
            <th>آخر تحديث بواسطة</th>
            <th>تاريخ الإضافة</th>
            <th>تاريخ التحديث</th>
            <th>الإجراءات</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($shiftsTypes as $shiftsType)
        <tr>
            <td>{{ $shiftsType->id }}</td>

            <td>
                @if($shiftsType->type == 1)
                شفت نهاري
                @elseif($shiftsType->type == 2)
                شفت ليلي
                @elseif($shiftsType->type == 3)
                شفت كامل
                @else
                نوع غير معروف
                @endif
            </td>
            @php
            $start_time = new DateTime($shiftsType->start_time);
            $start_time = $start_time->format('h:i A');
            @endphp
            <td>{{ $start_time }}</td>
            @php
            $end_time = new DateTime($shiftsType->end_time);
            $end_time = $end_time->format('h:i A');
            @endphp
            <td>{{ $end_time }}</td>

            <td>{{ $shiftsType->total_hours }}</td>

            <td>
                @if($shiftsType->status == 1)
                <span class="badge badge-success px-3 py-2">
                    <i class="fas fa-check-circle"></i>
                    مفعل
                </span>
                @else
                <span class="badge badge-danger px-3 py-2">
                    <i class="fas fa-times-circle"></i>
                    معطل
                </span>
                @endif
            </td>

            <td>{{ $shiftsType->company_id }}</td>

            <td>{{ optional($shiftsType->createdBy)->name ?? '---' }}</td>

            <td>{{ optional($shiftsType->updatedBy)->name ?? '---' }}</td>

            <td>{{ $shiftsType->created_at }}</td>

            <td>{{ $shiftsType->updated_at }}</td>

            <td>
                <div class="d-flex justify-content-center align-items-center gap-1">

                    <a href="{{ route('admin.shifts-types.edit', $shiftsType->id) }}" class="btn btn-sm btn-warning m-1"
                        title="تعديل">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('admin.shifts-types.destroy', $shiftsType->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>

                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="12">
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-circle"></i>
                    لا توجد بيانات أنواع شفتات حالياً
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>

</table>


{{-- Pagination --}}
<div id="ajax-pagination">
    {{ $shiftsTypes->links() }}
</div>
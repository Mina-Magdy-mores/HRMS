<div class="container-fluid">

    @php
        $lastShiftType = $shiftsTypes->last();
        $lastShiftTypeLabel = '---';
        if ($lastShiftType) {
            $lastShiftTypeLabel = $lastShiftType->type == 1 ? 'شفت نهاري' : ($lastShiftType->type == 2 ? 'شفت ليلي' : 'نوع غير معروف');
        }
    @endphp

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد أنواع الشفتات</span>
                    <span class="info-box-number">{{ $shiftsTypes->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الشفتات المفعلة</span>
                    <span class="info-box-number">
                        {{ $shiftsTypes->where('status', 1)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الشفتات المعطلة</span>
                    <span class="info-box-number">
                        {{ $shiftsTypes->where('status', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-history"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر شفت تم إضافته</span>
                    <span class="info-box-number">
                        {{ $lastShiftTypeLabel }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول نوع الشفتات
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.shifts-types.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة نوع شفت
                </a>
            </div>

        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>نوع الشفت</th>
                            <th>وقت البداية</th>
                            <th>وقت النهاية</th>
                            <th>إجمالي الساعات</th>
                            <th>الحالة</th>
                            <th>رقم الشركة</th>
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

                                        <a href="{{ route('admin.shifts-types.edit', $shiftsType->id) }}"
                                            class="btn btn-sm btn-warning m-1" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.shifts-types.destroy', $shiftsType->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1"
                                                title="حذف">
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

            </div>

            {{-- Pagination --}}
            {{ $shiftsTypes->links() }}

        </div>
    </div>
</div>
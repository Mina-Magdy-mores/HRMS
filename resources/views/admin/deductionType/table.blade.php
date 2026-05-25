<div class="container-fluid">

    @php
    $lastDeductionType = $deductionTypes->last();
    $lastDeductionTypeLabel = $lastDeductionType ? $lastDeductionType->name : '---';
    @endphp

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-minus-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد أنواع الخصومات</span>
                    <span class="info-box-number">{{ $deductionTypes->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الأنواع المفعلة</span>
                    <span class="info-box-number">{{ $deductionTypes->where('status', 1)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الأنواع المعطلة</span>
                    <span class="info-box-number">{{ $deductionTypes->where('status', 0)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-history"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر نوع خصم تمت إضافته</span>
                    <span class="info-box-number">{{ $lastDeductionTypeLabel }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول أنواع الخصومات
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.deduction-types.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة نوع خصم
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
                            <th>الإسم</th>
                            <th>الحالة</th>
                            <th>كود الشركة</th>
                            <th>أضيف بواسطة</th>
                            <th>آخر تحديث بواسطة</th>
                            <th>تاريخ الإضافة</th>
                            <th>تاريخ التحديث</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($deductionTypes as $deductionType)
                        <tr>
                            <td>{{ $deductionType->id }}</td>
                            <td>{{ $deductionType->name }}</td>
                            <td>
                                @if($deductionType->status == 1)
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
                            <td>{{ $deductionType->company_id }}</td>
                            <td>{{ optional($deductionType->addedBy)->name ?? '---' }}</td>
                            <td>{{ optional($deductionType->updatedBy)->name ?? '---' }}</td>
                            <td>{{ $deductionType->created_at }}</td>
                            <td>{{ $deductionType->updated_at }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-1">

                                    <a href="{{ route('admin.deduction-types.edit', $deductionType->id) }}"
                                        class="btn btn-sm btn-warning m-1" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.deduction-types.destroy', $deductionType->id) }}"
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
                            <td colspan="9">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle"></i>
                                    لا توجد بيانات أنواع خصومات حالياً
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
                {{-- Pagination --}}
            </div>
            <div>
                {{ $deductionTypes->links() }}
            </div>
        </div>
    </div>
</div>

@section('js')
@endsection

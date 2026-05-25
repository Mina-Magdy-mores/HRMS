<div class="container-fluid">

    @php
        $lastAllowanceType = $allowanceTypes->last();
        $lastAllowanceTypeLabel = $lastAllowanceType ? $lastAllowanceType->name : '---';
    @endphp

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-money-bill-wave"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد أنواع البدل للراتب</span>
                    <span class="info-box-number">{{ $allowanceTypes->count() }}</span>
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
                    <span class="info-box-number">{{ $allowanceTypes->where('status', 1)->count() }}</span>
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
                    <span class="info-box-number">{{ $allowanceTypes->where('status', 0)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-history"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر نوع بدل تمت إضافته</span>
                    <span class="info-box-number">{{ $lastAllowanceTypeLabel }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول أنواع البدل للراتب
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.allowance-types.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة نوع بدل
                </a>
            </div>

        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
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
                        @forelse ($allowanceTypes as $allowanceType)
                            <tr>
                                <td>{{ $allowanceType->id }}</td>
                                <td>{{ $allowanceType->name }}</td>
                                <td>
                                    @if ($allowanceType->status == 1)
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
                                <td>{{ $allowanceType->company_id }}</td>
                                <td>{{ optional($allowanceType->addedBy)->name ?? '---' }}</td>
                                <td>{{ optional($allowanceType->updatedBy)->name ?? '---' }}</td>
                                <td>{{ $allowanceType->created_at }}</td>
                                <td>{{ $allowanceType->updated_at }}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-1">

                                        <a href="{{ route('admin.allowance-types.edit', $allowanceType->id) }}"
                                            class="btn btn-sm btn-warning m-1" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.allowance-types.destroy', $allowanceType->id) }}"
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
                                        لا توجد بيانات أنواع بدلات حالياً
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
                {{-- Pagination --}}
            </div>
            <div>
                {{ $allowanceTypes->links() }}
            </div>
        </div>
    </div>
</div>

@section('js')
@endsection

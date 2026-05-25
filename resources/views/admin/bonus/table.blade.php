<div class="container-fluid">

    @php
    $lastBonus = $bonuses->last();
    $lastBonusLabel = $lastBonus ? $lastBonus->name : '---';
    @endphp

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-gift"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد المكافآت</span>
                    <span class="info-box-number">{{ $bonuses->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">المكافآت المفعلة</span>
                    <span class="info-box-number">{{ $bonuses->where('status', 1)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">المكافآت المعطلة</span>
                    <span class="info-box-number">{{ $bonuses->where('status', 0)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-history"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر مكافأة تمت إضافتها</span>
                    <span class="info-box-number">{{ $lastBonusLabel }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول المكافآت
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.bonuses.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة مكافأة
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
                        @forelse ($bonuses as $bonus)
                        <tr>
                            <td>{{ $bonus->id }}</td>
                            <td>{{ $bonus->name }}</td>
                            <td>
                                @if($bonus->status == 1)
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
                            <td>{{ $bonus->company_id }}</td>
                            <td>{{ optional($bonus->addedBy)->name ?? '---' }}</td>
                            <td>{{ optional($bonus->updatedBy)->name ?? '---' }}</td>
                            <td>{{ $bonus->created_at }}</td>
                            <td>{{ $bonus->updated_at }}</td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-1">

                                    <a href="{{ route('admin.bonuses.edit', $bonus->id) }}"
                                        class="btn btn-sm btn-warning m-1" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.bonuses.destroy', $bonus->id) }}"
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
                                    لا توجد بيانات مكافآت حالياً
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
                {{-- Pagination --}}
            </div>
            <div>
                {{ $bonuses->links() }}
            </div>
        </div>
    </div>
</div>

@section('js')
@endsection

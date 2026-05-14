<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-code-branch"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد الفروع</span>
                    <span class="info-box-number">{{ $branches->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الفروع المفعلة</span>
                    <span class="info-box-number">
                        {{ $branches->where('status', 1)->count() }}
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
                    <span class="info-box-text">الفروع المعطلة</span>
                    <span class="info-box-number">
                        {{ $branches->where('status', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر فرع تم إضافته</span>
                    <span class="info-box-number">
                        {{ optional($branches->last())->name ?? '---' }}
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
                جدول الفروع
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.branches.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة فرع
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
                            <th>اسم الفرع</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>البريد الإلكتروني</th>
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
                        @forelse ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>

                                <td>
                                        {{ $branch->name }}
                                </td>

                                <td>{{ $branch->address }}</td>

                                <td>
                                        {{ $branch->phone }}
                                </td>

                                <td>
                                    @if($branch->email)
                                            {{ $branch->email }}
                                    @else
                                        <span class="text-muted">---</span>
                                    @endif
                                </td>

                                <td>
                                    @if($branch->status == 1)
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

                                <td>{{ $branch->company_id }}</td>

                                <td>{{ optional($branch->createdBy)->name ?? '---' }}</td>

                                <td>{{ optional($branch->updatedBy)->name ?? '---' }}</td>

                                <td>{{ $branch->created_at }}</td>

                                <td>{{ $branch->updated_at }}</td>

                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-1">

                                        <a href="{{ route('admin.branches.edit', $branch->id) }}"
                                            class="btn btn-sm btn-warning m-1"
                                            title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.branches.destroy', $branch->id) }}"
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
                                        لا توجد بيانات فروع حاليا
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            {{ $branches->links() }}

        </div>
    </div>
</div>

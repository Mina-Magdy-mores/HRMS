<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users-cog"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد الحسابات</span>
                    <span class="info-box-number">{{ $admins->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الحسابات المفعّلة</span>
                    <span class="info-box-number">
                        {{ $admins->where('status', 1)->count() }}
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
                    <span class="info-box-text">الحسابات المعطّلة</span>
                    <span class="info-box-number">
                        {{ $admins->where('status', 0)->count() }}
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
                    <span class="info-box-text">آخر مستخدم تم إضافته</span>
                    <span class="info-box-number">
                        {{ optional($admins->last())->name ?? '---' }}
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
                جدول المستخدمين
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.admin-profiles.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة مستخدم جديد
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
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>اسم المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور/الصلاحية</th>
                            <th>الهاتف</th>
                            <th>الرقم القومي</th>
                            <th>الجنس</th>
                            <th>دخول النظام</th>
                            <th>الحالة</th>
                            <th>تاريخ الميلاد</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>

                            <td>
                                @if ($admin->image)
                                <img src="{{ asset('storage/' . $admin->image) }}"
                                    alt="{{ $admin->name }}"
                                    class="rounded-circle shadow"
                                    width="45" height="45"
                                    style="object-fit: cover;">
                                @else
                                <span class="badge badge-secondary px-2 py-2">
                                    <i class="fas fa-user"></i>
                                </span>
                                @endif
                            </td>

                            <td>{{ $admin->name }}</td>

                            <td>{{ $admin->username }}</td>

                            <td>
                                @if($admin->email)
                                {{ $admin->email }}
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                @if($admin->is_master_admin)
                                <span class="badge badge-danger">مدير ماستر</span>
                                @else
                                <span class="badge badge-info">{{ $admin->permissionRole->name ?? 'بدون صلاحية' }}</span>
                                @endif
                                @if($admin->is_employee && $admin->employee_id)
                                <br>
                                <span class="badge badge-success mt-1 shadow-sm" title="حساب موظف مرتبط">
                                    <i class="fas fa-id-badge"></i> موظف: {{ $admin->employee->name ?? '---' }}
                                </span>
                                @endif
                            </td>

                            <td>
                                @if($admin->phone)
                                {{ $admin->phone }}
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                @if($admin->national_id)
                                {{ $admin->national_id }}
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                @if($admin->gender == 'male')
                                <span class="badge badge-info px-3 py-2">
                                    <i class="fas fa-mars"></i> ذكر
                                </span>
                                @elseif($admin->gender == 'female')
                                <span class="badge badge-pink px-3 py-2" style="background-color:#e83e8c;color:#fff;">
                                    <i class="fas fa-venus"></i> أنثى
                                </span>
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>
                                @if($admin->is_employee == 1)
                                    @if($admin->allow_login == 1)
                                    <span class="badge badge-success px-3 py-2">
                                        <i class="fas fa-sign-in-alt"></i> مسموح بالدخول
                                    </span>
                                    @else
                                    <span class="badge badge-danger px-3 py-2">
                                        <i class="fas fa-ban"></i> حظر الدخول
                                    </span>
                                    @endif
                                @else
                                    <span class="badge badge-info px-3 py-2">
                                        <i class="fas fa-user-shield"></i> مدير النظام
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($admin->status == 1)
                                <span class="badge badge-success px-3 py-2">
                                    <i class="fas fa-check-circle"></i>
                                    مفعّل
                                </span>
                                @else
                                <span class="badge badge-danger px-3 py-2">
                                    <i class="fas fa-times-circle"></i>
                                    معطّل
                                </span>
                                @endif
                            </td>

                            <td>
                                @if($admin->birth_date)
                                {{ $admin->birth_date }}
                                @else
                                <span class="text-muted">---</span>
                                @endif
                            </td>

                            <td>{{ $admin->created_at }}</td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-1">

                                    <a href="{{ route('admin.admin-profiles.archive', $admin->id) }}"
                                        class="btn btn-sm btn-info m-1" title="عرض الأرشيف">
                                        <i class="fas fa-history"></i>
                                    </a>

                                    <a href="{{ route('admin.admin-profiles.edit', $admin->id) }}"
                                        class="btn btn-sm btn-warning m-1" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($admin->id !== auth()->id())
                                    <form action="{{ route('admin.admin-profiles.destroy', $admin->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1"
                                            title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle"></i>
                                    لا توجد بيانات أدمنية حالياً
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            {{ $admins->links() }}

        </div>
    </div>
</div>

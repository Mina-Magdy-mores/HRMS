<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users-cog"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الأدوار</span>
                    <span class="info-box-number">{{ $roles->total() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الأدوار النشطة</span>
                    <span class="info-box-number">{{ $roles->where('is_active', 1)->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">المستخدم الحالي</span>
                    <span class="info-box-number">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-id-badge"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">كود الشركة</span>
                    <span class="info-box-number">{{ auth()->user()->company_id }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول عرض أدوار وصلاحيات المستخدمين
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission-roles.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة دور جديد
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
                            <th>اسم الدور الصلاحياتي</th>
                            <th>عدد المستخدمين المرتبطين</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->admins_count }}</td>
                            <td>
                                @if($role->is_active == 1)
                                <span class="badge badge-success px-2 py-1">مفعل</span>
                                @else
                                <span class="badge badge-danger px-2 py-1">معطل</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.permission-roles.edit', $role->id) }}" class="btn btn-sm btn-info shadow-sm">
                                    <i class="fas fa-edit"></i> تعديل الصلاحيات
                                </a>
                                <form action="{{ route('admin.permission-roles.destroy', $role->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذا الدور الصلاحياتي بالكامل؟')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">لا توجد سجلات بعد.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</div>

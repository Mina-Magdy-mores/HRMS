<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-running"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي الحركات</span>
                    <span class="info-box-number">{{ $actions->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">المستخدم الحالي</span>
                    <span class="info-box-number">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-user-shield"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الحالة</span>
                    <span class="info-box-number">كامل الصلاحيات</span>
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
                <i class="fas fa-running text-primary mr-1"></i>
                جدول حركات القوائم الفرعية
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission-sub-menu-actions.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة حركة جديدة
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
                            <th style="width: 10%">#</th>
                            <th style="width: 50%" class="text-right pr-5">اسم الحركة (العملية)</th>
                            <th style="width: 20%">الحالة</th>
                            <th style="width: 20%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentSubMenu = null; @endphp
                        @forelse ($actions as $action)
                            @if ($action->subMenu && $currentSubMenu !== $action->subMenu->name)
                                @php $currentSubMenu = $action->subMenu->name; @endphp
                                <tr class="bg-light text-right">
                                    <td colspan="4" class="py-2 pr-4 font-weight-bold text-dark" style="font-size: 1.1rem; border-top: 2px solid #007bff; border-bottom: 2px solid #dee2e6;">
                                        <div class="d-flex align-items-center justify-content-start" style="gap: 8px;">
                                            <span class="badge badge-secondary py-2 px-3 shadow-sm font-weight-normal text-dark" style="background-color: #e9ecef; border: 1px solid #ced4da; font-size: 0.95rem;">
                                                <i class="fas fa-folder text-warning ml-1"></i>
                                                {{ $action->subMenu->mainMenu ? $action->subMenu->mainMenu->name : '' }}
                                            </span>
                                            <i class="fas fa-angle-double-left text-muted" style="font-size: 0.9rem;"></i>
                                            <span class="badge badge-info py-2 px-3 shadow-sm font-weight-normal text-white" style="background-color: #17a2b8; font-size: 0.95rem;">
                                                <i class="fas fa-link ml-1"></i>
                                                {{ $currentSubMenu }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>{{ $action->id }}</td>
                                <td class="text-right pr-5 font-weight-bold text-secondary">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <span>
                                            <i class="fas fa-angle-left text-muted mr-1"></i>
                                            {{ $action->name }}
                                        </span>
                                        <form action="{{ route('admin.permission-sub-menu-actions.destroy', $action->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger px-2 py-1 shadow-sm font-weight-normal text-white" style="font-size: 0.8rem;" onclick="return confirm('هل أنت متأكد من حذف هذه الحركة؟')">
                                                <i class="fas fa-trash-alt"></i> مسح
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    @if($action->is_active == 1)
                                    <span class="badge badge-success px-2 py-1">مفعل</span>
                                    @else
                                    <span class="badge badge-danger px-2 py-1">معطل</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.permission-sub-menu-actions.edit', $action->id) }}" class="btn btn-sm btn-info shadow-sm">
                                        <i class="fas fa-edit"></i> تعديل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">لا توجد سجلات بعد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

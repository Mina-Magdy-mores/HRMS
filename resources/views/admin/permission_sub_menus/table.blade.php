<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-list-ul"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">إجمالي القوائم الفرعية</span>
                    <span class="info-box-number">{{ $submenus->count() }}</span>
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

    <!-- Header Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-dark font-weight-bold m-0">
            <i class="fas fa-list-ul text-primary mr-2"></i>
            هيكلية القوائم الفرعية
        </h4>
        <a href="{{ route('admin.permission-sub-menus.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle"></i>
            إضافة قائمة فرعية جديدة
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
        <button type="button" class="close text-white text-right" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4">
        <i class="fas fa-times-circle"></i>
        {{ session('error') }}
        <button type="button" class="close text-white text-right" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Accordion List Layout -->
    <div class="accordion" id="submenusAccordion">
        @foreach($submenus->groupBy('permission_main_menu_id') as $mainMenuId => $groupedSubs)
            @php $mainMenu = $groupedSubs->first()->mainMenu; @endphp
            <div class="card card-outline card-primary mb-3 shadow-sm border">
                <div class="card-header bg-light collapsed d-flex justify-content-between align-items-center py-3" 
                     data-toggle="collapse" data-target="#collapse_{{ $mainMenuId }}" style="cursor: pointer; user-select: none;">
                    <h5 class="m-0 font-weight-bold text-dark" style="font-size: 1.1rem;">
                        <i class="fas fa-folder-open text-warning mr-2"></i>
                        {{ $mainMenu ? $mainMenu->name : 'غير محدد' }}
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="badge badge-info px-3 py-1 font-weight-bold mr-3" style="font-size: 0.8rem;">
                            {{ $groupedSubs->count() }} قوائم فرعية
                        </span>
                        <i class="fas fa-chevron-down text-muted transition-icon"></i>
                    </div>
                </div>
                <div id="collapse_{{ $mainMenuId }}" class="collapse" data-parent="#submenusAccordion">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush mb-0">
                            @foreach($groupedSubs as $submenu)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <span class="font-weight-bold text-primary" style="font-size: 1rem;">
                                        <i class="fas fa-link text-muted mr-2"></i>
                                        {{ $submenu->name }}
                                    </span>
                                    <div class="d-flex align-items-center">
                                        @if($submenu->is_active == 1)
                                            <span class="badge badge-success px-2 py-1 mr-3">مفعل</span>
                                        @else
                                            <span class="badge badge-danger px-2 py-1 mr-3">معطل</span>
                                        @endif
                                        <a href="{{ route('admin.permission-sub-menus.edit', $submenu->id) }}" class="btn btn-sm btn-info shadow-sm mr-1">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.permission-sub-menus.destroy', $submenu->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('هل أنت متأكد من حذف هذه القائمة الفرعية وحركاتها التابعة؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .card-header .transition-icon {
        transition: transform 0.2s ease-in-out;
    }
    .card-header.collapsed .transition-icon {
        transform: rotate(-90deg);
    }
</style>

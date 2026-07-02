<div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users-cog"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">تعديل دور الصلاحية</span>
                    <span class="info-box-number">{{ $role->name }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                @if($role->is_active == 1)
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                @else
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                @endif
                <div class="info-box-content">
                    <span class="info-box-text">الحالة الحالية</span>
                    <span class="info-box-number">{{ $role->is_active == 1 ? 'مفعل' : 'معطل' }}</span>
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
                <i class="fas fa-user-edit"></i>
                تعديل الصلاحيات للدور
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission-roles.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>
        </div>

        <form action="{{ route('admin.permission-roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h5>
                        <i class="fas fa-exclamation-circle"></i>
                        يوجد أخطاء في البيانات
                    </h5>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-times-circle"></i>
                    {{ session('error') }}
                </div>
                @endif

                <!-- بيانات الدور الأساسية -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-4 text-primary border-bottom pb-2">
                            <i class="fas fa-info-circle"></i>
                            البيانات الأساسية للدور
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>اسم الدور الصلاحياتي <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $role->name) }}"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="مثال: مسؤول موارد بشرية، محاسب...">
                            @include('admin.errors.errors', ['value' => 'name'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الحالة <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-control {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                                <option value="1" {{ old('is_active', $role->is_active) == 1 ? 'selected' : '' }}>مفعل</option>
                                <option value="0" {{ old('is_active', $role->is_active) == 0 ? 'selected' : '' }}>معطل</option>
                            </select>
                            @include('admin.errors.errors', ['value' => 'is_active'])
                        </div>
                    </div>
                </div>

                <!-- شجرة الصلاحيات -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-primary border-bottom pb-2">
                            <i class="fas fa-key"></i>
                            تحديد الصلاحيات للقسم والقوائم والحركات
                        </h5>
                    </div>

                    <!-- زري تحديد وإلغاء الكل -->
                    <div class="col-12 mb-4 d-flex justify-content-start align-items-center" style="gap: 10px;">
                        <button type="button" id="select_all_btn" class="btn btn-outline-primary btn-sm shadow-sm font-weight-bold">
                            <i class="fas fa-check-double mr-1"></i> تحديد الكل (كامل الصلاحيات)
                        </button>
                        <button type="button" id="deselect_all_btn" class="btn btn-outline-danger btn-sm shadow-sm font-weight-bold">
                            <i class="fas fa-times mr-1"></i> إلغاء تحديد الكل
                        </button>
                    </div>

                    @foreach($mainMenus as $mainMenu)
                        <div class="col-12 mb-4">
                            <div class="card card-outline card-primary shadow-sm">
                                <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="permissions_main[]" value="{{ $mainMenu->id }}" 
                                               id="main_menu_{{ $mainMenu->id }}" class="custom-control-input main-menu-checkbox"
                                               {{ is_array(old('permissions_main', $assignedMain)) && in_array($mainMenu->id, old('permissions_main', $assignedMain)) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold text-dark mb-0" for="main_menu_{{ $mainMenu->id }}" style="font-size: 1.1rem; cursor: pointer;">
                                            <i class="fas fa-folder-open text-warning mr-1"></i>
                                            {{ $mainMenu->name }}
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-xs btn-outline-primary select-main-group-btn" data-main="{{ $mainMenu->id }}" style="font-size: 0.75rem;">
                                        <i class="fas fa-check-square mr-1"></i> تحديد كل المجموعة
                                    </button>
                                </div>
                                <div class="card-body p-0 main-menu-body" id="main_menu_body_{{ $mainMenu->id }}" style="{{ is_array(old('permissions_main', $assignedMain)) && in_array($mainMenu->id, old('permissions_main', $assignedMain)) ? '' : 'display: none;' }}">
                                    <ul class="list-group list-group-flush mb-0">
                                        @foreach($mainMenu->subMenus as $subMenu)
                                            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center py-3">
                                                <div class="d-flex flex-column mb-2 mb-md-0" style="min-width: 250px;">
                                                    <div class="custom-control custom-checkbox mb-1">
                                                        <input type="checkbox" name="permissions_sub[]" value="{{ $subMenu->id }}"
                                                               id="sub_menu_{{ $subMenu->id }}" class="custom-control-input sub-menu-checkbox"
                                                               data-main="{{ $mainMenu->id }}"
                                                               {{ is_array(old('permissions_sub', $assignedSub)) && in_array($subMenu->id, old('permissions_sub', $assignedSub)) ? 'checked' : '' }}>
                                                        <label class="custom-control-label font-weight-bold text-primary" for="sub_menu_{{ $subMenu->id }}" style="cursor: pointer;">
                                                            <i class="fas fa-link text-muted mr-1"></i>
                                                            {{ $subMenu->name }}
                                                        </label>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-xs btn-outline-info py-0 px-2 select-sub-group-btn" data-sub="{{ $subMenu->id }}" style="font-size: 0.7rem;">
                                                            <i class="fas fa-check mr-1"></i> تحديد كل الحركات
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap align-items-center sub-menu-actions-body" id="sub_menu_actions_{{ $subMenu->id }}" style="{{ is_array(old('permissions_sub', $assignedSub)) && in_array($subMenu->id, old('permissions_sub', $assignedSub)) ? 'display: flex; gap: 15px;' : 'display: none; gap: 15px;' }}">
                                                    @foreach($subMenu->actions as $action)
                                                        <div class="custom-control custom-checkbox mr-4 mb-1">
                                                            <input type="checkbox" name="permissions_action[]" value="{{ $action->id }}"
                                                                   id="action_{{ $action->id }}" class="custom-control-input action-checkbox"
                                                                   data-main="{{ $mainMenu->id }}" data-sub="{{ $subMenu->id }}"
                                                                   {{ is_array(old('permissions_action', $assignedActions)) && in_array($action->id, old('permissions_action', $assignedActions)) ? 'checked' : '' }}>
                                                            <label class="custom-control-label text-secondary font-weight-normal" for="action_{{ $action->id }}">
                                                                {{ $action->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
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

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    تعديل البيانات
                </button>
                <a href="{{ route('admin.permission-roles.index') }}" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

@section('js')
<script>
    $(document).ready(function() {
        // Select All Button
        $('#select_all_btn').on('click', function() {
            $('input[type="checkbox"].main-menu-checkbox, input[type="checkbox"].sub-menu-checkbox, input[type="checkbox"].action-checkbox').prop('checked', true);
            $('.main-menu-body').slideDown();
            $('.sub-menu-actions-body').slideDown(function() {
                $(this).css('display', 'flex'); // Preserve flex layout
            });
        });

        // Deselect All Button
        $('#deselect_all_btn').on('click', function() {
            $('input[type="checkbox"].main-menu-checkbox, input[type="checkbox"].sub-menu-checkbox, input[type="checkbox"].action-checkbox').prop('checked', false);
            $('.main-menu-body, .sub-menu-actions-body').slideUp();
        });

        // Select All under a specific Main Menu
        $('.select-main-group-btn').on('click', function(e) {
            e.preventDefault();
            let mainId = $(this).data('main');
            $(`#main_menu_${mainId}`).prop('checked', true);
            $(`#main_menu_body_${mainId}`).slideDown();
            $(`.sub-menu-checkbox[data-main="${mainId}"]`).prop('checked', true);
            $(`.action-checkbox[data-main="${mainId}"]`).prop('checked', true);
            $(`.sub-menu-checkbox[data-main="${mainId}"]`).each(function() {
                let subId = $(this).val();
                $(`#sub_menu_actions_${subId}`).slideDown(function() {
                    $(this).css('display', 'flex');
                });
            });
        });

        // Select All under a specific Sub Menu
        $('.select-sub-group-btn').on('click', function(e) {
            e.preventDefault();
            let subId = $(this).data('sub');
            $(`#sub_menu_${subId}`).prop('checked', true);
            $(`#sub_menu_actions_${subId}`).slideDown(function() {
                $(this).css('display', 'flex');
            });
            $(`.action-checkbox[data-sub="${subId}"]`).prop('checked', true);
            
            let mainId = $(`#sub_menu_${subId}`).data('main');
            $(`#main_menu_${mainId}`).prop('checked', true);
            $(`#main_menu_body_${mainId}`).slideDown();
        });

        // 1. When checking a main menu
        $('.main-menu-checkbox').on('change', function() {
            let mainId = $(this).val();
            let checked = $(this).is(':checked');
            
            if (checked) {
                $(`#main_menu_body_${mainId}`).slideDown();
            } else {
                $(`#main_menu_body_${mainId}`).slideUp();
                // Uncheck all submenus and actions under this main menu silently
                $(`.sub-menu-checkbox[data-main="${mainId}"]`).prop('checked', false);
                $(`.action-checkbox[data-main="${mainId}"]`).prop('checked', false);
                $(`.sub-menu-actions-body[id^="sub_menu_actions_"]`).slideUp();
            }
        });

        // 2. When checking a sub menu
        $('.sub-menu-checkbox').on('change', function() {
            let subId = $(this).val();
            let mainId = $(this).data('main');
            let checked = $(this).is(':checked');
            
            if (checked) {
                $(`#sub_menu_actions_${subId}`).slideDown(function() {
                    $(this).css('display', 'flex'); // Preserve flexbox layout
                });
                $(`#main_menu_${mainId}`).prop('checked', true);
            } else {
                $(`#sub_menu_actions_${subId}`).slideUp();
                // Uncheck all actions under this submenu
                $(`.action-checkbox[data-sub="${subId}"]`).prop('checked', false);
            }
        });

        // 3. When checking an action
        $('.action-checkbox').on('change', function() {
            let actionId = $(this).val();
            let subId = $(this).data('sub');
            let mainId = $(this).data('main');
            let checked = $(this).is(':checked');

            if (checked) {
                $(`#sub_menu_${subId}`).prop('checked', true);
                $(`#main_menu_${mainId}`).prop('checked', true);
            }
        });
    });
</script>
@endsection

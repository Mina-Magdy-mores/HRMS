<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <div class="d-flex justify-content-around align-items-center">
            <span class="badge bg-info">HRM</span>
            <span class="brand-text font-weight-light">HRMS V1</span>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <a href="{{ route('admin.profile.edit') }}">
                    @if(auth()->user()->image)
                        <img src="{{ asset('storage/' . auth()->user()->image) }}" class="img-circle elevation-2"
                            alt="{{ auth()->user()->name }}" style="object-fit: cover; width:35px; height:35px;">
                    @else
                        <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                            alt="User Image">
                    @endif
                </a>
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit') }}" class="d-block font-weight-bold text-light" title="تعديل الملف الشخصي">
                    {{ auth()->user()->name ?? 'Admin' }}
                    <span class="d-block text-xs text-muted font-weight-normal"><i class="fas fa-cog text-xs mr-1"></i> تعديل البروفايل</span>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                
                @if(check_main_menu_permission('قائمة الضبط'))
                <li
                    class="nav-item has-treeview  {{ request()->is('admin/general-settings*') ||
                    request()->routeIs('admin.financeCalendars.*') ||
                    request()->routeIs('admin.branches.*') ||
                    request()->routeIs('admin.shifts-types.*') ||
                    request()->routeIs('admin.departments.*') ||
                    request()->routeIs('admin.jobCategories.*') ||
                    request()->routeIs('admin.qualifications.*') ||
                    request()->routeIs('admin.occasions.*') ||
                    request()->routeIs('admin.resignations.*') ||
                    request()->routeIs('admin.nationalities.*') ||
                    request()->routeIs('admin.religions.*') ||
                    request()->routeIs('admin.blood-groups.*') ||
                    request()->routeIs('admin.vacation-types.*') ||
                    request()->routeIs('admin.countries.*') ||
                    request()->routeIs('admin.governorates.*') ||
                    request()->routeIs('admin.cities.*')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('admin/general-settings*') ||
                        request()->routeIs('admin.financeCalendars.*') ||
                        request()->routeIs('admin.branches.*') ||
                        request()->routeIs('admin.shifts-types.*') ||
                        request()->routeIs('admin.departments.*') ||
                        request()->routeIs('admin.jobCategories.*') ||
                        request()->routeIs('admin.qualifications.*') ||
                        request()->routeIs('admin.occasions.*') ||
                        request()->routeIs('admin.resignations.*') ||
                        request()->routeIs('admin.nationalities.*') ||
                        request()->routeIs('admin.religions.*') ||
                        request()->routeIs('admin.blood-groups.*') ||
                        request()->routeIs('admin.vacation-types.*') ||
                        request()->routeIs('admin.countries.*') ||
                        request()->routeIs('admin.governorates.*') ||
                        request()->routeIs('admin.cities.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt text-warning"></i>
                        <p>
                            قائمة الضبط
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('الضبط العام'))
                        <li class="nav-item">
                            <a href="{{ route('admin.general-settings') }}"
                                class="nav-link @if (request()->is('admin/general-settings*')) active @endif">
                                <i class="fas fa-cogs text-info"></i>
                                <p>الضبط العام</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('السنوات المالية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.financeCalendars.index') }}"
                                class="nav-link @if (request()->routeIs('admin.financeCalendars.*')) active @endif">
                                <i class="fas fa-calendar text-success"></i>
                                <p>السنوات المالية</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الفروع'))
                        <li class="nav-item">
                            <a href="{{ route('admin.branches.index') }}"
                                class="nav-link @if (request()->routeIs('admin.branches.*')) active @endif">
                                <i class="fas fa-building text-primary"></i>
                                <p>الفروع</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('أنواع الشفتات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.shifts-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.shifts-types.*')) active @endif">
                                <i class="fas fa-clock text-warning"></i>
                                <p>أنواع الشفتات</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('إدارات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.departments.index') }}"
                                class="nav-link @if (request()->routeIs('admin.departments.*')) active @endif">
                                <i class="fas fa-users text-indigo"></i>
                                <p>إدارات الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('تصنيفات الوظائف'))
                        <li class="nav-item">
                            <a href="{{ route('admin.jobCategories.index') }}"
                                class="nav-link @if (request()->routeIs('admin.jobCategories.*')) active @endif">
                                <i class="fas fa-briefcase text-secondary"></i>
                                <p>تصنيفات الوظائف</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('مؤهلات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.qualifications.index') }}"
                                class="nav-link @if (request()->routeIs('admin.qualifications.*')) active @endif">
                                <i class="fas fa-graduation-cap text-light"></i>
                                <p>مؤهلات الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('المناسبات الرسمية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.occasions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.occasions.*')) active @endif">
                                <i class="fas fa-calendar text-danger"></i>
                                <p>المناسبات الرسمية</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('أنواع الإجازات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.vacation-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.vacation-types.*')) active @endif">
                                <i class="fas fa-calendar-alt text-teal"></i>
                                <p>أنواع الإجازات</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('انواع استقالات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.resignations.index') }}"
                                class="nav-link @if (request()->routeIs('admin.resignations.*')) active @endif">
                                <i class="fas fa-sign-out-alt text-danger"></i>
                                <p>انواع استقالات الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الجنسية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.nationalities.index') }}"
                                class="nav-link @if (request()->routeIs('admin.nationalities.*')) active @endif">
                                <i class="fas fa-flag text-warning"></i>
                                <p>الجنسية</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الأديان'))
                        <li class="nav-item">
                            <a href="{{ route('admin.religions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.religions.*')) active @endif">
                                <i class="fas fa-pray text-purple"></i>
                                <p>الأديان</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('فصائل الدم'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blood-groups.index') }}"
                                class="nav-link @if (request()->routeIs('admin.blood-groups.*')) active @endif">
                                <i class="fas fa-tint text-danger"></i>
                                <p>فصائل الدم</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الدول'))
                        <li class="nav-item">
                            <a href="{{ route('admin.countries.index') }}"
                                class="nav-link @if (request()->routeIs('admin.countries.*')) active @endif">
                                <i class="fas fa-globe text-info"></i>
                                <p>الدول</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('المحافظات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.governorates.index') }}"
                                class="nav-link @if (request()->routeIs('admin.governorates.*')) active @endif">
                                <i class="fas fa-map text-success"></i>
                                <p>المحافظات</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('المدن'))
                        <li class="nav-item">
                            <a href="{{ route('admin.cities.index') }}"
                                class="nav-link @if (request()->routeIs('admin.cities.*')) active @endif">
                                <i class="fas fa-city text-primary"></i>
                                <p>المدن</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('قائمة شئون الموظفين'))
                <li
                    class="nav-item has-treeview  {{ request()->routeIs('admin.employees.*') ||
                    request()->routeIs('admin.allowance-types.*') ||
                    request()->routeIs('admin.deduction-types.*') ||
                    request()->routeIs('admin.bonuses.*')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.employees.*') ||
                        request()->routeIs('admin.allowance-types.*') ||
                        request()->routeIs('admin.deduction-types.*') ||
                        request()->routeIs('admin.bonuses.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-users text-primary"></i>
                        <p>
                            قائمة شئون الموظفين
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('بيانات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.employees.index') }}"
                                class="nav-link @if (request()->routeIs('admin.employees.*')) active @endif">
                                <i class="fas fa-users text-primary"></i>
                                <p>بيانات الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('انواع البدل للراتب'))
                        <li class="nav-item">
                            <a href="{{ route('admin.allowance-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.allowance-types.*')) active @endif">
                                <i class="fas fa-hand-holding-usd text-success"></i>
                                <p>انواع البدل للراتب</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('انواع الخصم للراتب'))
                        <li class="nav-item">
                            <a href="{{ route('admin.deduction-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.deduction-types.*')) active @endif">
                                <i class="fas fa-file-invoice-dollar text-danger"></i>
                                <p>انواع الخصم للراتب</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('انواع المكافآت للراتب'))
                        <li class="nav-item">
                            <a href="{{ route('admin.bonuses.index') }}"
                                class="nav-link @if (request()->routeIs('admin.bonuses.*')) active @endif">
                                <i class="fas fa-award text-warning"></i>
                                <p>انواع المكافآت للراتب</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') != 'tasks' ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') != 'tasks' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-circle text-info"></i>
                        <p>
                            ملف الموظف التفصيلي
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'personal', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab', 'personal') == 'personal') active @endif">
                                <i class="fas fa-user-circle text-primary"></i>
                                <p>البيانات المهنية للموظف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'vacation', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'vacation') active @endif">
                                <i class="fas fa-plane-departure text-success"></i>
                                <p>أرصدة إجازات الموظف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'attendance', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'attendance') active @endif">
                                <i class="fas fa-fingerprint text-info"></i>
                                <p>سجل بصمة الموظف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'loans', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'loans') active @endif">
                                <i class="fas fa-hand-holding-usd text-warning"></i>
                                <p>السلف العادية للموظف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'ploans', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'ploans') active @endif">
                                <i class="fas fa-university text-danger"></i>
                                <p>السلف المستديمة للموظف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'salary', 'employee_id' => request()->get('employee_id')]) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'salary') active @endif">
                                <i class="fas fa-history text-secondary"></i>
                                <p>أرشيف رواتب الموظف</p>
                            </a>
                        </li>
                    </ul>
                </li>


                @if(check_main_menu_permission('قائمة أجور الموظفين'))
                <li
                    class="nav-item has-treeview  {{ request()->routeIs('admin.main-salary-records.*') ||
                    request()->routeIs('admin.main-salary-employee-deductions.*') ||
                    request()->routeIs('admin.main-salary-employee-absences.*') ||
                    request()->routeIs('admin.main-salary-employee-additions.*') ||
                    request()->routeIs('admin.main-salary-employee-allowances.*') ||
                    request()->routeIs('admin.main-salary-employee-deduction-types.*') ||
                    request()->routeIs('admin.main-salary-employee-bonuses.*') ||
                    request()->routeIs('admin.main-salary-employee-loans.*') ||
                    request()->routeIs('admin.main-salary-employee-ploans.*') ||
                    request()->routeIs('admin.main-salary-employee.*')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.main-salary-records.*') ||
                        request()->routeIs('admin.main-salary-employee-deductions.*') ||
                        request()->routeIs('admin.main-salary-employee-absences.*') ||
                        request()->routeIs('admin.main-salary-employee-additions.*') ||
                        request()->routeIs('admin.main-salary-employee-allowances.*') ||
                        request()->routeIs('admin.main-salary-employee-deduction-types.*') ||
                        request()->routeIs('admin.main-salary-employee-bonuses.*') ||
                        request()->routeIs('admin.main-salary-employee-loans.*') ||
                        request()->routeIs('admin.main-salary-employee-ploans.*') ||
                        request()->routeIs('admin.main-salary-employee.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-wallet text-success"></i>
                        <p>
                            قائمة أجور الموظفين
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('بيانات رواتب الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-records.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-records.*')) active @endif ">
                                <i class="fas fa-users text-secondary"></i>
                                <p>بيانات رواتب الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الجزاءات اليدويه'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-deductions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-deductions.*')) active @endif ">
                                <i class="fas fa-gavel text-danger"></i>
                                <p>الجزاءات اليدويه</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('خصم الغياب اليدوي'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-absences.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-absences.*')) active @endif ">
                                <i class="fas fa-calendar-times text-warning"></i>
                                <p>خصم الغياب اليدوي</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('أضافه الأيام اليدوي'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-additions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-additions.*')) active @endif ">
                                <i class="fas fa-calendar-plus text-success"></i>
                                <p>أضافه الأيام اليدوي</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('الخصومات المالية المسجلة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-deduction-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-deduction-types.*')) active @endif ">
                                <i class="fas fa-minus-circle text-danger"></i>
                                <p>الخصومات المالية المسجلة</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('المكافئات المالية المسجلة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-bonuses.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-bonuses.*')) active @endif ">
                                <i class="fas fa-trophy text-warning"></i>
                                <p>المكافئات المالية المسجلة</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('البدلات المالية المسجلة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-allowances.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-allowances.*')) active @endif ">
                                <i class="fas fa-plus-circle text-info"></i>
                                <p>البدلات المالية المسجلة</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('السلف الشهرية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-loans.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-loans.*')) active @endif ">
                                <i class="fas fa-hand-holding-usd text-success"></i>
                                <p>السلف الشهرية</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('السلف المستديمة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-ploans.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-ploans.*')) active @endif ">
                                <i class="fas fa-coins text-warning"></i>
                                <p>السلف المستديمة</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('رواتب الموظفين مفصله'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee.*')) active @endif ">
                                <i class="fas fa-print text-primary"></i>
                                <p>رواتب الموظفين مفصله</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('قائمة التسويات'))
                <li
                    class="nav-item has-treeview  {{ request()->routeIs('admin.main-salary-employee-settlements.*') ||
                        request()->routeIs('admin.salary-grant-types.*') ||
                        request()->routeIs('admin.direct-bonuses.*') ||
                        request()->routeIs('admin.direct-grants.*')
                            ? 'menu-open'
                            : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.main-salary-employee-settlements.*') ||
                            request()->routeIs('admin.salary-grant-types.*') ||
                            request()->routeIs('admin.direct-bonuses.*') ||
                            request()->routeIs('admin.direct-grants.*')
                                ? 'active'
                                : '' }}">
                        <i class="nav-icon fas fa-balance-scale text-info"></i>
                        <p>
                            قائمة التسويات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('تسويات رواتب الموظفين المؤرشفة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-settlements.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-settlements.*')) active @endif ">
                                <i class="fas fa-hand-holding-usd text-primary"></i>
                                <p>تسويات رواتب الموظفين</p>
                            </a>
                        </li>
                        @endif

                        @if(check_sub_menu_permission('أنواع منح الرواتب'))
                        <li class="nav-item">
                            <a href="{{ route('admin.salary-grant-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.salary-grant-types.*')) active @endif ">
                                <i class="fas fa-gift text-info"></i>
                                <p>أنواع منح الرواتب</p>
                            </a>
                        </li>
                        @endif

                        @if(check_sub_menu_permission('المكافئات المباشرة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.direct-bonuses.index') }}"
                                class="nav-link @if (request()->routeIs('admin.direct-bonuses.*')) active @endif ">
                                <i class="fas fa-hand-holding-usd text-success"></i>
                                <p>المكافئات المباشرة</p>
                            </a>
                        </li>
                        @endif

                        @if(check_sub_menu_permission('المنح المباشرة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.direct-grants.index') }}"
                                class="nav-link @if (request()->routeIs('admin.direct-grants.*')) active @endif ">
                                <i class="fas fa-hand-holding-usd text-warning"></i>
                                <p>المنح المباشرة</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('الحضور والانصراف'))
                <li
                    class="nav-item has-treeview  {{ request()->routeIs('admin.attendanceDepartures.*') ||
                    request()->routeIs('admin.main-employees-vacations-balances.*')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.attendanceDepartures.*') ||
                        request()->routeIs('admin.main-employees-vacations-balances.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-fingerprint text-danger"></i>
                        <p>
                            الحضور والانصراف
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('سجلات البصمات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.attendanceDepartures.index') }}"
                                class="nav-link @if (request()->routeIs('admin.attendanceDepartures.*')) active @endif ">
                                <i class="fas fa-fingerprint text-info"></i>
                                <p>سجلات البصمات</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('أرصدة إجازات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-employees-vacations-balances.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-employees-vacations-balances.*')) active @endif">
                                <i class="fas fa-calendar-check text-success"></i>
                                <p>أرصدة إجازات الموظفين</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('التحقيقات الإدارية'))
                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.main-salary-employee-investigations.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.main-salary-employee-investigations.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-search text-info"></i>
                        <p>
                            التحقيقات الإدارية
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('التحقيقات الإدارية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-investigations.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-investigations.*')) active @endif">
                                <i class="fas fa-file-signature text-warning"></i>
                                <p>التحقيقات الإدارية</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('مراقبة النظام'))
                <li class="nav-item has-treeview {{ request()->routeIs('admin.system-monitoring.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.system-monitoring.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-desktop text-warning"></i>
                        <p>
                            مراقبة النظام
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('سجلات النظام العامة'))
                        <li class="nav-item">
                            <a href="{{ route('admin.system-monitoring.index') }}"
                                class="nav-link @if (request()->routeIs('admin.system-monitoring.index')) active @endif">
                                <i class="fas fa-history text-primary"></i>
                                <p>سجلات النظام العامة</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('سجلات المراقبة الذاتية'))
                        <li class="nav-item">
                            <a href="{{ route('admin.system-monitoring.self-logs') }}"
                                class="nav-link @if (request()->routeIs('admin.system-monitoring.self-logs')) active @endif">
                                <i class="fas fa-user-shield text-danger"></i>
                                <p>سجلات المراقبة الذاتية</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <!-- الصلاحيات والأدوار -->
                @if(check_main_menu_permission('الصلاحيات والادوار'))
                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.admin-profiles.*') ||
                    request()->routeIs('admin.permission-roles.*') ||
                    request()->routeIs('admin.permission-main-menus.*') ||
                    request()->routeIs('admin.permission-sub-menus.*') ||
                    request()->routeIs('admin.permission-sub-menu-actions.*')
                        ? 'menu-open'
                        : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.admin-profiles.*') ||
                        request()->routeIs('admin.permission-roles.*') ||
                        request()->routeIs('admin.permission-main-menus.*') ||
                        request()->routeIs('admin.permission-sub-menus.*') ||
                        request()->routeIs('admin.permission-sub-menu-actions.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-shield-alt text-danger"></i>
                        <p>
                            الصلاحيات والأدوار
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('المستخدمين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.admin-profiles.index') }}"
                                class="nav-link @if (request()->routeIs('admin.admin-profiles.*')) active @endif">
                                <i class="fas fa-users text-info"></i>
                                <p>المستخدمين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('ادوار المستخدمين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.permission-roles.index') }}"
                                class="nav-link @if (request()->routeIs('admin.permission-roles.*')) active @endif">
                                <i class="fas fa-user-shield text-success"></i>
                                <p>أدوار المستخدمين</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('القوائم الرئيسيه للصلاحيات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.permission-main-menus.index') }}"
                                class="nav-link @if (request()->routeIs('admin.permission-main-menus.*')) active @endif">
                                <i class="fas fa-list text-warning"></i>
                                <p>القوائم الرئيسية للصلاحيات</p>
                            </a>
                        </li>
                        @endif
                        @if(check_sub_menu_permission('القوائم الفرعيه للصلاحيات'))
                        <li class="nav-item">
                            <a href="{{ route('admin.permission-sub-menus.index') }}"
                                class="nav-link @if (request()->routeIs('admin.permission-sub-menus.*')) active @endif">
                                <i class="fas fa-list-ul text-primary"></i>
                                <p>القوائم الفرعية للصلاحيات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.permission-sub-menu-actions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.permission-sub-menu-actions.*')) active @endif">
                                <i class="fas fa-running text-secondary"></i>
                                <p>حركات القوائم الفرعية</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('قائمة المهام'))
                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.employee-tasks.*') || (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'tasks') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.employee-tasks.*') || (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'tasks') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks text-info"></i>
                        <p>
                            قائمة المهام
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(auth('admin')->user()->is_employee == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-dashboard.index', ['tab' => 'tasks']) }}"
                                class="nav-link @if (request()->routeIs('admin.employee-dashboard.*') && request()->get('tab') == 'tasks') active @endif">
                                <i class="fas fa-tasks text-warning"></i>
                                <p>مهام الموظف</p>
                            </a>
                        </li>
                        @endif
                        @if(auth('admin')->user()->is_employee == 0 && check_sub_menu_permission('مهام الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-tasks.index') }}"
                                class="nav-link @if (request()->routeIs('admin.employee-tasks.*')) active @endif">
                                <i class="fas fa-tasks text-info"></i>
                                <p>مهام الموظفين</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('قائمة الطلبات'))
                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.employee-requests.*') || request()->routeIs('admin.employee-request-types.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.employee-requests.*') || request()->routeIs('admin.employee-request-types.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-signature text-success"></i>
                        <p>
                            قائمة الطلبات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(check_sub_menu_permission('أنواع طلبات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-request-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.employee-request-types.*')) active @endif">
                                <i class="fas fa-list-ul text-warning"></i>
                                <p>أنواع طلبات الموظفين</p>
                            </a>
                        </li>
                        @endif
                        @if(auth('admin')->user()->is_employee == 1 || check_sub_menu_permission('طلبات الموظفين'))
                        <li class="nav-item">
                            <a href="{{ route('admin.employee-requests.index') }}"
                                class="nav-link @if (request()->routeIs('admin.employee-requests.*')) active @endif">
                                <i class="fas fa-file-signature text-success"></i>
                                <p>طلبات الموظفين</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(check_main_menu_permission('المحادثات'))
                <li
                    class="nav-item has-treeview {{ request()->routeIs('admin.chats.*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('admin.chats.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments text-primary"></i>
                        <p>
                            المحادثات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.chats.index') }}"
                                class="nav-link @if (request()->routeIs('admin.chats.*')) active @endif">
                                <i class="fas fa-comment-dots text-info"></i>
                                <p>المحادثات والدردشة</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

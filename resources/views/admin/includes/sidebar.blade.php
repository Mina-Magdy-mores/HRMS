<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name ?? 'Admin' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
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
                        request()->routeIs('admin.countries.*') ||
                        request()->routeIs('admin.governorates.*') ||
                        request()->routeIs('admin.cities.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            قائمة الضبط
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.general-settings') }}"
                                class="nav-link @if (request()->is('admin/general-settings*')) active @endif">
                                <i class="fas fa-cogs"></i>
                                <p>الضبط العام</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.financeCalendars.index') }}"
                                class="nav-link @if (request()->routeIs('admin.financeCalendars.*')) active @endif">
                                <i class="fas fa-calendar"></i>
                                <p>السنوات المالية</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.branches.index') }}"
                                class="nav-link @if (request()->routeIs('admin.branches.*')) active @endif">
                                <i class="fas fa-building"></i>
                                <p>الفروع</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.shifts-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.shifts-types.*')) active @endif">
                                <i class="fas fa-clock"></i>
                                <p>أنواع الشفتات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.departments.index') }}"
                                class="nav-link @if (request()->routeIs('admin.departments.*')) active @endif">
                                <i class="fas fa-users"></i>
                                <p>إدارات الموظفين</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.jobCategories.index') }}"
                                class="nav-link @if (request()->routeIs('admin.jobCategories.*')) active @endif">
                                <i class="fas fa-briefcase"></i>
                                <p>تصنيفات الوظائف</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.qualifications.index') }}"
                                class="nav-link @if (request()->routeIs('admin.qualifications.*')) active @endif">
                                <i class="fas fa-graduation-cap"></i>
                                <p>مؤهلات الموظفين</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.occasions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.occasions.*')) active @endif">
                                <i class="fas fa-calendar"></i>
                                <p>المناسبات الرسمية</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.resignations.index') }}"
                                class="nav-link @if (request()->routeIs('admin.resignations.*')) active @endif">
                                <i class="fas fa-sign-out-alt"></i>
                                <p>انواع استقالات الموظفين</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.nationalities.index') }}"
                                class="nav-link @if (request()->routeIs('admin.nationalities.*')) active @endif">
                                <i class="fas fa-flag"></i>
                                <p>الجنسية</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.religions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.religions.*')) active @endif">
                                <i class="fas fa-pray"></i>
                                <p>الأديان</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.blood-groups.index') }}"
                                class="nav-link @if (request()->routeIs('admin.blood-groups.*')) active @endif">
                                <i class="fas fa-tint"></i>
                                <p>فصائل الدم</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.countries.index') }}"
                                class="nav-link @if (request()->routeIs('admin.countries.*')) active @endif">
                                <i class="fas fa-globe"></i>
                                <p>الدول</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.governorates.index') }}"
                                class="nav-link @if (request()->routeIs('admin.governorates.*')) active @endif">
                                <i class="fas fa-map"></i>
                                <p>المحافظات</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.cities.index') }}"
                                class="nav-link @if (request()->routeIs('admin.cities.*')) active @endif">
                                <i class="fas fa-city"></i>
                                <p>المدن</p>
                            </a>
                        </li>
                    </ul>
                </li>
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
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            قائمة شئون الموظفين
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.employees.index') }}"
                                class="nav-link @if (request()->routeIs('admin.employees.*')) active @endif">
                                <i class="fas fa-users"></i>
                                <p>بيانات الموظفين</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.allowance-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.allowance-types.*')) active @endif">
                                <i class="fas fa-hand-holding-usd"></i>
                                <p>انواع البدل للراتب</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.deduction-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.deduction-types.*')) active @endif">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <p>انواع الخصم للراتب</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.bonuses.index') }}"
                                class="nav-link @if (request()->routeIs('admin.bonuses.*')) active @endif">
                                <i class="fas fa-award"></i>
                                <p>انواع المكافآت للراتب</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li
                    class="nav-item has-treeview  {{ request()->routeIs('admin.main-salary-records.*') ||
                    request()->routeIs('admin.main-salary-employee-deductions.*') ||
                    request()->routeIs('admin.main-salary-employee-absences.*') ||
                    request()->routeIs('admin.main-salary-employee-additions.*') ||
                    request()->routeIs('admin.main-salary-employee-allowances.*') ||
                    request()->routeIs('admin.main-salary-employee-deduction-types.*') ||
                    request()->routeIs('admin.main-salary-employee-bonuses.*') ||
                    request()->routeIs('admin.main-salary-employee-loans.*') ||
                    request()->routeIs('admin.main-salary-employee-ploans.*')
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
                        request()->routeIs('admin.main-salary-employee-ploans.*')
                            ? 'active'
                            : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            قائمة أجور الموظفين
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-records.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-records.*')) active @endif ">
                                <i class="fas fa-users"></i>
                                <p>بيانات رواتب الموظفين</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-deductions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-deductions.*')) active @endif ">
                                <i class="fas fa-gavel"></i>
                                <p>الجزاءات اليدويه</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-absences.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-absences.*')) active @endif ">
                                <i class="fas fa-calendar-times"></i>
                                <p>خصم الغياب اليدوي</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-additions.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-additions.*')) active @endif ">
                                <i class="fas fa-calendar-plus"></i>
                                <p>أضافه الأيام اليدوي</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-deduction-types.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-deduction-types.*')) active @endif ">
                                <i class="fas fa-minus-circle"></i>
                                <p>الخصومات المالية المسجلة</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-bonuses.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-bonuses.*')) active @endif ">
                                <i class="fas fa-trophy"></i>
                                <p>المكافئات المالية المسجلة</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-allowances.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-allowances.*')) active @endif ">
                                <i class="fas fa-plus-circle"></i>
                                <p>البدلات المالية المسجلة</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-loans.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-loans.*')) active @endif ">
                                <i class="fas fa-hand-holding-usd"></i>
                                <p>السلف الشهرية</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-employee-ploans.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-employee-ploans.*')) active @endif ">
                                <i class="fas fa-coins"></i>
                                <p>السلف المستديمة</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.main-salary-records.index') }}"
                                class="nav-link @if (request()->routeIs('admin.main-salary-records.*')) active @endif ">
                                <i class="fas fa-print"></i>
                                <p>رواتب الموظفين مفصله</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

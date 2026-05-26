<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-calendar"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">عدد الشهور</span>
                    <span class="info-box-number">{{ $finanaceMonthlyCalendars->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">الشهور المفعلة</span>
                    <span class="info-box-number">
                        {{ $finanaceMonthlyCalendars->where('status', 1)->count() }}
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
                    <span class="info-box-text">الشهور المغلقة و فى انتظار الفتح</span>
                    <span class="info-box-number">
                        {{ $finanaceMonthlyCalendars->where('status', 0)->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">آخر شهر</span>
                    <span class="info-box-number">
                        {{ optional($finanaceMonthlyCalendars->last())->year_and_month ?? '---' }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <!-- Table Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                الشهور المالية
            </h3>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th>الشهر بالعربية</th>
                            <th>الشهر بالإنجليزية</th>
                            <th>عدد الأيام</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الحالة</th>
                            <th>أضيف بواسطة</th>
                            <th>آخر تحديث بواسطة</th>

                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($finanaceMonthlyCalendars as $month)
                            <tr>

                                <td>
                                    {{ $month->month->name }}
                                </td>
                                <td>
                                    {{ $month->month->name_en }}
                                </td>

                                <td>
                                    {{ $month->number_of_days }}
                                </td>

                                <td>
                                    {{ $month->start_date }}
                                </td>

                                <td>
                                    {{ $month->end_date }}
                                </td>

                                <td>
                                    @if ($month->status == 1)
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-check-circle"></i>
                                            مفعل
                                        </span>
                                    @elseif ($month->status == 2)
                                        <span class="badge badge-danger px-3 py-2">
                                            <i class="fas fa-lock"></i>
                                            مغلق و مؤرشف
                                        </span>
                                    @else
                                        <span class="badge badge-danger px-3 py-2">
                                            <i class="fas fa-times-circle"></i>
                                            مغلق و فى انتظار الفتح
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $month->addedBy->name }}
                                </td>

                                <td>
                                    {{ $month->updatedBy->name }}
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <div class="alert alert-warning mb-0">
                                        لا توجد شهور مالية
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>
</div>

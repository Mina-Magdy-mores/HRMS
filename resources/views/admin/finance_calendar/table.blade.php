<div class="container-fluid">

    <!-- Info Boxes -->
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-calendar"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">عدد السنوات المالية</span>
                    <span class="info-box-number">{{ $finance_calendars->count() }}</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-12">
            <div class="info-box shadow-sm">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">السنوات المفعلة</span>
                    <span class="info-box-number">
                        {{ $finance_calendars->where('status', 1)->count() }}
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
                    <span class="info-box-text">السنوات المعطلة</span>
                    <span class="info-box-number">
                        {{ $finance_calendars->where('status', 0)->count() }}
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
                    <span class="info-box-text">آخر إضافة</span>
                    <span class="info-box-number">
                        {{ optional($finance_calendars->last())->finance_yr ?? '---' }}
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
                جدول السنوات المالية
            </h3>

            <div class="card-tools">

                <a href="{{ route('admin.finance_calendars.create') }}" class="btn btn-primary btn-sm shadow-sm">

                    <i class="fas fa-plus-circle"></i>
                    إضافة سنة مالية
                </a>

            </div>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fas fa-check-circle"></i>

                    {{ session('success') }}

                    <button type="button" class="close text-white" data-dismiss="alert">

                        <span>&times;</span>

                    </button>

                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-bordered table-hover text-center align-middle">

                    <thead class="bg-primary text-white">

                        <tr>

                            <th>#</th>
                            <th>السنة المالية</th>
                            <th>الوصف</th>
                            <th>بداية السنة</th>
                            <th>نهاية السنة</th>
                            <th>الحالة</th>
                            <th>رقم الشركة</th>
                            <th>أضيف بواسطة</th>
                            <th>آخر تحديث بواسطة</th>
                            <th>تاريخ الإضافة</th>
                            <th>تاريخ التحديث</th>
                            <th width="180">الإجراءات</th>

                        </tr>

                    </thead>
                    <tbody>
                        @forelse ($finance_calendars as $finance_calendar)
                            <tr>
                                <td>
                                    {{ $finance_calendar->id }}
                                </td>
                                <td>
                                    <span class="badge badge-primary px-3 py-2">
                                        {{ $finance_calendar->finance_yr }}
                                    </span>
                                </td>
                                <td>
                                    {{ $finance_calendar->finance_yr_desc }}
                                </td>
                                <td>
                                    <span class="badge badge-success px-3 py-2">
                                        {{ $finance_calendar->start_date }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-danger px-3 py-2">
                                        {{ $finance_calendar->end_date }}
                                    </span>
                                </td>
                                <td>
                                    @if ($finance_calendar->status == 1)
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
                                <td>
                                    {{ $finance_calendar->company_id }}
                                </td>
                                <td>
                                    {{ $finance_calendar->added_by }}
                                </td>
                                <td>
                                    {{ $finance_calendar->updated_by ?? '---' }}
                                </td>
                                <td>
                                    {{ $finance_calendar->created_at }}
                                </td>
                                <td>
                                    {{ $finance_calendar->updated_at }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.finance_calendars.edit', $finance_calendar->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.finance_calendars.destroy', $finance_calendar) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('هل أنت متأكد ؟')">
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
                                        لا توجد بيانات سنوات مالية حاليا
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
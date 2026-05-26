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
                    <span class="info-box-number">{{ $financeCalendars->count() }}</span>
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
                        {{ $financeCalendars->where('status', 1)->count() }}
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
                    <span class="info-box-text">السنوات المغلقة و فى انتظار الفتح</span>
                    <span class="info-box-number">
                        {{ $financeCalendars->where('status', 0)->count() }}
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
                    <span class="info-box-text">آخر سنة مالية تم اضافتها</span>
                    <span class="info-box-number">
                        {{ optional($financeCalendars->last())->finance_yr ?? '---' }}
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

                <a href="{{ route('admin.financeCalendars.create') }}" class="btn btn-primary btn-sm shadow-sm">

                    <i class="fas fa-plus-circle"></i>
                    إضافة سنة مالية
                </a>

            </div>

        </div>

        <div class="card-body">

            @if (session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fas fa-check-circle"></i>

                    {{ session('success') }}

                    <button type="button" class="close text-white text-right" data-dismiss="alert">

                        <span>&times;</span>

                    </button>

                </div>

            @endif
            @if (session('error'))

                <div class="alert alert-danger alert-dismissible fade show">

                    <i class="fas fa-times-circle"></i>

                    {{ session('error') }}

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
                            <th>كود الشركه</th>
                            <th>أضيف بواسطة</th>
                            <th>آخر تحديث بواسطة</th>
                            <th>تاريخ الإضافة</th>
                            <th>تاريخ التحديث</th>
                            <th>الإجراءات</th>

                        </tr>

                    </thead>
                    <tbody>
                        @forelse ($financeCalendars as $financeCalendar)
                            <tr>
                                <td>
                                    {{ $financeCalendar->id }}
                                </td>
                                <td>
                                    <span class=" badge badge-primary px-3 py-2">
                                        {{ $financeCalendar->finance_yr }}
                                    </span>
                                </td>
                                <td>
                                    {{ $financeCalendar->finance_yr_desc }}
                                </td>
                                <td>
                                    <span class="badge badge-success px-3 py-2">
                                        {{ $financeCalendar->start_date }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-danger px-3 py-2">
                                        {{ $financeCalendar->end_date }}
                                    </span>
                                </td>
                                <td>
                        @if ($financeCalendar->status == 1)
                            <span class="badge badge-success px-3 py-2">
                                <i class="fas fa-check-circle"></i>
                                مفعل
                            </span>
                        @elseif ($financeCalendar->status == 2)
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
                            {{ $financeCalendar->company_id }}
                        </td>
                        <td>
                            {{ $financeCalendar->addedBy->name }}
                        </td>
                        <td>
                            {{ $financeCalendar->updatedBy->name }}
                        </td>
                        <td>
                            {{ $financeCalendar->created_at }}
                        </td>
                        <td>
                            {{ $financeCalendar->updated_at }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                <button type="button" class="btn btn-sm btn-primary m-1 show_year_monthes"
                                    data-id="{{ $financeCalendar->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if ($financeCalendar->status == 0)
                                    <a href="{{ route('admin.financeCalendars.edit', $financeCalendar->id) }}"
                                        class="btn btn-sm btn-warning m-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                <form action="{{ route('admin.financeCalendars.destroy', $financeCalendar) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger are_you_sure m-1">
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
                {{-- add paginate --}}
                {{ $financeCalendars->links() }}
            </div>
        </div>
    </div>

    <!-- Months Modal (EMPTY BODY) -->
    <div class="modal fade " id="monthsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow">

                <!-- Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-alt"></i>
                        الشهور المالية
                    </h5>

                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- BODY = EMPTY -->
                <div class="modal-body" id="months_modal_body">
                    <!-- AJAX will inject everything here -->
                </div>

            </div>
        </div>
    </div>


    <!-- JavaScript للتحكم في وضع التعديل -->

    @section('js')
        {{-- تفعيل زر عرض الشهور --}}
        <script>
            $(document).ready(function() {

                $(document).on('click', '.show_year_monthes', function() {

                    var id = $(this).data('id');

                    $.ajax({
                        url: `/admin/financeCalendars/${id}/months`,
                        type: 'GET',
                        dataType: 'html',
                        cache: false,
                        success: function(response) {
                            $('#months_modal_body').html(response);
                            $('#monthsModal').modal('show');
                        },
                        error: function(xhr) {
                            alert('حدث خطأ');
                        }
                    });

                });

            });
        </script>
    @endsection

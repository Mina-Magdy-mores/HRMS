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
                    <span class="info-box-number">{{ $financeMonthlyCalendars->count() }}</span>
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
                        {{ $financeMonthlyCalendars->where('status', 1)->count() }}
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
                        {{ $financeMonthlyCalendars->where('status', 0)->count() }}
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
                        {{ optional($financeMonthlyCalendars->last())->year_and_month ?? '---' }}
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
                جدول بيانات الرواتب
            </h3>

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
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            
            <div id="ajax_responce_search">
                <div class="table-responsive">

                    <table class="table table-bordered table-hover text-center align-middle">

                        <thead class="bg-primary text-white">
                            <tr>
                                <th>الشهر</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th>تاريخ البدء لحساب البصمة</th>
                                <th>تاريخ الانتهاء لحساب البصمة</th>
                                <th>عدد الأيام</th>
                                <th>الحالة</th>
                                <th>السنة المالية</th>
                                <th>حالة السنه الماليه</th>
                                <th>الأجرائات</th>


                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($financeMonthlyCalendars as $month)
                                <tr>

                                    <td>
                                        {{ $month->month->name }}
                                    </td>


                                    <td>
                                        {{ $month->start_date }}
                                    </td>

                                    <td>
                                        {{ $month->end_date }}
                                    </td>
                                    <td>
                                        {{ $month->start_date_for_calculation }}
                                    </td>

                                    <td>
                                        {{ $month->end_date_for_calculation }}
                                    </td>

                                    <td>
                                        {{ $month->number_of_days }}
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
                                        {{ $month->finance_yr }}
                                    </td>
                                    <td>
                                        @if ($month->financeCalendar->status == 1)
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fas fa-check-circle"></i>
                                                مفعل
                                            </span>
                                        @elseif ($month->financeCalendar->status == 2)
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
                                        @if (
                                                $month->financeCalendar->status == 1 &&
                                                $month->status == 0 &&
                                                $month->total_prev_months_waiting_to_open == 0 &&
                                                $month->total_opened_months == 0
                                            )
                                            <button data-id="{{ $month->id }}" class="btn load-modal btn-sm btn-primary">
                                                <i class="fa fa-folder-open"></i>
                                                فتح الشهر المالى
                                            </button>
                                        @else
                                        @endif
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
                {{-- Pagination --}}
                <div>
                    {{ $financeMonthlyCalendars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Months Modal (EMPTY BODY) -->
<div class="modal fade " id="loadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow">

            <!-- Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt"></i>
                  فتح الشهر المالى
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <!-- BODY = EMPTY -->
            <div class="modal-body" id="loadModal_body">

            </div>

        </div>
    </div>
</div>
@section('js')
    <script>
        $(document).ready(function () {
            $(document).on('change', '#type-search', function () {
                ajax_search();
            })
            $(document).on('input', '#start_time_search', function () {
                ajax_search();
            })
            $(document).on('input', '#end_time_search', function () {
                ajax_search();
            })
            function ajax_search() {
                var type = $('#type-search').val();
                var start_time = $('#start_time_search').val();
                var end_time = $('#end_time_search').val();
                $.ajax({
                    url: '{{ route('admin.shifts-types.search') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: type,
                        start_time: start_time,
                        end_time: end_time
                    },
                    success: function (financeMonthlyCalendars) {
                        $('#ajax_responce_search').html(financeMonthlyCalendars);
                    },
                    error: function (xhr) {
                        alert('حدث خطأ');
                    }
                });
                $(document).on('click', '#ajax-pagination a', function (e) {
                    e.preventDefault();
                    var type = $('#type-search').val();
                    var start_time = $('#start_time_search').val();
                    var end_time = $('#end_time_search').val();
                    var url = $(this).attr('href');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'html',
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            type: type,
                            start_time: start_time,
                            end_time: end_time
                        },
                        success: function (financeMonthlyCalendars) {
                            $('#ajax_responce_search').html(financeMonthlyCalendars);
                        },
                        error: function (xhr) {
                            alert('حدث خطأ');
                        }
                    });
                })
            }



            $(document).on('click', '.load-modal', function () {
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('admin.main-salary-records.load-open-month') }}',
                    type: 'POST',
                    dataType: 'html',
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (data) {
                        $('#loadModal_body').html(data);
                        $('#loadModal').modal('show');
                    },
                    error: function (xhr) {
                        alert('حدث خطأ');
                    }
                });


            })
        })



    </script>
@endsection

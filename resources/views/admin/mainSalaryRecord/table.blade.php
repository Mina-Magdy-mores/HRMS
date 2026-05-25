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
                    <span class="info-box-text">الشهور المعطلة</span>
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
                جدول نوع الشفتات
            </h3>

            <div class="card-tools">
                <a href="{{ route('admin.shifts-types.create') }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-plus-circle"></i>
                    إضافة نوع شفت
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
                    <button type="button" class="close text-white text-right" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>نوع الشفت</label>
                        <select name="type-search" id="type-search" class="form-control">
                            <option selected disabled value="null">اختر نوع الشفت</option>
                            <option value="1">شفت نهاري</option>
                            <option value="2">شفت ليلي</option>
                            <option value="3">شفت كامل</option>
                        </select>
                    </div>
                </div>

            </div>
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
                                                مفعل
                                            </span>
                                        @else
                                            <span class="badge badge-danger px-3 py-2">
                                                معطل
                                            </span>
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


@section('js')
    <script>
        $(document).ready(function() {
            $(document).on('change', '#type-search', function() {
                ajax_search();
            })
            $(document).on('input', '#start_time_search', function() {
                ajax_search();
            })
            $(document).on('input', '#end_time_search', function() {
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
                    success: function(financeMonthlyCalendars) {
                        $('#ajax_responce_search').html(financeMonthlyCalendars);
                    },
                    error: function(xhr) {
                        alert('حدث خطأ');
                    }
                });
                $(document).on('click', '#ajax-pagination a', function(e) {
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
                        success: function(financeMonthlyCalendars) {
                            $('#ajax_responce_search').html(financeMonthlyCalendars);
                        },
                        error: function(xhr) {
                            alert('حدث خطأ');
                        }
                    });
                })
            }
        })
    </script>
@endsection

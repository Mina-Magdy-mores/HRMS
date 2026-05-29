@if ($financeMonthlyCalendar && $financeMonthlyCalendar->status == 0)
    <!-- Main Card -->
    <div class="card card-primary card-outline shadow">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus-circle"></i>
                تحديد تاريخ سحب بيانات البصمه وفتح شهر مالى جديد
            </h3>
            <div class="card-tools">
                <button data-dismiss="modal" class="btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </button>
            </div>
        </div>

        <form action="{{ route('admin.main-salary-records.open-month', $financeMonthlyCalendar->id) }}" method="POST">
            @csrf
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

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-4 text-primary">
                            <i class="fas fa-calendar-alt"></i>
                            تحديد تاريخ سحب بيانات البصمه وفتح شهر مالى جديد
                        </h5>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>تاريخ بدايه البصمة للشهر</label>
                            <input type="date" name="start_date_for_calculation" value="{{ old('start_date_for_calculation', $financeMonthlyCalendar->start_date_for_calculation) }}"
                                class="form-control {{ $errors->has('start_date_for_calculation') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'start_date_for_calculation'])
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>تاريخ نهاية البصمة للشهر</label>
                            <input type="date" name="end_date_for_calculation" value="{{ old('end_date_for_calculation', $financeMonthlyCalendar->end_date_for_calculation) }}"
                                class="form-control {{ $errors->has('end_date_for_calculation') ? 'is-invalid' : '' }}">
                            @include('admin.errors.errors', ['value' => 'end_date_for_calculation'])
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer text-left">
                <button type="submit" class="btn btn-success shadow px-4">
                    <i class="fas fa-save"></i>
                    حفظ البيانات
                </button>
                <button data-dismiss="modal" class="btn btn-danger shadow px-4">
                    <i class="fas fa-times-circle"></i>
                    إلغاء
                </button>
            </div>
        </form>
    </div>

    </div>

@endif

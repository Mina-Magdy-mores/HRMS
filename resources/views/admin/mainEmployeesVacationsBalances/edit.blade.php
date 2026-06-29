@extends('admin.layouts.admin')

@section('title', 'تعديل رصيد إجازات الموظف')
@section('contentHeader')
    <i class="fas fa-edit"></i>
    تعديل رصيد إجازات الموظف
@endsection

@section('contentHeaderActiveLink')
    <a href="{{ route('admin.main-employees-vacations-balances.index') }}">أرصدة إجازات الموظفين</a>
@endsection
@section('contentHeaderActive', 'تعديل')

@section('content')
    <div class="container-fluid">
        <!-- Employee Info Card -->
        <div class="card card-outline card-info shadow mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-8 text-left">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            <i class="fas fa-user-tie text-info ml-2"></i>
                            الموظف: <span class="text-primary">{{ $employee->name }}</span> (كود:
                            {{ $employee->employee_code ?? '---' }})
                        </h5>
                        <p class="mb-0 text-muted mt-1" style="font-size: 0.9rem;">
                            <strong>الشهر المالي:</strong> {{ $balance->year_and_month ?: '---' }} |
                            <strong>السنة المالية:</strong> {{ $balance->financial_year ?: '---' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-left">
                        <a href="{{ route('admin.main-employees-vacations-balances.show', $employee->id) }}"
                            class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left ml-1"></i> العودة للتفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card card-primary card-outline shadow">
            <div class="card-header">
                <h3 class="card-title text-primary font-weight-bold">
                    <i class="fas fa-edit ml-2"></i>
                    تعديل بيانات الرصيد
                </h3>
            </div>
            
            <form action="{{ route('admin.main-employees-vacations-balances.update', $balance->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="card-body">
                    <!-- Error messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> خطأ في البيانات</h5>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-times-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <!-- carryover_from_previous_month -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">الرصيد المرحل من الشهر السابق</label>
                                <input type="number" step="0.01" min="0" name="carryover_from_previous_month" id="carryover_from_previous_month"
                                    class="form-control {{ $errors->has('carryover_from_previous_month') ? 'is-invalid' : '' }}"
                                    value="{{ old('carryover_from_previous_month', $balance->carryover_from_previous_month) }}" required>
                            </div>
                        </div>

                        <!-- current_month_balance -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">رصيد الشهر الحالي</label>
                                <input type="number" step="0.01" min="0" name="current_month_balance" id="current_month_balance"
                                    class="form-control {{ $errors->has('current_month_balance') ? 'is-invalid' : '' }}"
                                    value="{{ old('current_month_balance', $balance->current_month_balance) }}" required>
                            </div>
                        </div>

                        <!-- spent_balance -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">الرصيد المستهلك (المسحوب)</label>
                                <input type="number" step="0.01" min="0" name="spent_balance" id="spent_balance"
                                    class="form-control {{ $errors->has('spent_balance') ? 'is-invalid' : '' }}"
                                    value="{{ old('spent_balance', $balance->spent_balance) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Calculated summary presentation (dynamic update) -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="info-box bg-light border">
                                <span class="info-box-icon bg-info"><i class="fas fa-calculator"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">إجمالي الرصيد المتاح (المحسوب)</span>
                                    <span class="info-box-number text-info" id="total_available_display" style="font-size: 1.3rem;">
                                        {{ number_format($balance->total_available_balance, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light border">
                                <span class="info-box-icon bg-warning"><i class="fas fa-check-double"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text font-weight-bold">صافي الرصيد المتبقي (المحسوب)</span>
                                    <span class="info-box-number text-warning" id="remaining_net_display" style="font-size: 1.3rem;">
                                        {{ number_format($balance->remaining_net_balance, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-start">
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="fas fa-save ml-1"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.main-employees-vacations-balances.show', $employee->id) }}"
                        class="btn btn-secondary px-4 shadow-sm mr-2">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            function updateCalculations() {
                var carryover = parseFloat($('#carryover_from_previous_month').val()) || 0;
                var current = parseFloat($('#current_month_balance').val()) || 0;
                var spent = parseFloat($('#spent_balance').val()) || 0;
                
                var totalAvailable = carryover + current;
                var remainingNet = totalAvailable - spent;
                
                $('#total_available_display').text(totalAvailable.toFixed(2));
                $('#remaining_net_display').text(remainingNet.toFixed(2));
            }
            
            $('#carryover_from_previous_month, #current_month_balance, #spent_balance').on('input change', function() {
                updateCalculations();
            });
        });
    </script>
@endsection

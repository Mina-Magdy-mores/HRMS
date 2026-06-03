<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $systemData['system_name'] }} - تقرير الخصومات الشهرية المجمعة</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            direction: rtl;
            text-align: right;
            padding: 20px;
        }

        .header-section {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-section img {
            max-height: 80px;
            border-radius: 8px;
        }

        .header-section h1 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }

        .header-section p {
            margin: 5px 0;
            font-size: 14px;
        }

        .report-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #007bff;
            display: inline-block;
            padding-bottom: 5px;
        }

        .summary-box {
            background-color: #fff;
            border-right: 4px solid #28a745;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-box h5 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #495057;
            font-weight: bold;
        }

        .summary-box p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .table-custom {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .table-custom thead {
            background-color: #343a40;
            color: #fff;
        }

        .table-custom th,
        .table-custom td {
            vertical-align: middle;
            text-align: center;
            border: 1px solid #dee2e6;
            padding: 12px;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-custom tbody tr:hover {
            background-color: #e9ecef;
        }

        .badge-custom {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 12px;
        }

        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
            color: #007bff;
        }

        .footer-note {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }

        .print-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }

            .header-section {
                box-shadow: none;
                border: 1px solid #dee2e6;
                color: #000;
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }

            .header-section img {
                border: 1px solid #dee2e6;
            }

            .table-custom th {
                background-color: #343a40 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
            }

            .table-custom td {
                background-color: #fff !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
            }

            .table-custom tbody tr:nth-child(even) td {
                background-color: #f8f9fa !important;
                -webkit-print-color-adjust: exact;
            }

            .total-row td {
                background-color: #e9ecef !important;
                color: #007bff !important;
                -webkit-print-color-adjust: exact;
            }

            .summary-box {
                box-shadow: none;
                border: 1px solid #dee2e6;
                border-right: 4px solid #28a745 !important;
                -webkit-print-color-adjust: exact;
            }

            .print-btn,
            form {
                display: none !important;
            }

            .badge {
                border: 1px solid #000;
                color: #000 !important;
                background-color: transparent !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <!-- Header Section -->
        <div class="header-section d-flex justify-content-between align-items-center">
            <div>
                <h1>{{ $systemData['system_name'] }}</h1>
                <p>العنوان: {{ $systemData['address'] }}</p>
                <p>الهاتف: {{ $systemData['phone'] }} | البريد الإلكتروني: {{ $systemData['email'] }}</p>
            </div>
            <div>
                @if ($systemData['photo'])
                    <img src="{{ asset('assets/admin/uploads/' . $systemData['photo']) }}" alt="شعار الشركة">
                @else
                    <div style="width: 80px; height: 80px; background-color: #fff; border-radius: 8px; display:flex; justify-content:center; align-items:center; color:#007bff; font-weight:bold;">
                        لا يوجد شعار
                    </div>
                @endif
            </div>
        </div>

        <!-- Report Title -->
        <div class="text-center">
            <h2 class="report-title">تقرير الخصومات المالية الشهرية للموظفين</h2>
        </div>

        <!-- Summary Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="summary-box">
                    <h5>معلومات الشهر المالي</h5>
                    <p><strong>الشهر المالي:</strong> {{ $financeMonthlyCalendar->month->name }} لسنة
                        {{ $financeMonthlyCalendar->finance_yr }}</p>
                    <p><strong>تاريخ البدء لحساب البصمة:</strong> {{ $financeMonthlyCalendar->start_date_for_calculation }}</p>
                    <p><strong>تاريخ الانتهاء لحساب البصمة:</strong> {{ $financeMonthlyCalendar->end_date_for_calculation }}</p>
                    <p><strong>إجمالي أيام الشهر:</strong> {{ $financeMonthlyCalendar->number_of_days }} يوم</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="summary-box" style="border-right-color: #007bff;">
                    <h5>ملخص التقرير</h5>
                    <p><strong>تاريخ الطباعة:</strong> {{ date('Y-m-d H:i:s') }}</p>
                    <p><strong>إجمالي عدد سجلات الخصومات المجمعة:</strong> {{ $mainSalaryEmployeeDeductionTypes->count() }} سجل</p>
                    <p class="text-danger font-weight-bold" style="font-size: 16px; margin-top: 5px;">
                        إجمالي مبلغ الخصومات الكلي: {{ number_format($total_amount_sum, 2) }} ج.م
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        @if ($mainSalaryEmployeeDeductionTypes->count() > 0)
            <table class="table table-custom table-bordered">
                <thead>
                    <tr>
                        <th style="width: 5%">م</th>
                        <th style="width: 15%">كود الموظف</th>
                        <th style="width: 20%">أسم الموظف</th>
                        <th style="width: 15%">نوع الخصم</th>
                        <th style="width: 15%">نوع الإضافة</th>
                        <th style="width: 15%">المبلغ (ج.م)</th>
                        <th style="width: 15%">ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($mainSalaryEmployeeDeductionTypes as $deduction)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $deduction->employee->employee_code ?? '---' }}</td>
                            <td class="font-weight-bold">{{ $deduction->employee->name ?? '---' }}</td>
                            <td>
                                <span class="badge badge-info badge-custom">{{ optional($deduction->deductionType)->name ?? '---' }}</span>
                            </td>
                            <td>
                                @if ($deduction->is_auto == 1)
                                    <span class="badge badge-primary badge-custom">تلقائي</span>
                                @else
                                    <span class="badge badge-secondary badge-custom">يدوي</span>
                                @endif
                            </td>
                            <td class="text-danger font-weight-bold">{{ number_format($deduction->amount, 2) }}</td>
                            <td><small class="text-muted">{{ $deduction->notes ?: '---' }}</small></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="5" class="text-left" style="padding-right: 20px;">الإجمالي الكلي:</td>
                        <td class="text-danger font-weight-bold">{{ number_format($total_amount_sum, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="alert alert-warning text-center" role="alert">
                <strong>عفواً!</strong> لا توجد سجلات خصومات مالية مطابقة لمعايير البحث في هذا الشهر.
            </div>
        @endif

        <!-- Footer Note -->
        <div class="footer-note">
            تم إنشاء هذا التقرير تلقائياً من نظام {{ $systemData['system_name'] }} لإدارة الموارد البشرية.
        </div>

        <!-- Print Button -->
        <button class="btn btn-primary btn-lg rounded-circle print-btn" onclick="window.print()" title="طباعة التقرير">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-printer"
                viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                <path
                    d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z" />
            </svg>
        </button>

    </div>

    <!-- Bootstrap JS (Optional, for tooltips/popovers if needed) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

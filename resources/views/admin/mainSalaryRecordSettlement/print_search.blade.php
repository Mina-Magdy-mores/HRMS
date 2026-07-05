<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>طباعة تسويات رواتب الموظفين المؤرشفة</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            font-size: 13px;
            padding: 10px;
            background: #fff;
            color: #222;
        }

        /* ===== HEADER ===== */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .print-header .company-info {
            text-align: right;
        }
        .print-header .company-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .print-header .company-info p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        .print-header .logo img {
            width: 100px;
            height: 80px;
            object-fit: contain;
            border-radius: 8px;
        }

        /* ===== REPORT TITLE ===== */
        .report-title-box {
            text-align: center;
            margin: 10px 0;
            padding: 8px;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: #fff;
            border-radius: 6px;
        }
        .report-title-box h3 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .report-title-box p {
            margin: 3px 0 0;
            font-size: 12px;
            opacity: 0.9;
        }

        /* ===== META INFO ===== */
        .meta-row {
            display: flex;
            justify-content: space-between;
            background: #f0f4f8;
            border: 1px solid #cdd8e3;
            border-radius: 5px;
            padding: 8px 15px;
            margin-bottom: 12px;
            font-size: 12px;
        }
        .meta-row span {
            display: inline-block;
        }
        .meta-row strong {
            color: #2c3e50;
        }

        /* ===== TABLE ===== */
        table.print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 12px;
        }
        table.print-table thead tr {
            background-color: #2c3e50;
            color: #fff;
        }
        table.print-table thead th {
            padding: 7px 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table.print-table tbody td {
            padding: 6px 5px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table.print-table tfoot td {
            padding: 8px 5px;
            font-weight: bold;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }

        /* ===== FOOTER ===== */
        .print-footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        /* ===== PRINT BUTTONS ===== */
        .print-btn-area {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 9999;
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        @media print {
            .print-btn-area {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
        .no-data-msg {
            text-align: center;
            padding: 30px;
            background: #fff8db;
            border: 1px solid #ffe0b2;
            color: #b78103;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    {{-- ===== HEADER ===== --}}
    <div class="print-header">
        <div class="company-info">
            <h2>{{ Auth::user()->company->name ?? 'نظام الموارد البشرية' }}</h2>
            <p>قسم إدارة الأجور والمرتبات</p>
        </div>
        <div class="logo">
            <!-- standard logo or placeholder -->
        </div>
    </div>

    {{-- ===== REPORT TITLE ===== --}}
    <div class="report-title-box">
        <h3>تقرير تسويات رواتب الموظفين المؤرشفة</h3>
        <p>لشهر: {{ $financeMonthlyCalendar->month->name }} لسنة {{ $financeMonthlyCalendar->finance_yr }}</p>
    </div>

    {{-- ===== META INFO ===== --}}
    <div class="meta-row">
        <span>تاريخ الطباعة: <strong>{{ date('Y-m-d H:i') }}</strong></span>
        <span>عدد السجلات المطبوعة: <strong>{{ $mainSalaryEmployeeSettlements->count() }}</strong></span>
    </div>

    @if($mainSalaryEmployeeSettlements->count() > 0)

        <table class="table table-bordered print-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>كود الموظف</th>
                    <th>اسم الموظف</th>
                    <th>أجر اليوم</th>
                    <th>إجمالي الإضافة (+)</th>
                    <th>إجمالي الخصم (-)</th>
                    <th>صافي التسوية</th>
                    <th>أضيف بواسطة</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_addition = 0;
                    $total_deduction = 0;
                    $total_net = 0;
                @endphp
                @foreach($mainSalaryEmployeeSettlements as $settlement)
                @php
                    $total_addition += $settlement->total_amount_for_addition;
                    $total_deduction += $settlement->total_amount_for_deduction;
                    $total_net += $settlement->final_total_amount;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $settlement->employee->employee_code ?? '---' }}</td>
                    <td style="text-align:right;">{{ $settlement->employee->name }}</td>
                    <td>{{ number_format($settlement->employee_per_day_salary, 2) }} ج.م</td>
                    <td style="color:#2ecc71;">+{{ number_format($settlement->total_amount_for_addition, 2) }} ج.م</td>
                    <td style="color:#e74c3c;">-{{ number_format($settlement->total_amount_for_deduction, 2) }} ج.م</td>
                    <td style="font-weight:bold; @if($settlement->final_total_amount >=0) color:#2980b9; @else color:#c0392b; @endif">
                        {{ number_format($settlement->final_total_amount, 2) }} ج.م
                    </td>
                    <td>{{ $settlement->addedBy->name }}</td>
                    <td style="text-align:right; font-size:11px; color:#555;">{{ $settlement->notes ?? '---' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right; padding-right:10px;">الجميل العام</td>
                    <td style="color:#2ecc71;">+{{ number_format($total_addition, 2) }} ج.م</td>
                    <td style="color:#e74c3c;">-{{ number_format($total_deduction, 2) }} ج.م</td>
                    <td style="@if($total_net >=0) color:#2980b9; @else color:#c0392b; @endif">{{ number_format($total_net, 2) }} ج.م</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>

    @else

        <div class="no-data-msg">
            ⚠️ عفواً، لا توجد بيانات لعرضها بناءً على معايير البحث المحددة.
        </div>

    @endif

    <div class="print-btn-area">
        <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">
            🖨️ طباعة
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm mr-2">
            ✖ إغلاق
        </button>
    </div>

</body>

</html>

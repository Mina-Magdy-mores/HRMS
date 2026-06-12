<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>شيت الرواتب التفصيلي لشهر {{ optional(optional($financeMonthlyCalendar)->month)->name }} {{ $financeMonthlyCalendar->finance_yr ?? '' }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Tahoma', 'Arial', sans-serif;
            font-size: 10px;
            padding: 5px;
            background: #fff;
            color: #000;
        }

        /* ===== HEADER ===== */
        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }

        .print-header .company-info h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }

        .print-header .company-info p {
            margin: 1px 0;
            font-size: 10px;
            color: #555;
        }

        .print-header .logo img {
            width: 80px;
            height: 60px;
            object-fit: contain;
        }

        /* ===== REPORT TITLE ===== */
        .report-title-box {
            text-align: center;
            margin: 5px 0;
            padding: 5px;
            background: #2c3e50;
            color: #fff;
            border-radius: 4px;
        }

        .report-title-box h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }

        .report-title-box p {
            margin: 2px 0 0;
            font-size: 10px;
            opacity: 0.9;
        }

        /* ===== META INFO ===== */
        .meta-row {
            display: flex;
            justify-content: space-between;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 5px 10px;
            margin-bottom: 8px;
            font-size: 10px;
        }

        .meta-row strong {
            color: #1e293b;
        }

        /* ===== TABLE ===== */
        table.payroll-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 15px;
        }

        table.payroll-table th,
        table.payroll-table td {
            border: 1px solid #555555;
            padding: 3px 2px;
            text-align: center;
            vertical-align: middle;
        }

        /* Headers colors */
        table.payroll-table thead th {
            font-weight: bold;
            color: #000;
        }

        .hdr-basic {
            background-color: #f2f2f2;
        }

        .hdr-benefits {
            background-color: #e2f0d9; /* Light Green */
        }

        .hdr-deductions {
            background-color: #fce4d6; /* Light Red/Orange */
        }

        .hdr-summary {
            background-color: #fff2cc; /* Light Yellow */
        }

        /* Table body colors */
        table.payroll-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .col-emp-name {
            text-align: right !important;
            font-weight: bold;
            white-space: nowrap;
            padding-right: 4px !important;
        }

        .col-val-benefits {
            color: #1e4620;
            font-weight: 500;
        }

        .col-val-deductions {
            color: #721c24;
            font-weight: 500;
        }

        .col-val-net {
            font-weight: bold;
            font-size: 10.5px;
        }

        .net-positive {
            color: #15803d;
            background-color: #f0fdf4;
        }

        .net-negative {
            color: #b91c1c;
            background-color: #fef2f2;
        }

        .col-sign {
            width: 75px;
            height: 25px;
            font-size: 8px;
            color: #ccc;
        }

        /* Table footer */
        table.payroll-table tfoot tr {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        table.payroll-table tfoot td {
            border-top: 2px double #000;
            font-weight: bold;
        }

        /* ===== FOOTER SIGNATURES ===== */
        .signatures-row {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 15px;
            font-size: 10px;
        }

        .sig-block {
            text-align: center;
            width: 180px;
        }

        .sig-line {
            margin-top: 25px;
            border-top: 1px dotted #000;
            width: 100%;
        }

        /* ===== CONTROLS ===== */
        .print-btn-area {
            text-align: center;
            margin: 10px 0;
        }

        /* ===== PRINT CONFIG ===== */
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        @media print {
            .print-btn-area {
                display: none;
            }

            body {
                padding: 0;
                font-size: 9px;
            }

            table.payroll-table {
                font-size: 8.5px;
            }

            table.payroll-table th,
            table.payroll-table td {
                padding: 2px 1px !important;
            }
        }
    </style>
</head>

<body>

    {{-- ===== HEADER ===== --}}
    <div class="print-header">
        <div class="company-info">
            <h2>{{ $systemData['system_name'] ?? 'النظام' }}</h2>
            <p>📍 {{ $systemData['address'] ?? '' }} — 📞 {{ $systemData['phone'] ?? '' }}</p>
        </div>
        <div class="logo">
            @if (!empty($systemData['photo']))
                <img src="{{ asset('/storage/' . $systemData['photo']) }}" alt="شعار الشركة">
            @endif
        </div>
    </div>

    {{-- ===== REPORT TITLE ===== --}}
    <div class="report-title-box">
        <h3>شيت رواتب الموظفين التفصيلي لشهر: {{ optional(optional($financeMonthlyCalendar)->month)->name }} {{ $financeMonthlyCalendar->finance_yr ?? '' }}</h3>
        <p>مخصص للإدارة العامة لمراجعة الاستحقاقات والاستقطاعات بالكامل لجميع الموظفين</p>
    </div>

    {{-- ===== META INFO ===== --}}
    <div class="meta-row">
        <span>📅 <strong>تاريخ الاستخراج:</strong> @php echo date('Y-m-d'); @endphp</span>
        <span>🕐 <strong>الوقت:</strong> @php echo date('h:i A'); @endphp</span>
        <span>👤 <strong>المستخدم:</strong> {{ auth()->user()->name }}</span>
        <span>👥 <strong>عدد الموظفين المقيدين بالشيت:</strong> {{ $mainSalaryEmployees->count() }}</span>
    </div>

    <div class="print-btn-area">
        <button onclick="window.print()" class="btn btn-success btn-sm" id="printButton">
            🖨️ طباعة الشيت بالكامل
        </button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm mr-2">
            ✖ إغلاق
        </button>
    </div>

    {{-- ===== MAIN PAYROLL TABLE ===== --}}
    @if ($mainSalaryEmployees->count() > 0)
        <table class="payroll-table">
            <thead>
                <!-- Row 1: Main Headers -->
                <tr>
                    <th rowspan="2" class="hdr-basic" style="width: 25px;">#</th>
                    <th rowspan="2" class="hdr-basic" style="width: 45px;">كود</th>
                    <th rowspan="2" class="hdr-basic" style="width: 120px;">اسم الموظف</th>
                    <th rowspan="2" class="hdr-basic" style="width: 75px;">الوظيفة</th>
                    <th rowspan="2" class="hdr-basic" style="width: 50px;">الراتب الأساسي</th>
                    <th colspan="6" class="hdr-benefits">الاستحقاقات والمزايا (+)</th>
                    <th colspan="8" class="hdr-deductions">الاستقطاعات والخصومات (-)</th>
                    <th rowspan="2" class="hdr-summary" style="width: 50px;">الرصيد المرحل</th>
                    <th rowspan="2" class="hdr-summary" style="width: 65px;">صافي المستحق</th>
                    <th rowspan="2" class="hdr-summary" style="width: 80px;">التوقيع بالاستلام</th>
                </tr>
                <!-- Row 2: Sub Headers -->
                <tr>
                    <!-- Benefits -->
                    <th class="hdr-benefits" style="width: 35px;">حوافز</th>
                    <th class="hdr-benefits" style="width: 35px;">بدلات ثابته</th>
                    <th class="hdr-benefits" style="width: 35px;">بدلات متغيره</th>
                    <th class="hdr-benefits" style="width: 35px;">مكافآت</th>
                    <th class="hdr-benefits" style="width: 35px;">إضافي</th>
                    <th class="hdr-benefits" style="width: 45px;">إجمالي الاستحقاق</th>
                    <!-- Deductions -->
                    <th class="hdr-deductions" style="width: 35px;">تأمينات</th>
                    <th class="hdr-deductions" style="width: 35px;">تأمين طبي</th>
                    <th class="hdr-deductions" style="width: 35px;">غياب</th>
                    <th class="hdr-deductions" style="width: 35px;">جزاءات</th>
                    <th class="hdr-deductions" style="width: 35px;">عقوبات</th>
                    <th class="hdr-deductions" style="width: 35px;">سلف شهرية</th>
                    <th class="hdr-deductions" style="width: 35px;">سلف مستديمة</th>
                    <th class="hdr-deductions" style="width: 45px;">إجمالي الاستقطاع</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mainSalaryEmployees as $index => $record)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ optional($record->employee)->employee_code ?? '---' }}</td>
                        <td class="col-emp-name">{{ $record->employee_name }}</td>
                        <td style="white-space: nowrap;">{{ optional($record->job)->name ?? '---' }}</td>
                        <td class="font-weight-bold">{{ number_format($record->employee_salary, 2) }}</td>
                        
                        <!-- Benefits values -->
                        <td class="col-val-benefits">{{ number_format($record->motivation_amount, 2) }}</td>
                        <td class="col-val-benefits">{{ number_format($record->fixed_allowance, 2) }}</td>
                        <td class="col-val-benefits">{{ number_format($record->employee_total_allowance, 2) }}</td>
                        <td class="col-val-benefits">{{ number_format($record->employee_total_bonus, 2) }}</td>
                        <td class="col-val-benefits">{{ number_format($record->employee_additions_payment_total, 2) }}</td>
                        <td class="col-val-benefits font-weight-bold" style="background-color: #f1f9f1;">{{ number_format($record->total_benefits, 2) }}</td>
                        
                        <!-- Deductions values -->
                        <td class="col-val-deductions">{{ number_format($record->social_insurance_amount, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->medical_insurance_amount, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->employee_absences_payment_total, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->employee_deductions_payment_total, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->employee_total_deduction_type, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->monthly_loan_amount, 2) }}</td>
                        <td class="col-val-deductions">{{ number_format($record->permanent_loan_amount, 2) }}</td>
                        <td class="col-val-deductions font-weight-bold" style="background-color: #fff5f5;">{{ number_format($record->total_deductions, 2) }}</td>
                        
                        <!-- Summary values -->
                        <td class="font-weight-bold">{{ number_format($record->employee_rollover_amount, 2) }}</td>
                        <td class="col-val-net {{ $record->employee_net_salary >= 0 ? 'net-positive' : 'net-negative' }}">
                            {{ number_format(abs($record->employee_net_salary), 2) }}
                            <div style="font-size: 7.5px; font-weight: normal; line-height: 1;">
                                {{ $record->employee_net_salary >= 0 ? 'دائن' : 'مدين' }}
                            </div>
                        </td>
                        <td class="col-sign"></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; padding-right: 10px;">إجمالي الشيت</td>
                    <td>{{ number_format($total_salary_sum, 2) }}</td>
                    
                    <!-- Sum of Benefits -->
                    <td class="col-val-benefits">{{ number_format($mainSalaryEmployees->sum('motivation_amount'), 2) }}</td>
                    <td class="col-val-benefits">{{ number_format($mainSalaryEmployees->sum('fixed_allowance'), 2) }}</td>
                    <td class="col-val-benefits">{{ number_format($mainSalaryEmployees->sum('employee_total_allowance'), 2) }}</td>
                    <td class="col-val-benefits">{{ number_format($mainSalaryEmployees->sum('employee_total_bonus'), 2) }}</td>
                    <td class="col-val-benefits">{{ number_format($mainSalaryEmployees->sum('employee_additions_payment_total'), 2) }}</td>
                    <td class="col-val-benefits font-weight-bold" style="background-color: #e2f0d9;">{{ number_format($total_benefits_sum, 2) }}</td>
                    
                    <!-- Sum of Deductions -->
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('social_insurance_amount'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('medical_insurance_amount'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('employee_absences_payment_total'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('employee_deductions_payment_total'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('employee_total_deduction_type'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('monthly_loan_amount'), 2) }}</td>
                    <td class="col-val-deductions">{{ number_format($mainSalaryEmployees->sum('permanent_loan_amount'), 2) }}</td>
                    <td class="col-val-deductions font-weight-bold" style="background-color: #fce4d6;">{{ number_format($total_deductions_sum, 2) }}</td>
                    
                    <!-- Sum of Summary -->
                    <td>{{ number_format($mainSalaryEmployees->sum('employee_rollover_amount'), 2) }}</td>
                    <td class="font-weight-bold text-primary" style="font-size: 11px; background-color: #fff2cc;">{{ number_format($total_net_salary_sum, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- ===== SIGNATURES FOOTER ===== --}}
        <div class="signatures-row">
            <div class="sig-block">
                <strong>المراجع المالي</strong>
                <div class="sig-line"></div>
            </div>
            <div class="sig-block">
                <strong>مدير الموارد البشرية</strong>
                <div class="sig-line"></div>
            </div>
            <div class="sig-block">
                <strong>المدير المالي</strong>
                <div class="sig-line"></div>
            </div>
            <div class="sig-block">
                <strong>المدير العام / الاعتماد</strong>
                <div class="sig-line"></div>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; font-size: 14px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;">
            ⚠️ عفواً، لا توجد سجلات رواتب مدخلة في هذا الشهر المالي حتى الآن.
        </div>
    @endif

</body>

</html>

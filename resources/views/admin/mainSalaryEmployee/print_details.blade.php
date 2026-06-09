<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>طباعة تفاصيل راتب الموظف {{ $record->employee_name }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
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

        /* ===== EMPLOYEE DETAILS CARD ===== */
        .employee-card {
            border: 1px solid #cdd8e3;
            border-radius: 6px;
            background: #f8fafc;
            padding: 15px;
            margin-bottom: 15px;
        }

        .employee-card table {
            width: 100%;
            margin-bottom: 0;
        }

        .employee-card table td {
            padding: 5px 10px;
            border: none;
            font-size: 13px;
        }

        /* ===== DETAILS CONTAINER ===== */
        .details-container {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .details-column {
            flex: 1;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }

        .column-header {
            padding: 8px;
            font-weight: bold;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #cbd5e1;
        }

        .benefits-header {
            background-color: #d4edda;
            color: #155724;
        }

        .deductions-header {
            background-color: #f8d7da;
            color: #721c24;
        }

        table.print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 12px;
        }

        table.print-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        table.print-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
        }

        table.print-table tbody tr:last-child td {
            border-bottom: none;
        }

        table.print-table td.val-col {
            font-weight: bold;
            text-align: center;
            width: 35%;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f5f9 !important;
            border-top: 2px solid #cbd5e1;
        }

        .net-salary-box {
            background: #f0fdf4;
            border: 2px dashed #22c55e;
            border-radius: 6px;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .net-salary-title {
            color: #15803d;
        }

        .net-salary-value {
            color: #16a34a;
            font-size: 18px;
        }

        /* ===== FOOTER ===== */
        .print-footer {
            margin-top: 15px;
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }

        /* ===== PRINT BUTTON ===== */
        .print-btn-area {
            text-align: center;
            margin: 15px 0;
        }

        @media print {
            .print-btn-area {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>

    {{-- ===== HEADER ===== --}}
    <div class="print-header">
        <div class="company-info">
            <h2>{{ $systemData['system_name'] ?? 'النظام' }}</h2>
            <p>📍 {{ $systemData['address'] ?? '' }}</p>
            <p>📞 {{ $systemData['phone'] ?? '' }}</p>
            @if (!empty($systemData['email']))
                <p>✉️ {{ $systemData['email'] }}</p>
            @endif
        </div>
        <div class="logo">
            @if (!empty($systemData['photo']))
                <img src="{{ asset('/storage/' . $systemData['photo']) }}" alt="شعار الشركة">
            @endif
        </div>
    </div>

    {{-- ===== REPORT TITLE ===== --}}
    <div class="report-title-box">
        <h3>تفاصيل راتب الموظف</h3>
        <p>عن شهر: <strong>{{ optional($record->financeMonthlyCalendar->month)->name ?? '' }}
                {{ $record->financeMonthlyCalendar->finance_yr ?? '' }}</strong></p>
    </div>

    {{-- ===== META INFO ===== --}}
    <div class="meta-row">
        <span>📅 <strong>تاريخ الطباعة:</strong> @php echo date('Y-m-d'); @endphp</span>
        <span>🕐 <strong>الوقت:</strong> @php echo date('h:i A'); @endphp</span>
        <span>👤 <strong>طُبع بواسطة:</strong> {{ auth()->user()->name }}</span>
    </div>

    {{-- ===== EMPLOYEE CARD ===== --}}
    <div class="employee-card">
        <table class="table-sm">
            <tr>
                <td style="width: 15%; font-weight: bold; color: #4a5568;">اسم الموظف:</td>
                <td style="width: 35%; font-weight: bold;">{{ $record->employee_name }}</td>
                <td style="width: 15%; font-weight: bold; color: #4a5568;">كود الموظف:</td>
                <td style="width: 35%; font-weight: bold;">{{ $record->employee->employee_code ?? '---' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #4a5568;">الفرع / القسم:</td>
                <td>{{ $record->branch->name ?? '---' }} / {{ $record->department->name ?? '---' }}</td>
                <td style="font-weight: bold; color: #4a5568;">الوظيفة:</td>
                <td>{{ $record->job->name ?? '---' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #4a5568;">الراتب الأساسي:</td>
                <td style="font-weight: bold; color: #2b6cb0;">{{ number_format($record->employee_salary, 2) }} ج.م
                </td>
                <td style="font-weight: bold; color: #4a5568;">طريقة الدفع:</td>
                <td>
                    @if ($record->payment_method == 1)
                        نقداً
                    @elseif($record->payment_method == 2)
                        تحويل بنكي
                    @elseif($record->payment_method == 3)
                        شيك
                    @else
                        ---
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== DETAILS CONTAINER ===== --}}
    <div class="details-container">
        <!-- Benefits Section -->
        <div class="details-column">
            <div class="column-header benefits-header">الاستحقاقات (+)</div>
            <table class="print-table">
                <tbody>
                    <tr>
                        <td>الراتب الأساسي</td>
                        <td class="val-col">{{ number_format($record->employee_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td>الحوافز</td>
                        <td class="val-col text-success">{{ number_format($record->motivation_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>بدلات ثابتة</td>
                        <td class="val-col text-success">{{ number_format($record->fixed_allowance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>بدلات متغيرة</td>
                        <td class="val-col text-success">{{ number_format($record->employee_total_allowance, 2) }}</td>
                    </tr>
                    <tr>
                        <td>المكافآت</td>
                        <td class="val-col text-success">{{ number_format($record->employee_total_bonus, 2) }}</td>
                    </tr>
                    <tr>
                        <td>إضافي الأيام ({{ $record->employee_additions_days_counter * 1 }} يوم)</td>
                        <td class="val-col text-success">
                            {{ number_format($record->employee_additions_payment_total, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="text-success">إجمالي الاستحقاقات</td>
                        <td class="val-col text-success">{{ number_format($record->total_benefits, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Deductions Section -->
        <div class="details-column">
            <div class="column-header deductions-header">الاستقطاعات (-)</div>
            <table class="print-table">
                <tbody>
                    <tr>
                        <td>تأمينات اجتماعية</td>
                        <td class="val-col text-danger">{{ number_format($record->social_insurance_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>تأمين طبي</td>
                        <td class="val-col text-danger">{{ number_format($record->medical_insurance_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>غياب الموظف ({{ $record->employee_absences_days_counter * 1 }} يوم)</td>
                        <td class="val-col text-danger">
                            {{ number_format($record->employee_absences_payment_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>الجزاءات العامة ({{ $record->employee_deductions_days_counter * 1 }} يوم)</td>
                        <td class="val-col text-danger">
                            {{ number_format($record->employee_deductions_payment_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td>الخصومات المالية (العقوبات)</td>
                        <td class="val-col text-danger">{{ number_format($record->employee_total_deduction_type, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td>سلف شهرية</td>
                        <td class="val-col text-danger">{{ number_format($record->monthly_loan_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td>سلف مستديمة</td>
                        <td class="val-col text-danger">{{ number_format($record->permanent_loan_amount, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="text-danger">إجمالي الاستقطاعات</td>
                        <td class="val-col text-danger">{{ number_format($record->total_deductions, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div
        style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 10px 15px; margin-bottom: 15px; display:flex; justify-content:space-between; font-size:13px;">
        <span>الراتب المرحل من الشهر الماضي:</span>
        <strong>{{ number_format($record->employee_rollover_amount, 2) }} ج.م</strong>
    </div>

    <div class="net-salary-box">
        <span class="net-salary-title">صافي الراتب المستحق:</span>
        <span class="net-salary-value">{{ number_format($record->employee_net_salary, 2) }} ج.م</span>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div class="print-footer">
        {{ $systemData['address'] ?? '' }}
        @if (!empty($systemData['phone']))
            — {{ $systemData['phone'] }}
        @endif
        @if (!empty($systemData['email']))
            — {{ $systemData['email'] }}
        @endif
    </div>

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

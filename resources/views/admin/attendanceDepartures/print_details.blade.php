<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>طباعة كشف بصمة الموظف {{ $employee->name }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap_rtl-v4.2.1/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            font-size: 11px;
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
            background: linear-gradient(135deg, #2c3e50, #17a2b8);
            color: #fff;
            border-radius: 6px;
        }

        .report-title-box h3 {
            margin: 0;
            font-size: 15px;
            font-weight: bold;
        }

        .report-title-box p {
            margin: 3px 0 0;
            font-size: 12px;
            opacity: 0.9;
        }

        /* ===== EMPLOYEE DETAILS CARD ===== */
        .employee-card {
            border: 1px solid #cdd8e3;
            border-radius: 6px;
            background: #f8fafc;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .employee-card table {
            width: 100%;
            margin-bottom: 0;
        }

        .employee-card table td {
            padding: 3px 8px;
            border: none;
            font-size: 12px;
        }

        /* ===== TABLE ===== */
        table.print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        table.print-table th,
        table.print-table td {
            border: 1px solid #aaa !important;
            padding: 4px 6px !important;
            vertical-align: middle !important;
            text-align: center !important;
        }

        table.print-table thead th {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
            font-weight: bold !important;
        }

        table.print-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .total-row {
            font-weight: bold;
            background-color: #e2e8f0 !important;
        }

        /* ===== FOOTER ===== */
        .print-footer {
            margin-top: 15px;
            border-top: 2px solid #2c3e50;
            padding-top: 8px;
            text-align: center;
            font-size: 11px;
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
        <h3>كشف حركة الحضور والانصراف والبصمة اليومية للموظف</h3>
        <p>عن الشهر المالي: {{ $financeMonthlyCalendar->month->name }} ({{ $financeMonthlyCalendar->finance_yr }})</p>
    </div>

    {{-- ===== EMPLOYEE CARD ===== --}}
    <div class="employee-card">
        <table>
            <tr>
                <td style="width: 33%;"><strong>اسم الموظف:</strong> {{ $employee->name }}</td>
                <td style="width: 33%;"><strong>كود الموظف:</strong> {{ $employee->employee_code }}</td>
                <td style="width: 33%;"><strong>كود البصمة:</strong>
                    {{ $employee->fingerprint_code ?? 'لم يتم التعيين' }}</td>
            </tr>
            <tr>
                <td><strong>الفرع:</strong> {{ optional($employee->branch)->name ?? '---' }}</td>
                <td><strong>الإدارة:</strong> {{ optional($employee->department)->name ?? '---' }}</td>
                <td><strong>الوظيفة:</strong> {{ optional($employee->job)->name ?? '---' }}</td>
            </tr>
        </table>
    </div>

    {{-- ===== ATTENDANCE TABLE ===== --}}
    <table class="table print-table">
        <thead>
            <tr>
                <th>اليوم / التاريخ</th>
                <th>الحضور المعتمد</th>
                <th>الانصراف المعتمد</th>
                <th>المتغيرات</th>
                <th>ساعات العمل</th>
                <th>الإضافي</th>
                <th>الغياب</th>
                <th>خصم أيام</th>
                <th>نوع الإجازة</th>
                <th>إجازة رسمية</th>
                <th>التأخير (د)</th>
                <th>المبكر (د)</th>
                <th>إذن بالساعات</th>
                <th>اتخاذ إجراء</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $day)
                @php
                    $att = $day['attendance'];
                    $has_record = !empty($att);
                    $total_hours = $has_record ? $att->total_hours : '';
                    $overtime_hours = $has_record ? $att->overtime_hours : '';
                    $absence_hours = $has_record ? $att->absence_hours : '';
                    $cutting_days = $has_record ? $att->cutting_days : 0;
                    $deduction_type_id = $has_record ? $att->variables : 0;
                    $vacation_id = $has_record ? $att->vacation_id : 0;
                    $occasion_id = $has_record ? $att->occasion_id : 0;
                    $selectedVacation = $vacationTypes->firstWhere('id', $vacation_id);
                    $selectedOccasion = $occasions->firstWhere('id', $occasion_id);
                    $attendance_delay = $has_record ? $att->attendance_delay : '';
                    $early_departure = $has_record ? $att->early_departure : '';
                    $approved_delay = $has_record ? $att->approved_attendance_delay_early_departure : '';
                    $is_action = $has_record ? $att->is_action_made_on_employee : '0';
                    $notes = $has_record ? $att->notes : '';
                    $check_in_display =
                        $has_record && $att->checkInTime ? date('h:i A', strtotime($att->checkInTime)) : '---';
                    $check_out_display =
                        $has_record && $att->checkOutTime ? date('h:i A', strtotime($att->checkOutTime)) : '---';
                @endphp
                <tr>
                    <td class="font-weight-bold">
                        {{ $day['day_name'] }} <br>
                        <span class="text-muted" style="font-size: 9px;">{{ $day['date'] }}</span>
                    </td>
                    <td class="text-success font-weight-bold">{{ $check_in_display }}</td>
                    <td class="text-info font-weight-bold">{{ $check_out_display }}</td>
                    <td>{{ $has_record ? $att->variables : '' }}</td>
                    <td class="font-weight-bold">{{ $total_hours }}</td>
                    <td class="text-success font-weight-bold">{{ $overtime_hours }}</td>
                    <td class="text-danger font-weight-bold">{{ $absence_hours }}</td>
                    <td class="font-weight-bold">{{ $cutting_days }}</td>
                    <td>{{ $selectedVacation ? $selectedVacation->name : 'لا يوجد' }}</td>
                    <td>{{ $selectedOccasion ? $selectedOccasion->name : 'لا يوجد' }}</td>
                    <td>{{ $attendance_delay }}</td>
                    <td>{{ $early_departure }}</td>
                    <td>{{ $approved_delay }}</td>
                    <td>{{ $is_action == '1' ? 'نعم' : 'لا' }}</td>
                    <td class="text-right" style="font-size: 9px;">{{ $notes }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">الإجمالي</td>
                <td>{{ number_format($totals['total_hours'], 2) }}</td>
                <td class="text-success">{{ number_format($totals['overtime_hours'], 2) }}</td>
                <td class="text-danger">{{ number_format($totals['absence_hours'], 2) }}</td>
                <td class="text-warning" style="color: #e67e22 !important;">
                    {{ number_format($totals['cutting_days'], 2) }}</td>
                <td style="font-size: 9px; font-weight: normal;">{{ $totals['vacation_summary'] ?: '---' }}</td>
                <td style="font-size: 9px; font-weight: normal;">{{ $totals['occasion_summary'] ?: '---' }}</td>
                <td class="text-danger">{{ number_format($totals['attendance_delay'], 2) }}</td>
                <td style="color: #6f42c1 !important;">{{ number_format($totals['early_departure'], 2) }}</td>
                <td class="text-info">{{ number_format($totals['approved_attendance_delay_early_departure'], 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

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

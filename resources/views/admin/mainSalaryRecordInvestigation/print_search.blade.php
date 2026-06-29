<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>طباعة التحقيقات الإدارية الشهرية للموظفين</title>
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
        .print-header .company-info { text-align: right; }
        .print-header .company-info h2 {
            margin: 0; font-size: 18px; font-weight: bold; color: #2c3e50;
        }
        .print-header .company-info p { margin: 2px 0; font-size: 12px; color: #555; }
        .print-header .logo img {
            width: 100px; height: 80px; object-fit: contain; border-radius: 8px;
        }

        /* ===== REPORT TITLE ===== */
        .report-title-box {
            text-align: center; margin: 10px 0; padding: 8px;
            background: linear-gradient(135deg, #2c3e50, #8e44ad);
            color: #fff; border-radius: 6px;
        }
        .report-title-box h3 { margin: 0; font-size: 16px; font-weight: bold; }
        .report-title-box p { margin: 3px 0 0; font-size: 12px; opacity: 0.9; }

        /* ===== META INFO ===== */
        .meta-row {
            display: flex; justify-content: space-between;
            background: #f0f4f8; border: 1px solid #cdd8e3;
            border-radius: 5px; padding: 8px 15px;
            margin-bottom: 12px; font-size: 12px;
        }
        .meta-row span { display: inline-block; }
        .meta-row strong { color: #2c3e50; }

        /* ===== TABLE ===== */
        table.print-table {
            width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 12px;
        }
        table.print-table thead tr { background-color: #2c3e50; color: #fff; }
        table.print-table thead th {
            padding: 7px 5px; text-align: center;
            border: 1px solid #4a6278; white-space: nowrap;
        }
        table.print-table tbody tr:nth-child(even) { background-color: #f7f9fb; }
        table.print-table tbody tr:hover { background-color: #eaf2ff; }
        table.print-table tbody td {
            padding: 6px 5px; text-align: center;
            border: 1px solid #dde3ea; vertical-align: middle;
        }
        table.print-table tfoot tr { background-color: #d5e8f7; font-weight: bold; }
        table.print-table tfoot td {
            padding: 7px 5px; border: 1px solid #aac6e0; text-align: center;
        }

        /* ===== BADGES ===== */
        .badge-type {
            display: inline-block; padding: 2px 7px;
            border-radius: 10px; font-size: 11px; font-weight: bold;
        }
        .badge-auto     { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
        .badge-manual   { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        .badge-archived { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-active   { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* ===== SUMMARY CARDS ===== */
        .summary-section { display: flex; gap: 10px; margin: 12px 0; flex-wrap: wrap; }
        .summary-card {
            flex: 1; min-width: 150px; border: 1px solid #cdd8e3;
            border-radius: 6px; padding: 10px; text-align: center; background: #f8fafc;
        }
        .summary-card .sc-label { font-size: 11px; color: #666; margin-bottom: 4px; }
        .summary-card .sc-value { font-size: 16px; font-weight: bold; color: #2c3e50; }
        .summary-card.danger  .sc-value { color: #c0392b; }
        .summary-card.success .sc-value { color: #27ae60; }
        .summary-card.info    .sc-value { color: #2980b9; }
        .summary-card.purple  .sc-value { color: #8e44ad; }

        /* ===== FOOTER ===== */
        .print-footer {
            margin-top: 15px; border-top: 2px solid #2c3e50;
            padding-top: 8px; text-align: center; font-size: 12px; color: #555;
        }
        .no-data-msg {
            text-align: center; padding: 30px;
            font-size: 15px; font-weight: bold; color: #c0392b;
        }

        /* ===== PRINT BUTTON ===== */
        .print-btn-area { text-align: center; margin: 15px 0; }

        @media print {
            .print-btn-area { display: none; }
            body { padding: 0; }
            table.print-table tbody tr:hover { background-color: inherit; }
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
            @if(!empty($systemData['email']))
                <p>✉️ {{ $systemData['email'] }}</p>
            @endif
        </div>
        <div class="logo">
            @if(!empty($systemData['photo']))
                <img src="{{ asset('/storage/' . $systemData['photo']) }}" alt="شعار الشركة">
            @endif
        </div>
    </div>

    {{-- ===== REPORT TITLE ===== --}}
    <div class="report-title-box">
        <h3>كشف التحقيقات الإدارية الشهرية للموظفين</h3>
        @if($financeMonthlyCalendar)
            <p>
                الشهر:
                <strong>
                    {{ optional(optional($financeMonthlyCalendar)->month)->name ?? '' }}
                    {{ optional($financeMonthlyCalendar)->finance_yr ?? '' }}
                </strong>
            </p>
        @endif
    </div>

    {{-- ===== META INFO ===== --}}
    <div class="meta-row">
        <span>📅 <strong>تاريخ الطباعة:</strong> @php echo date('Y-m-d'); @endphp</span>
        <span>🕐 <strong>الوقت:</strong> @php echo date('h:i A'); @endphp</span>
        <span>👤 <strong>طُبع بواسطة:</strong> {{ auth()->user()->name }}</span>
        <span>📋 <strong>إجمالي السجلات:</strong> {{ $investigations->count() }}</span>
    </div>

    @if($investigations->count() > 0)

        {{-- ===== SUMMARY CARDS ===== --}}
        <div class="summary-section">
            <div class="summary-card purple">
                <div class="sc-label">عدد التحقيقات</div>
                <div class="sc-value">{{ $investigations->count() }}</div>
            </div>
            <div class="summary-card info">
                <div class="sc-label">عدد الموظفين المعنيين</div>
                <div class="sc-value">{{ $investigations->pluck('employee_id')->unique()->count() }}</div>
            </div>
            <div class="summary-card danger">
                <div class="sc-label">تحقيقات مؤرشفة</div>
                <div class="sc-value">{{ $investigations->where('is_archived', 1)->count() }}</div>
            </div>
            <div class="summary-card success">
                <div class="sc-label">تحقيقات غير مؤرشفة</div>
                <div class="sc-value">{{ $investigations->where('is_archived', 0)->count() }}</div>
            </div>
        </div>

        {{-- ===== MAIN TABLE ===== --}}
        <table class="print-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>كود الموظف</th>
                    <th>اسم الموظف</th>
                    <th>وصف التحقيق</th>
                    <th>طريقة الإضافة</th>
                    <th>الحالة</th>
                    <th>أضيف بواسطة</th>
                    <th>تاريخ الإضافة</th>
                    <th>آخر تعديل</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($investigations as $index => $investigation)
                <tr>
                    {{-- رقم --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- كود الموظف --}}
                    <td>{{ optional($investigation->employee)->employee_code ?? '---' }}</td>

                    {{-- اسم الموظف --}}
                    <td style="text-align:right; font-weight:bold;">
                        {{ optional($investigation->employee)->name ?? '---' }}
                    </td>

                    {{-- وصف التحقيق --}}
                    <td style="text-align:right; font-size:11px;">
                        {{ $investigation->description ?? '---' }}
                    </td>

                    {{-- طريقة الإضافة --}}
                    <td>
                        @if($investigation->is_auto == 1)
                            <span class="badge-type badge-auto">تلقائي</span>
                        @else
                            <span class="badge-type badge-manual">يدوي</span>
                        @endif
                    </td>

                    {{-- الحالة (أرشفة) --}}
                    <td>
                        @if($investigation->is_archived == 1)
                            <span class="badge-type badge-archived">مؤرشف</span>
                        @else
                            <span class="badge-type badge-active">نشط</span>
                        @endif
                    </td>

                    {{-- أضيف بواسطة --}}
                    <td>{{ optional($investigation->addedBy)->name ?? '---' }}</td>

                    {{-- تاريخ الإضافة --}}
                    <td style="white-space:nowrap;">
                        {{ $investigation->created_at ? $investigation->created_at->format('Y-m-d h:i:s A') : '---' }}
                    </td>

                    {{-- آخر تعديل --}}
                    <td style="white-space:nowrap;">
                        @if($investigation->updated_at && $investigation->updated_at != $investigation->created_at)
                            {{ $investigation->updated_at->format('Y-m-d h:i:s A') }}
                            @if(optional($investigation->updatedBy)->name)
                                <br><small style="color:#777;">{{ $investigation->updatedBy->name }}</small>
                            @endif
                        @else
                            ---
                        @endif
                    </td>

                    {{-- ملاحظات --}}
                    <td style="text-align:right; font-size:11px; color:#555;">
                        {{ $investigation->notes ?? '---' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align:right; padding-right:10px;">الإجمالي</td>
                    <td colspan="7">إجمالي التحقيقات: {{ $investigations->count() }}</td>
                </tr>
            </tfoot>
        </table>

    @else

        <div class="no-data-msg">
            ⚠️ عفواً، لا توجد بيانات لعرضها بناءً على معايير البحث المحددة.
        </div>

    @endif

    {{-- ===== FOOTER ===== --}}
    <div class="print-footer">
        {{ $systemData['address'] ?? '' }}
        @if(!empty($systemData['phone'])) — {{ $systemData['phone'] }} @endif
        @if(!empty($systemData['email'])) — {{ $systemData['email'] }} @endif
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

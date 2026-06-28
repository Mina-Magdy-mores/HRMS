<style>
    /* Warning row highlight */
    #attendanceGridTable tbody tr.warning-row {
        background-color: #ffe3e6b9 !important;
    }
    #attendanceGridTable tbody tr.warning-row:hover {
        background-color: #ffd5d9ff !important;
    }

    /* Fixed scroll buttons on screen */
    .fixed-scroll-btn {
        position: fixed;
        top: 72%;
        transform: translateY(-50%);
        z-index: 1040;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: rgba(0, 123, 255, 0.75) !important;
        border: 2px solid #fff !important;
        color: #fff !important;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .fixed-scroll-btn:hover {
        background-color: rgba(0, 86, 179, 0.95) !important;
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25);
    }
    .fixed-scroll-right {
        right: 20px;
    }
    .fixed-scroll-left {
        left: 20px;
    }
    
    /* Support RTL Sidebar offset on desktop */
    @media (min-width: 992px) {
        body:not(.sidebar-collapse) .fixed-scroll-right {
            right: 270px;
        }
    }
</style>

<!-- Fixed Scroll Buttons on Viewport Sides -->
<button type="button" class="fixed-scroll-btn fixed-scroll-right d-print-none" onclick="scrollGrid('right')" title="تحريك لليمين">
    <i class="fas fa-chevron-right"></i>
</button>
<button type="button" class="fixed-scroll-btn fixed-scroll-left d-print-none" onclick="scrollGrid('left')" title="تحريك لليسار">
    <i class="fas fa-chevron-left"></i>
</button>

<div>
    <div class="table-responsive shadow-sm rounded" id="gridTableContainer" style="overflow-x: auto; scroll-behavior: smooth;">
        <table class="table table-striped table-bordered table-hover text-center align-middle mb-0" id="attendanceGridTable">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="min-width: 120px; vertical-align: middle;">اليوم / التاريخ</th>
                    <th style="min-width: 100px; vertical-align: middle;">الحضور المعتمد</th>
                    <th style="min-width: 100px; vertical-align: middle;">الانصراف المعتمد</th>
                    <th style="min-width: 100px; vertical-align: middle;">حركات اليوم</th>
                    <th style="min-width: 150px; vertical-align: middle;">المتغيرات</th>
                    <th style="min-width: 90px; vertical-align: middle;">ساعات العمل</th>
                    <th style="min-width: 90px; vertical-align: middle;">الإضافي</th>
                    <th style="min-width: 90px; vertical-align: middle;">الغياب</th>
                    <th style="min-width: 120px; vertical-align: middle;">خصم أيام</th>
                    <th style="min-width: 120px; vertical-align: middle;">نوع الإجازة</th>
                    <th style="min-width: 140px; vertical-align: middle;">إجازة رسمية</th>
                    <th style="min-width: 80px; vertical-align: middle;">التأخير (د)</th>
                    <th style="min-width: 80px; vertical-align: middle;">المبكر (د)</th>
                    <th style="min-width: 90px; vertical-align: middle;">إذن بالساعات</th>
                    <th style="min-width: 100px; vertical-align: middle;">اتخاذ إجراء</th>
                    <th style="min-width: 150px; vertical-align: middle;">ملاحظات</th>
                    @if ($is_editable)
                        <th style="min-width: 80px; vertical-align: middle;" class="actions-column">حفظ</th>
                    @endif
                </tr>
            </thead>
        <tbody>
            @foreach ($days as $day)
                @php
                    $att = $day['attendance'];
                    $has_record = !empty($att);
                    $is_row_archived = ($has_record && $att->is_archived == 1);
                    $is_row_editable = $is_editable && !$is_row_archived;
                    
                    $total_hours = $has_record ? $att->total_hours : '';
                    $overtime_hours = $has_record ? $att->overtime_hours : '';
                    $absence_hours = $has_record ? $att->absence_hours : '';
                    $cutting_days = $has_record ? $att->cutting_days : 0;
                    $deduction_type_id = $has_record ? $att->variables : 0;
                    $vacation_id = $has_record ? $att->vacation_id : 0;
                    $occasion_id = $has_record ? $att->occasion_id : 0;
                    $selectedVacation = $vacationTypes->firstWhere('id', $vacation_id);
                    $isOfficialVacation = $selectedVacation && str_contains($selectedVacation->name, 'رسمية');
                    $attendance_delay = $has_record ? $att->attendance_delay : '';
                    $early_departure = $has_record ? $att->early_departure : '';
                    $approved_delay = $has_record ? $att->approved_attendance_delay_early_departure : '';
                    $is_action = $has_record ? $att->is_action_made_on_employee : '0';
                    $notes = $has_record ? $att->notes : '';
                    $check_in_display = ($has_record && $att->checkInTime) ? date('h:i A', strtotime($att->checkInTime)) : '---';
                    $check_out_display = ($has_record && $att->checkOutTime) ? date('h:i A', strtotime($att->checkOutTime)) : '---';
                    $check_in_24 = ($has_record && $att->checkInTime) ? date('H:i', strtotime($att->checkInTime)) : '---';
                    $check_out_24 = ($has_record && $att->checkOutTime) ? date('H:i', strtotime($att->checkOutTime)) : '---';
                    
                    $is_warning = ($vacation_id == 0 && $occasion_id == 0 && (!$has_record || !$att->checkInTime || !$att->checkOutTime));
                @endphp
                <tr data-date="{{ $day['date'] }}" class="{{ $is_warning ? 'warning-row' : '' }}">
                    <!-- Day & Date -->
                    <td class="align-middle" title="نظام 24 ساعة - الحضور: {{ $check_in_24 }} | الانصراف: {{ $check_out_24 }}">
                        <span class="d-block font-weight-bold text-dark">{{ $day['day_name'] }}</span>
                        <span class="text-muted small">{{ $day['date'] }}</span>
                    </td>
                    
                    <!-- Approved In/Out -->
                    <td class="align-middle text-success font-weight-bold" title="نظام 24 ساعة: {{ $check_in_24 }}">{{ $check_in_display }}</td>
                    <td class="align-middle text-info font-weight-bold" title="نظام 24 ساعة: {{ $check_out_24 }}">{{ $check_out_display }}</td>
                    
                    <!-- Day Movements Button -->
                    <td class="align-middle">
                        <button type="button" class="btn btn-info btn-xs view-day-movements-btn shadow-sm" title="عرض الحركات">
                            <i class="fas fa-history"></i> ({{ $day['movements_count'] }})
                        </button>
                    </td>
                    
                    <!-- Variables Text -->
                    <td class="align-middle">
                        <input type="text" class="form-control form-control-sm text-right" 
                            name="variables" value="{{ $has_record ? $att->variables : '' }}" placeholder="..." {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Work Hours -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="total_hours" value="{{ $total_hours }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Overtime -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold text-success" 
                            name="overtime_hours" value="{{ $overtime_hours }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Absence -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold text-danger" 
                            name="absence_hours" value="{{ $absence_hours }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Cutting Days -->
                    <td class="align-middle">
                        <input type="number" step="0.01" min="0" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="cutting_days" value="{{ $cutting_days }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Vacation ID -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm select-vacation" name="vacation_id" {{ !$is_row_editable ? 'disabled' : '' }}>
                            <option value="0" {{ $vacation_id == 0 ? 'selected' : '' }}>لا يوجد</option>
                            @foreach ($vacationTypes as $vt)
                                <option value="{{ $vt->id }}" {{ $vacation_id == $vt->id ? 'selected' : '' }} data-is-official="{{ str_contains($vt->name, 'رسمية') ? 1 : 0 }}">
                                    {{ $vt->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    
                    <!-- Occasion ID -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm" name="occasion_id" {{ (!$is_row_editable || !$isOfficialVacation) ? 'disabled' : '' }}>
                            <option value="0">لا يوجد</option>
                            @foreach ($occasions as $oc)
                                <option value="{{ $oc->id }}" {{ $occasion_id == $oc->id ? 'selected' : '' }}>{{ $oc->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    <!-- Delay minutes -->
                    <td class="align-middle">
                        <input type="number" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="attendance_delay" value="{{ $attendance_delay }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Early departure minutes -->
                    <td class="align-middle">
                        <input type="number" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="early_departure" value="{{ $early_departure }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Hourly permissions -->
                    <td class="align-middle">
                        <input type="number" step="0.1" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="approved_attendance_delay_early_departure" value="{{ $approved_delay }}" {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Action taken -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm font-weight-bold" name="is_action_made_on_employee" {{ !$is_row_editable ? 'disabled' : '' }}>
                            <option value="0" {{ $is_action == '0' ? 'selected' : '' }}>لا</option>
                            <option value="1" {{ $is_action == '1' ? 'selected' : '' }}>نعم</option>
                        </select>
                    </td>
                    
                    <!-- Notes -->
                    <td class="align-middle">
                        <input type="text" class="form-control form-control-sm text-right" 
                            name="notes" value="{{ $notes }}" placeholder="..." {{ !$is_row_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Action buttons (Row level save) -->
                    @if ($is_editable)
                        <td class="align-middle actions-column">
                            @if (!$is_row_archived)
                                <button type="button" class="btn btn-success btn-xs save-row-btn shadow-sm" title="حفظ هذا اليوم">
                                    <i class="fas fa-save"></i> حفظ
                                </button>
                            @else
                                <span class="badge badge-secondary" title="اليوم مؤرشف"><i class="fas fa-lock"></i> مؤرشف</span>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="border-top: 3px double #007bff; border-bottom: 3px double #007bff; background-color: #e8f4fd; font-weight: bold; font-size: 0.95rem;">
                <td class="align-middle text-primary text-center" colspan="5" style="font-size: 1.05rem;">الإجمالي</td>
                <td class="align-middle text-dark font-weight-bold">{{ number_format($totals['total_hours'], 2) }}</td>
                <td class="align-middle text-success font-weight-bold">{{ number_format($totals['overtime_hours'], 2) }}</td>
                <td class="align-middle text-danger font-weight-bold">{{ number_format($totals['absence_hours'], 2) }}</td>
                <td class="align-middle text-warning font-weight-bold" style="color: #e67e22 !important;">{{ number_format($totals['cutting_days'], 2) }}</td>
                <!-- Vacation Summary -->
                <td class="align-middle text-info font-weight-bold small" style="max-width: 140px; word-wrap: break-word;">
                    {{ $totals['vacation_summary'] ?: '---' }}
                </td>
                <!-- Occasion Summary -->
                <td class="align-middle text-muted font-weight-bold small" style="max-width: 140px; word-wrap: break-word;">
                    {{ $totals['occasion_summary'] ?: '---' }}
                </td>
                <td class="align-middle font-weight-bold" style="color: #dc3545 !important;">{{ number_format($totals['attendance_delay'], 2) }}</td>
                <td class="align-middle font-weight-bold" style="color: #6f42c1 !important;">{{ number_format($totals['early_departure'], 2) }}</td>
                <td class="align-middle font-weight-bold" style="color: #17a2b8 !important;">{{ number_format($totals['approved_attendance_delay_early_departure'], 2) }}</td>
                <td class="align-middle" colspan="2"></td>
                @if ($is_editable)
                    <td class="align-middle actions-column"></td>
                @endif
            </tr>
        </tfoot>
    </table>
    </div>
</div>

@if ($is_editable)
    <div class="d-flex justify-content-start mt-3 p-3 bg-light border rounded d-print-none">
        <button type="button" id="btn_save_all" class="btn btn-success shadow-sm font-weight-bold ml-2">
            <i class="fas fa-check-double mr-1"></i> حفظ جميع التعديلات دفعة واحدة
        </button>
        <button type="button" id="btn_cancel_all" class="btn btn-secondary shadow-sm font-weight-bold">
            <i class="fas fa-undo mr-1"></i> إلغاء التغييرات والتراجع
        </button>
    </div>
@endif

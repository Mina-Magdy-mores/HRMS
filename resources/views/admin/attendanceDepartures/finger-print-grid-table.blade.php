<style>
    #attendanceGridTable tbody tr.warning-row {
        background-color: #ffe3e6b9 !important;
    }
    #attendanceGridTable tbody tr.warning-row:hover {
        background-color: #ffd5d9ff !important;
    }
</style>
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-bordered table-hover text-center align-middle mb-0" id="attendanceGridTable">
        <thead class="bg-primary text-white">
            <tr>
                <th style="min-width: 120px; vertical-align: middle;">اليوم / التاريخ</th>
                <th style="min-width: 100px; vertical-align: middle;">الحضور المعتمد</th>
                <th style="min-width: 100px; vertical-align: middle;">الانصراف المعتمد</th>
                <th style="min-width: 100px; vertical-align: middle;">حركات اليوم</th>
                <th style="min-width: 90px; vertical-align: middle;">ساعات العمل</th>
                <th style="min-width: 90px; vertical-align: middle;">الإضافي</th>
                <th style="min-width: 90px; vertical-align: middle;">الغياب</th>
                <th style="min-width: 120px; vertical-align: middle;">خصم أيام</th>
                <th style="min-width: 120px; vertical-align: middle;">نوع الخصم</th>
                <th style="min-width: 120px; vertical-align: middle;">نوع الإجازة</th>
                <th style="min-width: 140px; vertical-align: middle;">إجازة رسمية</th>
                <th style="min-width: 80px; vertical-align: middle;">التأخير (د)</th>
                <th style="min-width: 80px; vertical-align: middle;">المبكر (د)</th>
                <th style="min-width: 90px; vertical-align: middle;">إذن بالساعات</th>
                <th style="min-width: 100px; vertical-align: middle;">اتخاذ إجراء</th>
                <th style="min-width: 150px; vertical-align: middle;">ملاحظات</th>
                @if ($is_editable)
                    <th style="min-width: 80px; vertical-align: middle;">حفظ</th>
                @endif
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
                            <i class="fas fa-history"></i> الحركات ({{ $day['movements_count'] }})
                        </button>
                    </td>
                    
                    <!-- Work Hours -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="total_hours" value="{{ $total_hours }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Overtime -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold text-success" 
                            name="overtime_hours" value="{{ $overtime_hours }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Absence -->
                    <td class="align-middle">
                        <input type="number" step="0.01" class="form-control form-control-sm text-center px-1 font-weight-bold text-danger" 
                            name="absence_hours" value="{{ $absence_hours }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Cutting Days -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm text-center font-weight-bold" name="cutting_days" {{ !$is_editable ? 'disabled' : '' }}>
                            <option value="0" {{ $cutting_days == 0 ? 'selected' : '' }}>0</option>
                            <option value="0.25" {{ $cutting_days == 0.25 ? 'selected' : '' }}>0.25 (ربع يوم)</option>
                            <option value="0.5" {{ $cutting_days == 0.5 ? 'selected' : '' }}>0.5 (نصف يوم)</option>
                            <option value="1" {{ $cutting_days == 1 ? 'selected' : '' }}>1 (يوم كامل)</option>
                            <option value="1.5" {{ $cutting_days == 1.5 ? 'selected' : '' }}>1.5</option>
                            <option value="2" {{ $cutting_days == 2 ? 'selected' : '' }}>2 (يومين)</option>
                            <option value="3" {{ $cutting_days == 3 ? 'selected' : '' }}>3 أيام</option>
                        </select>
                    </td>
                    
                    <!-- Deduction Type (variables) -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm" name="variables" {{ !$is_editable ? 'disabled' : '' }}>
                            <option value="0">لا يوجد</option>
                            @foreach ($deductionTypes as $dt)
                                <option value="{{ $dt->id }}" {{ $deduction_type_id == $dt->id ? 'selected' : '' }}>{{ $dt->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    <!-- Vacation ID -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm" name="vacation_id" {{ !$is_editable ? 'disabled' : '' }}>
                            <option value="0" {{ $vacation_id == 0 ? 'selected' : '' }}>لا يوجد</option>
                            <option value="1" {{ $vacation_id == 1 ? 'selected' : '' }}>عارضة</option>
                            <option value="2" {{ $vacation_id == 2 ? 'selected' : '' }}>اعتيادية</option>
                            <option value="3" {{ $vacation_id == 3 ? 'selected' : '' }}>مرضية</option>
                        </select>
                    </td>
                    
                    <!-- Occasion ID -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm" name="occasion_id" {{ !$is_editable ? 'disabled' : '' }}>
                            <option value="0">لا يوجد</option>
                            @foreach ($occasions as $oc)
                                <option value="{{ $oc->id }}" {{ $occasion_id == $oc->id ? 'selected' : '' }}>{{ $oc->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    <!-- Delay minutes -->
                    <td class="align-middle">
                        <input type="number" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="attendance_delay" value="{{ $attendance_delay }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Early departure minutes -->
                    <td class="align-middle">
                        <input type="number" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="early_departure" value="{{ $early_departure }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Hourly permissions -->
                    <td class="align-middle">
                        <input type="number" step="0.1" class="form-control form-control-sm text-center px-1 font-weight-bold" 
                            name="approved_attendance_delay_early_departure" value="{{ $approved_delay }}" {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Action taken -->
                    <td class="align-middle">
                        <select class="form-control form-control-sm font-weight-bold" name="is_action_made_on_employee" {{ !$is_editable ? 'disabled' : '' }}>
                            <option value="0" {{ $is_action == '0' ? 'selected' : '' }}>لا</option>
                            <option value="1" {{ $is_action == '1' ? 'selected' : '' }}>نعم</option>
                        </select>
                    </td>
                    
                    <!-- Notes -->
                    <td class="align-middle">
                        <input type="text" class="form-control form-control-sm text-right" 
                            name="notes" value="{{ $notes }}" placeholder="..." {{ !$is_editable ? 'disabled' : '' }}>
                    </td>
                    
                    <!-- Action buttons (Row level save) -->
                    @if ($is_editable)
                        <td class="align-middle">
                            <button type="button" class="btn btn-success btn-xs save-row-btn shadow-sm" title="حفظ هذا اليوم">
                                <i class="fas fa-save"></i> حفظ
                            </button>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if ($is_editable)
    <div class="d-flex justify-content-start mt-3 p-3 bg-light border rounded">
        <button type="button" id="btn_save_all" class="btn btn-success shadow-sm font-weight-bold ml-2">
            <i class="fas fa-check-double mr-1"></i> حفظ جميع التعديلات دفعة واحدة
        </button>
        <button type="button" id="btn_cancel_all" class="btn btn-secondary shadow-sm font-weight-bold">
            <i class="fas fa-undo mr-1"></i> إلغاء التغييرات والتراجع
        </button>
    </div>
@endif

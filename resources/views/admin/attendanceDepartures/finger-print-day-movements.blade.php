@if ($is_editable)
<div class="card card-outline card-warning shadow-sm mb-4">
    <div class="card-header py-2 bg-light">
        <h6 class="card-title font-weight-bold text-dark mb-0">
            <i class="fas fa-user-edit text-warning mr-1"></i>
            تعديل توقيت البصمة والحركات اليدوية لليوم: <span class="text-primary">{{ $date }}</span>
        </h6>
    </div>
    <div class="card-body p-3">
        <form id="edit_day_movements_form">
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="row">
                <!-- Check-in Section -->
                <div class="col-md-6 border-left">
                    <h6 class="font-weight-bold text-success mb-3">
                        <i class="fas fa-sign-in-alt"></i> توقيت الحضور (Check-In)
                    </h6>
                    @php
                        $check_in_dt = $attendance && $attendance->checkInDateTime ? date('Y-m-d', strtotime($attendance->checkInDateTime)) : '';
                        $check_in_t = $attendance && $attendance->checkInDateTime ? date('H:i', strtotime($attendance->checkInDateTime)) : '';
                    @endphp
                    <div class="form-row align-items-center">
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">التاريخ</label>
                            <input type="date" class="form-control form-control-sm" id="edit_check_in_date" name="check_in_date" value="{{ $check_in_dt }}">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">الوقت</label>
                            <input type="time" class="form-control form-control-sm" id="edit_check_in_time" name="check_in_time" value="{{ $check_in_t }}">
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-start">
                        <button type="button" class="btn btn-xs btn-outline-secondary mr-2 shadow-sm" onclick="resetCheckIn()" title="إعادة تعيين للتاريخ الحالي">
                            <i class="fas fa-undo-alt mr-1"></i> إعادة تعيين
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-danger shadow-sm" onclick="clearCheckIn()" title="مسح التاريخ والوقت">
                            <i class="fas fa-trash-alt mr-1"></i> مسح
                        </button>
                    </div>
                </div>

                <!-- Check-out Section -->
                <div class="col-md-6">
                    <h6 class="font-weight-bold text-info mb-3">
                        <i class="fas fa-sign-out-alt"></i> توقيت الانصراف (Check-Out)
                    </h6>
                    @php
                        $check_out_dt = $attendance && $attendance->checkOutDateTime ? date('Y-m-d', strtotime($attendance->checkOutDateTime)) : '';
                        $check_out_t = $attendance && $attendance->checkOutDateTime ? date('H:i', strtotime($attendance->checkOutDateTime)) : '';
                    @endphp
                    <div class="form-row align-items-center">
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">التاريخ</label>
                            <input type="date" class="form-control form-control-sm" id="edit_check_out_date" name="check_out_date" value="{{ $check_out_dt }}">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">الوقت</label>
                            <input type="time" class="form-control form-control-sm" id="edit_check_out_time" name="check_out_time" value="{{ $check_out_t }}">
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-start">
                        <button type="button" class="btn btn-xs btn-outline-secondary mr-2 shadow-sm" onclick="resetCheckOut()" title="إعادة تعيين للتاريخ الحالي">
                            <i class="fas fa-undo-alt mr-1"></i> إعادة تعيين
                        </button>
                        <button type="button" class="btn btn-xs btn-outline-danger shadow-sm" onclick="clearCheckOut()" title="مسح التاريخ والوقت">
                            <i class="fas fa-trash-alt mr-1"></i> مسح
                        </button>
                    </div>
                </div>
            </div>
            
            <hr class="my-3">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-success mr-2 shadow-sm font-weight-bold" id="btn_update_day_movements">
                    <i class="fas fa-save mr-1"></i> تحديث
                </button>
                <button type="button" class="btn btn-sm btn-secondary shadow-sm" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    var originalCheckInDate = "{{ $check_in_dt }}";
    var originalCheckInTime = "{{ $check_in_t }}";
    var originalCheckOutDate = "{{ $check_out_dt }}";
    var originalCheckOutTime = "{{ $check_out_t }}";

    function resetCheckIn() {
        document.getElementById('edit_check_in_date').value = originalCheckInDate;
        document.getElementById('edit_check_in_time').value = originalCheckInTime;
    }

    function clearCheckIn() {
        document.getElementById('edit_check_in_date').value = '';
        document.getElementById('edit_check_in_time').value = '';
    }

    function resetCheckOut() {
        document.getElementById('edit_check_out_date').value = originalCheckOutDate;
        document.getElementById('edit_check_out_time').value = originalCheckOutTime;
    }

    function clearCheckOut() {
        document.getElementById('edit_check_out_date').value = '';
        document.getElementById('edit_check_out_time').value = '';
    }
</script>
@endif

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle mb-0">
        <thead class="bg-dark text-white">
            <tr>
                <th style="width: 50px;">#</th>
                <th>وقت الحركة</th>
                <th>نوع الحركة</th>
                <th>طريقة الإضافة</th>
                <th>حالة الاعتماد</th>
                <th>الاستخدام في اليوم</th>
                <th>تاريخ سحب البصمة</th>
                <th>بواسطة</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($actions as $action)
                @php
                    $excel = $action->excelAction;
                    $time_display = $excel ? date('h:i:s A', strtotime($excel->dateTimeAction)) : date('h:i:s A', strtotime($action->dateTimeAction));
                    $time_24 = $excel ? date('H:i:s', strtotime($excel->dateTimeAction)) : date('H:i:s', strtotime($action->dateTimeAction));
                    $upload_display = $excel && $excel->created_at ? $excel->created_at->format('Y-m-d h:i A') : '---';
                    
                    $is_taken_as_checkin = false;
                    $is_taken_as_checkout = false;
                    $is_taken_prev_checkin = false;
                    $is_taken_prev_checkout = false;
                    $is_taken_next_checkin = false;
                    $is_taken_next_checkout = false;
                    
                    $action_time = date('H:i:s', strtotime($time_24));
                    
                    if ($action->is_active_with_parent == 1 && $action->attendances_departure_id) {
                        if (isset($attendance) && $action->attendances_departure_id == $attendance->id) {
                            if ($action->type == 1) {
                                $is_taken_as_checkin = true;
                            } elseif ($action->type == 2) {
                                $is_taken_as_checkout = true;
                            }
                        } elseif (isset($attendancePrev) && $action->attendances_departure_id == $attendancePrev->id) {
                            if ($action->type == 1) {
                                $is_taken_prev_checkin = true;
                            } elseif ($action->type == 2) {
                                $is_taken_prev_checkout = true;
                            }
                        } elseif (isset($attendanceNext) && $action->attendances_departure_id == $attendanceNext->id) {
                            if ($action->type == 1) {
                                $is_taken_next_checkin = true;
                            } elseif ($action->type == 2) {
                                $is_taken_next_checkout = true;
                            }
                        }
                    } else {
                        // Fallback to time-based matching (e.g. if the action is unapproved or doesn't have an ID link)
                        if (isset($attendance)) {
                            if ($attendance->checkInTime) {
                                $is_taken_as_checkin = (date('H:i:s', strtotime($attendance->checkInTime)) === $action_time);
                            }
                            if ($attendance->checkOutTime) {
                                $is_taken_as_checkout = (date('H:i:s', strtotime($attendance->checkOutTime)) === $action_time);
                            }
                        }
                        
                        if (isset($attendancePrev)) {
                            if ($attendancePrev->checkInTime) {
                                $is_taken_prev_checkin = (date('H:i:s', strtotime($attendancePrev->checkInTime)) === $action_time);
                            }
                            if ($attendancePrev->checkOutTime) {
                                $is_taken_prev_checkout = (date('H:i:s', strtotime($attendancePrev->checkOutTime)) === $action_time);
                            }
                        }
                        
                        if (isset($attendanceNext)) {
                            if ($attendanceNext->checkInTime) {
                                $is_taken_next_checkin = (date('H:i:s', strtotime($attendanceNext->checkInTime)) === $action_time);
                            }
                            if ($attendanceNext->checkOutTime) {
                                $is_taken_next_checkout = (date('H:i:s', strtotime($attendanceNext->checkOutTime)) === $action_time);
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="font-weight-bold text-primary" title="نظام 24 ساعة: {{ $time_24 }}">{{ $time_display }}</td>
                    <td>
                        @if ($action->type == 1)
                            <span class="badge badge-success px-3 py-1">
                                <i class="fas fa-sign-in-alt mr-1"></i> حضور
                            </span>
                        @elseif ($action->type == 2)
                            <span class="badge badge-info px-3 py-1">
                                <i class="fas fa-sign-out-alt mr-1"></i> انصراف
                            </span>
                        @else
                            <span class="badge badge-secondary px-3 py-1">
                                غير محدد
                            </span>
                        @endif

                        @if ($excel && $excel->type && $excel->type != $action->type)
                            <div class="mt-1">
                                <span class="badge badge-warning text-dark px-2 py-1" style="font-size: 75%; font-weight: normal;" title="تم تعديل النوع تلقائياً ليناسب منطق الشيفت والوردية">
                                    <i class="fas fa-exchange-alt mr-1"></i> 
                                    الأصل: {{ $excel->type == 1 ? 'حضور' : ($excel->type == 2 ? 'انصراف' : 'غير محدد') }}
                                </span>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($action->added_method == 2)
                            <span class="badge badge-secondary px-3 py-1">
                                <i class="fas fa-edit mr-1"></i> يدوي
                            </span>
                        @else
                            <span class="badge badge-info px-3 py-1">
                                <i class="fas fa-laptop mr-1"></i> تلقائي
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($action->is_active_with_parent == 1)
                            <span class="badge badge-success px-3 py-1">
                                <i class="fas fa-check-circle mr-1"></i> معتمدة
                            </span>
                        @else
                            <span class="badge badge-warning px-3 py-1">
                                <i class="fas fa-exclamation-circle mr-1"></i> غير معتمدة
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($is_taken_as_checkin)
                            <span class="badge badge-success px-3 py-1">
                                <i class="fas fa-sign-in-alt mr-1"></i> احتسبت كحضور
                            </span>
                        @elseif ($is_taken_as_checkout)
                            <span class="badge badge-primary px-3 py-1">
                                <i class="fas fa-sign-out-alt mr-1"></i> احتسبت كانصراف
                            </span>
                        @elseif ($is_taken_prev_checkin)
                            <span class="badge badge-warning px-3 py-1">
                                <i class="fas fa-sign-in-alt mr-1"></i> حضور لليوم السابق
                            </span>
                        @elseif ($is_taken_prev_checkout)
                            <span class="badge badge-purple px-3 py-1" style="background-color: #6f42c1 !important; color: white;">
                                <i class="fas fa-sign-out-alt mr-1"></i> انصراف لليوم السابق
                            </span>
                        @elseif ($is_taken_next_checkin)
                            <span class="badge badge-warning px-3 py-1">
                                <i class="fas fa-sign-in-alt mr-1"></i> حضور لليوم التالي
                            </span>
                        @elseif ($is_taken_next_checkout)
                            <span class="badge badge-orange px-3 py-1" style="background-color: #fd7e14 !important; color: white;">
                                <i class="fas fa-sign-out-alt mr-1"></i> انصراف لليوم التالي
                            </span>
                        @else
                            <span class="text-muted small">---</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $upload_display }}</td>
                    <td>{{ optional($action->addedBy)->name ?? 'النظام' }}</td>
                    <td>{{ $action->notes ?? '---' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="alert alert-warning mb-0 text-center py-3">
                            لا توجد حركات بصمة مسجلة لهذا اليوم.
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

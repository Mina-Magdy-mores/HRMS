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

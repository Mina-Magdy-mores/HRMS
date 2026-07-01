<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center align-middle">
        <thead class="bg-primary text-white">
            <tr>
                <th style="width: 50px;">#</th>
                <th>أهمية</th>
                <th>الاسم/العملية</th>
                <th>القسم/المديول</th>
                <th>نوع الحركة</th>
                <th>المسؤول المنفذ</th>
                <th style="width: 250px;">الوصف والتفاصيل</th>
                <th>التاريخ والوقت</th>
                <th style="width: 120px;">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @php
                $moveTypeBadges = [
                    'إضافة' => 'success',
                    'تعديل' => 'warning text-white',
                    'حذف' => 'danger',
                    'تفعيل' => 'info',
                    'إيقاف' => 'dark',
                    'أرشفة' => 'secondary',
                    'اعتماد' => 'success',
                    'اعتماد الراتب' => 'success',
                    'إغلاق الراتب' => 'danger',
                    'ترحيل' => 'primary',
                    'تقسيط' => 'primary',
                    'تسديد قسط' => 'success',
                    'فتح السنة المالية' => 'success',
                    'إغلاق السنة المالية' => 'danger',
                    'إضافة ملف' => 'info',
                    'حذف ملف' => 'danger',
                    'تحميل ملف' => 'dark',
                    'إضافة بدل ثابت' => 'success',
                    'تعديل بدل ثابت' => 'warning text-white',
                    'حذف بدل ثابت' => 'danger',
                    'تسوية رصيد' => 'warning text-white',
                    'تغيير كلمة المرور' => 'danger',
                    'إغلاق التحقيق' => 'dark',
                    'عرض' => 'light',
                    'طباعة' => 'light',
                    'تمييز' => 'warning text-white',
                ];
                $moduleIcons = [
                    'الضبط العام' => 'cogs text-info',
                    'السنوات المالية' => 'calendar text-success',
                    'الفروع' => 'building text-primary',
                    'أنواع الشفتات' => 'clock text-warning',
                    'إدارات الموظفين' => 'users text-indigo',
                    'تصنيفات الوظائف' => 'briefcase text-secondary',
                    'مؤهلات الموظفين' => 'graduation-cap text-muted',
                    'المناسبات الرسمية' => 'calendar text-danger',
                    'أنواع الإجازات' => 'calendar-alt text-teal',
                    'انواع استقالات الموظفين' => 'sign-out-alt text-dark',
                    'الجنسية' => 'flag text-orange',
                    'الأديان' => 'pray text-purple',
                    'فصائل الدم' => 'tint text-danger',
                    'الدول' => 'globe text-info',
                    'المحافظات' => 'map text-success',
                    'المدن' => 'city text-primary',
                    'بيانات الموظفين' => 'users text-primary',
                    'انواع البدل للراتب' => 'hand-holding-usd text-success',
                    'انواع الخصم للراتب' => 'file-invoice-dollar text-danger',
                    'انواع المكافآت للراتب' => 'award text-warning',
                    'بيانات رواتب الموظفين' => 'users text-secondary',
                    'الجزاءات اليدويه' => 'gavel text-danger',
                    'خصم الغياب اليدوي' => 'calendar-times text-warning',
                    'أضافه الأيام اليدوي' => 'calendar-plus text-success',
                    'الخصومات المالية المسجلة' => 'minus-circle text-danger',
                    'المكافئات المالية المسجلة' => 'trophy text-warning',
                    'البدلات المالية المسجلة' => 'plus-circle text-info',
                    'السلف الشهرية' => 'hand-holding-usd text-success',
                    'السلف المستديمة' => 'coins text-warning',
                    'رواتب الموظفين مفصله' => 'print text-primary',
                    'سجلات البصمات' => 'fingerprint text-info',
                    'أرصدة إجازات الموظفين' => 'calendar-check text-success',
                    'بروفايل الادمين' => 'users-cog text-secondary',
                    'التحقيقات الإدارية' => 'search text-info',
                    'مراقبة النظام' => 'desktop text-warning',
                ];
            @endphp
            @forelse ($monitorings as $log)
                <tr id="log-row-{{ $log->id }}" class="{{ $log->is_important ? 'table-warning font-weight-bold' : '' }}">
                    <td>{{ $log->id }}</td>
                    <td>
                        <button class="btn btn-link p-0 toggle-important-btn" data-id="{{ $log->id }}" style="font-size: 1.2rem; transition: transform 0.2s ease-in-out;">
                            @if ($log->is_important)
                                <i class="fas fa-star text-warning star-icon animate-bounce"></i>
                            @else
                                <i class="far fa-star text-muted star-icon"></i>
                            @endif
                        </button>
                    </td>
                    <td class="text-right font-weight-bold text-dark">{{ $log->name }}</td>
                    <td>
                        @php
                            $moduleName = optional($log->alertModule)->name ?? '';
                            $moduleIcon = $moduleIcons[$moduleName] ?? 'cube text-secondary';
                        @endphp
                        <span class="badge badge-light border px-2 py-1">
                            <i class="fas fa-{{ $moduleIcon }} mr-1"></i>
                            {{ $moduleName ?: '---' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $actionName = optional($log->alertMoveType)->name ?? '---';
                            $badgeColor = $moveTypeBadges[$actionName] ?? 'light';
                        @endphp
                        <span class="badge badge-{{ $badgeColor }} px-3 py-2">
                            {{ $actionName }}
                        </span>
                    </td>
                    <td>
                        <span class="text-secondary font-weight-bold">
                            <i class="fas fa-user-shield mr-1"></i>
                            {{ optional($log->addedBy)->name ?? '---' }}
                        </span>
                    </td>
                    <td class="text-right text-muted small" style="white-space: pre-wrap; word-break: break-all;">{{ $log->content }}</td>
                    <td class="direction-ltr">
                        <span class="badge badge-secondary font-weight-normal px-2 py-1">
                            {{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '---' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center">
                            <!-- View Detail Button -->
                            <button class="btn btn-sm btn-info m-1 show-details-btn" 
                                    data-id="{{ $log->id }}"
                                    data-name="{{ $log->name }}"
                                    data-module="{{ optional($log->alertModule)->name }}"
                                    data-action="{{ optional($log->alertMoveType)->name }}"
                                    data-admin="{{ optional($log->addedBy)->name }}"
                                    data-content="{{ $log->content }}"
                                    data-date="{{ $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : '---' }}"
                                    data-notes="{{ $log->notes }}"
                                    title="عرض التفاصيل الكاملة">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.system-monitoring.destroy', $log->id) }}" method="POST" class="m-0 delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger destroy-btn m-1" title="حذف السجل">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">
                        <div class="alert alert-warning mb-0 text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block text-warning animate-pulse"></i>
                            <h5 class="font-weight-bold">لا توجد سجلات مراقبة حالياً</h5>
                            <p class="mb-0 text-muted small">جرب تغيير معايير البحث أو تفعيل مراقبة النظام من الضبط العام.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-between align-items-center" id="ajax-pagination">
    <div class="text-muted small">
        عرض {{ $monitorings->firstItem() ?? 0 }} إلى {{ $monitorings->lastItem() ?? 0 }} من إجمالي {{ $monitorings->total() }} سجل
    </div>
    <div>
        {{ $monitorings->links() }}
    </div>
</div>

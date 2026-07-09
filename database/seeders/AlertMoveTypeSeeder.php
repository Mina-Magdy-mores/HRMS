<?php

namespace Database\Seeders;

use App\Models\AlertModule;
use App\Models\AlertMoveType;
use Illuminate\Database\Seeder;

class AlertMoveTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Define common actions
        $commonCrud = ['إضافة', 'تعديل', 'حذف'];
        $approvalCrud = ['إضافة', 'تعديل', 'حذف', 'اعتماد'];

        // Map specific modules to their actions
        $moduleActions = [
            'الضبط العام' => ['تعديل'],
            'السنوات المالية' => ['إضافة', 'تعديل', 'حذف', 'فتح السنة المالية', 'إغلاق السنة المالية', 'فتح الشهر المالي', 'أرشفة الشهر المالي'],
            'الفروع' => $commonCrud,
            'أنواع الشفتات' => $commonCrud,
            'إدارات الموظفين' => $commonCrud,
            'تصنيفات الوظائف' => $commonCrud,
            'مؤهلات الموظفين' => $commonCrud,
            'المناسبات الرسمية' => $commonCrud,
            'أنواع الإجازات' => $commonCrud,
            'انواع استقالات الموظفين' => $commonCrud,
            'الجنسية' => $commonCrud,
            'الأديان' => $commonCrud,
            'فصائل الدم' => $commonCrud,
            'الدول' => $commonCrud,
            'المحافظات' => $commonCrud,
            'المدن' => $commonCrud,
            'بيانات الموظفين' => ['إضافة', 'تعديل', 'حذف', 'أرشفة', 'تفعيل', 'إيقاف', 'إضافة ملف', 'حذف ملف', 'تحميل ملف', 'إضافة بدل ثابت', 'تعديل بدل ثابت', 'حذف بدل ثابت'],
            'انواع البدل للراتب' => $commonCrud,
            'انواع الخصم للراتب' => $commonCrud,
            'انواع المكافآت للراتب' => $commonCrud,
            'بيانات رواتب الموظفين' => ['إضافة', 'تعديل', 'حذف', 'اعتماد الراتب', 'إغلاق الراتب', 'ترحيل', 'أرشفة راتب موظف', 'أرشفة رواتب الموظفين'],
            'الجزاءات اليدويه' => $approvalCrud,
            'خصم الغياب اليدوي' => $approvalCrud,
            'أضافه الأيام اليدوي' => $approvalCrud,
            'الخصومات المالية المسجلة' => $commonCrud,
            'المكافئات المالية المسجلة' => $commonCrud,
            'البدلات المالية المسجلة' => $commonCrud,
            'السلف الشهرية' => $approvalCrud,
            'السلف المستديمة' => ['إضافة', 'تعديل', 'حذف', 'اعتماد', 'تقسيط', 'تسديد قسط'],
            'رواتب الموظفين مفصله' => ['عرض', 'طباعة'],
            'سجلات البصمات' => ['إضافة', 'تعديل', 'حذف', 'استيراد'],
            'أرصدة إجازات الموظفين' => ['إضافة', 'تعديل', 'حذف', 'تسوية رصيد'],
            'بروفايل الادمين' => ['إضافة', 'تعديل', 'حذف', 'أرشفة', 'تغيير كلمة المرور'],
            'التحقيقات الإدارية' => ['إضافة', 'تعديل', 'حذف', 'إغلاق التحقيق', 'أرشفة'],
            'مراقبة النظام' => ['تمييز', 'حذف'],
            'الصلاحيات' => ['إضافة مستخدمين', 'تعديل', 'حذف'],
            'تسويات رواتب الموظفين المؤرشفة' => $commonCrud,
            'أنواع منح الرواتب' => $commonCrud,
            'المكافئات المباشرة' => $commonCrud,
            'المنح المباشرة' => $commonCrud,
            'مهام الموظفين' => ['إضافة', 'تعديل', 'حذف', 'أرشفة', 'تغيير حالة', 'رد الموظف'],
            'أنواع طلبات الموظفين' => ['إضافة', 'تعديل', 'حذف'],
            'طلبات الموظفين' => ['إضافة', 'تعديل', 'حذف', 'أرشفة', 'تغيير الحالة', 'تعليق جديد'],
        ];

        $totalSeeded = 0;

        foreach ($moduleActions as $moduleName => $actions) {
            // Find the module in the database
            $module = AlertModule::where('name', $moduleName)->first();

            if ($module) {
                foreach ($actions as $actionName) {
                    AlertMoveType::updateOrCreate(
                        [
                            'name' => $actionName,
                            'alert_module_id' => $module->id,
                            'company_id' => 1
                        ],
                        [
                            'alert_module_id' => $module->id,
                            'added_by' => 1,
                            'updated_by' => 1,
                            'is_active' => 1,
                            'company_id' => 1,
                        ]
                    );
                    $totalSeeded++;
                }
            } else {
                $this->command->warn("⚠️ Warning: Parent module '{$moduleName}' not found in alert_modules table!");
            }
        }

        $this->command->info("✅ Alert Move Types seeded: {$totalSeeded} records successfully!");
    }
}

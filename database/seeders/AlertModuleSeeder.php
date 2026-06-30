<?php

namespace Database\Seeders;

use App\Models\AlertModule;
use Illuminate\Database\Seeder;

class AlertModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['name' => 'الضبط العام'],
            ['name' => 'السنوات المالية'],
            ['name' => 'الفروع'],
            ['name' => 'أنواع الشفتات'],
            ['name' => 'إدارات الموظفين'],
            ['name' => 'تصنيفات الوظائف'],
            ['name' => 'مؤهلات الموظفين'],
            ['name' => 'المناسبات الرسمية'],
            ['name' => 'أنواع الإجازات'],
            ['name' => 'انواع استقالات الموظفين'],
            ['name' => 'الجنسية'],
            ['name' => 'الأديان'],
            ['name' => 'فصائل الدم'],
            ['name' => 'الدول'],
            ['name' => 'المحافظات'],
            ['name' => 'المدن'],
            ['name' => 'بيانات الموظفين'],
            ['name' => 'انواع البدل للراتب'],
            ['name' => 'انواع الخصم للراتب'],
            ['name' => 'انواع المكافآت للراتب'],
            ['name' => 'بيانات رواتب الموظفين'],
            ['name' => 'الجزاءات اليدويه'],
            ['name' => 'خصم الغياب اليدوي'],
            ['name' => 'أضافه الأيام اليدوي'],
            ['name' => 'الخصومات المالية المسجلة'],
            ['name' => 'المكافئات المالية المسجلة'],
            ['name' => 'البدلات المالية المسجلة'],
            ['name' => 'السلف الشهرية'],
            ['name' => 'السلف المستديمة'],
            ['name' => 'رواتب الموظفين مفصله'],
            ['name' => 'سجلات البصمات'],
            ['name' => 'أرصدة إجازات الموظفين'],
            ['name' => 'بروفايل الادمين'],
            ['name' => 'التحقيقات الإدارية'],
            ['name' => 'مراقبة النظام'],
            ['name' => 'الصلاحيات'],
        ];

        foreach ($modules as $module) {
            AlertModule::updateOrCreate(
                ['name' => $module['name'], 'company_id' => 1],
                [
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                    'is_active' => 1,
                ]
            );
        }

        $this->command->info('✅ Alert Modules seeded: ' . count($modules) . ' records');
    }
}

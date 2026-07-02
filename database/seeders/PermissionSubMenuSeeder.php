<?php

namespace Database\Seeders;

use App\Models\PermissionMainMenu;
use App\Models\PermissionSubMenu;
use Illuminate\Database\Seeder;

class PermissionSubMenuSeeder extends Seeder
{
    public function run(): void
    {
        $submenus = [
            'قائمة الضبط' => [
                'الضبط العام',
                'السنوات المالية',
                'الفروع',
                'أنواع الشفتات',
                'إدارات الموظفين',
                'تصنيفات الوظائف',
                'مؤهلات الموظفين',
                'المناسبات الرسمية',
                'أنواع الإجازات',
                'انواع استقالات الموظفين',
                'الجنسية',
                'الأديان',
                'فصائل الدم',
                'الدول',
                'المحافظات',
                'المدن',
            ],
            'قائمة شئون الموظفين' => [
                'بيانات الموظفين',
                'انواع البدل للراتب',
                'انواع الخصم للراتب',
                'انواع المكافآت للراتب',
            ],
            'قائمة أجور الموظفين' => [
                'بيانات رواتب الموظفين',
                'الجزاءات اليدويه',
                'خصم الغياب اليدوي',
                'أضافه الأيام اليدوي',
                'الخصومات المالية المسجلة',
                'المكافئات المالية المسجلة',
                'البدلات المالية المسجلة',
                'السلف الشهرية',
                'السلف المستديمة',
                'رواتب الموظفين مفصله',
            ],
            'الحضور والانصراف' => [
                'سجلات البصمات',
                'أرصدة إجازات الموظفين',
            ],
            'التحقيقات الإدارية' => [
                'التحقيقات الإدارية',
            ],
            'مراقبة النظام' => [
                'سجلات النظام العامة',
                'سجلات المراقبة الذاتية',
            ],
            'الصلاحيات والادوار' => [
                'المستخدمين',
                'ادوار المستخدمين',
                'القوائم الرئيسيه للصلاحيات',
                'القوائم الفرعيه للصلاحيات',
            ],
        ];

        foreach ($submenus as $mainMenuName => $subs) {
            $mainMenu = PermissionMainMenu::where('name', $mainMenuName)->first();
            if ($mainMenu) {
                foreach ($subs as $sub) {
                    PermissionSubMenu::updateOrCreate(
                        [
                            'permission_main_menu_id' => $mainMenu->id,
                            'name' => $sub,
                        ],
                        [
                            'is_active' => 1,
                            'added_by' => 1,
                            'updated_by' => 1,
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ Sub menus seeded successfully!');
    }
}

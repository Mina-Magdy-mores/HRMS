<?php

namespace Database\Seeders;

use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use Illuminate\Database\Seeder;

class PermissionSubMenuActionSeeder extends Seeder
{
    public function run(): void
    {
        // First delete all actions for 'رواتب الموظفين مفصله', 'بيانات رواتب الموظفين' and 'الضبط العام' to rebuild them cleanly
        $detailedSalarySubMenu = PermissionSubMenu::where('name', 'رواتب الموظفين مفصله')->first();
        if ($detailedSalarySubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $detailedSalarySubMenu->id)->delete();
        }

        $recordsSalarySubMenu = PermissionSubMenu::where('name', 'بيانات رواتب الموظفين')->first();
        if ($recordsSalarySubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $recordsSalarySubMenu->id)->delete();
        }

        $generalSettingsSubMenu = PermissionSubMenu::where('name', 'الضبط العام')->first();
        if ($generalSettingsSubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $generalSettingsSubMenu->id)->delete();
        }

        $systemLogsSubMenu = PermissionSubMenu::where('name', 'سجلات النظام العامة')->first();
        if ($systemLogsSubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $systemLogsSubMenu->id)->delete();
        }

        $selfLogsSubMenu = PermissionSubMenu::where('name', 'سجلات المراقبة الذاتية')->first();
        if ($selfLogsSubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $selfLogsSubMenu->id)->delete();
        }

        $vacationBalancesSubMenu = PermissionSubMenu::where('name', 'أرصده إجازات الموظفين')->first();
        if ($vacationBalancesSubMenu) {
            PermissionSubMenuAction::where('permission_sub_menu_id', $vacationBalancesSubMenu->id)->delete();
        }

        $subMenus = PermissionSubMenu::all();
        $defaultActions = ['عرض', 'إضافة', 'تعديل', 'حذف'];
        
        $menusWithPrint = [
            'بيانات الموظفين',
            'الجزاءات اليدويه',
            'خصم الغياب اليدوي',
            'أضافه الأيام اليدوي',
            'الخصومات المالية المسجلة',
            'المكافئات المالية المسجلة',
            'البدلات المالية المسجلة',
            'السلف الشهرية',
            'السلف المستديمة',
            'سجلات البصمات',
            'التحقيقات الإدارية',
            'تسويات رواتب الموظفين المؤرشفة',
        ];

        foreach ($subMenus as $subMenu) {
            if ($subMenu->name === 'الضبط العام') {
                $currentActions = [
                    'عرض',
                    'تعديل'
                ];
            } elseif ($subMenu->name === 'بيانات رواتب الموظفين') {
                $currentActions = [
                    'عرض',
                    'إضافة',
                    'تعديل',
                    'حذف',
                    'أرشفة وإغلاق الشهر بالكامل لكافة الموظفين',
                    'فتح الشهر المالي'
                ];
            } elseif ($subMenu->name === 'رواتب الموظفين مفصله') {
                $currentActions = [
                    'عرض',
                    'إضافة',
                    'تعديل',
                    'حذف',
                    'إضافة راتب للموظف',
                    'حذف سجل الراتب للموظف',
                    'إيقاف وتفعيل صرف الراتب',
                    'أرشفة وإغلاق الشهر بالكامل',
                    'أرشفة سجل الراتب للموظف',
                    'طباعة كشف الرواتب للبحث',
                    'طباعة التفاصيل الكاملة للبحث',
                    'طباعة كل الموظفين بالتفاصيل (للإدارة)'
                ];
            } elseif ($subMenu->name === 'سجلات النظام العامة') {
                $currentActions = [
                    'عرض',
                    'تمييز',
                    'حذف'
                ];
            } elseif ($subMenu->name === 'سجلات المراقبة الذاتية') {
                $currentActions = [
                    'عرض',
                    'حذف'
                ];
            } elseif ($subMenu->name === 'أرصده إجازات الموظفين') {
                $currentActions = [
                    'عرض',
                    'تعديل'
                ];
            } else {
                $currentActions = $defaultActions;

                // Add Print for matching submenus
                if (in_array($subMenu->name, $menusWithPrint)) {
                    $currentActions[] = 'طباعة';
                }

                // Map other custom actions for specific submenus
                switch ($subMenu->name) {
                    case 'بيانات الموظفين':
                        $currentActions[] = 'أرشيف';
                        break;
                    case 'السلف المستديمة':
                        $currentActions[] = 'صرف السلفة';
                        $currentActions[] = 'دفع قسط';
                        break;
                    case 'سجلات البصمات':
                        $currentActions[] = 'رفع بصمة';
                        $currentActions[] = 'تعديل حركة يوم';
                        break;
                    case 'المستخدمين':
                    case 'بروفايل الادمين':
                        $currentActions[] = 'أرشيف';
                        break;
                }
            }

            // Clean array to avoid duplicates
            $currentActions = array_unique($currentActions);

            foreach ($currentActions as $actionName) {
                PermissionSubMenuAction::updateOrCreate(
                    [
                        'permission_sub_menu_id' => $subMenu->id,
                        'name' => $actionName,
                    ],
                    [
                        'is_active' => 1,
                        'added_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }
        }

        $this->command->info('✅ Custom sub menu actions seeded successfully!');
    }
}

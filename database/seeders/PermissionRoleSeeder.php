<?php

namespace Database\Seeders;

use App\Models\PermissionMainMenu;
use App\Models\PermissionSubMenu;
use App\Models\PermissionSubMenuAction;
use App\Models\PermissionRole;
use App\Models\PermissionRoleMainMenu;
use App\Models\PermissionRoleSubMenu;
use App\Models\PermissionRoleSubMenuAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Enterprise Roles for Company 1
        $rolesData = [
            ['name' => 'مدير عام الشركة (General Manager)'],
            ['name' => 'مدير النظام الكامل (Full Control)'],
            ['name' => 'مدير الموارد البشرية (HR Manager)'],
            ['name' => 'أخصائي الموارد البشرية (HR Specialist)'],
            ['name' => 'مدير الحسابات (Finance Manager)'],
            ['name' => 'المحاسب المالي (Finance Accountant)'],
            ['name' => 'مسؤول الحضور والانصراف (Attendance Officer)'],
            ['name' => 'المستشار القانوني (Legal Advisor)'],
        ];

        foreach ($rolesData as $roleData) {
            $role = PermissionRole::updateOrCreate(
                ['name' => $roleData['name'], 'company_id' => 1],
                [
                    'is_active' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );

            // Assign permissions based on role
            if (in_array($role->name, ['مدير عام الشركة (General Manager)', 'مدير النظام الكامل (Full Control)'])) {
                $this->grantAllPermissions($role);
            } elseif ($role->name === 'مدير الموارد البشرية (HR Manager)') {
                $this->grantHrManagerPermissions($role);
            } elseif ($role->name === 'أخصائي الموارد البشرية (HR Specialist)') {
                $this->grantHrSpecialistPermissions($role);
            } elseif ($role->name === 'مدير الحسابات (Finance Manager)') {
                $this->grantFinanceManagerPermissions($role);
            } elseif ($role->name === 'المحاسب المالي (Finance Accountant)') {
                $this->grantFinanceAccountantPermissions($role);
            } elseif ($role->name === 'مسؤول الحضور والانصراف (Attendance Officer)') {
                $this->grantAttendanceOfficerPermissions($role);
            } elseif ($role->name === 'المستشار القانوني (Legal Advisor)') {
                $this->grantLegalAdvisorPermissions($role);
            }
        }

        $this->command->info('✅ Roles and permissions seeded successfully!');
    }

    private function grantAllPermissions(PermissionRole $role)
    {
        $mainMenus = PermissionMainMenu::all();
        foreach ($mainMenus as $mainMenu) {
            $roleMainMenu = PermissionRoleMainMenu::updateOrCreate([
                'permission_role_id' => $role->id,
                'permission_main_menu_id' => $mainMenu->id,
            ], [
                'added_by' => 1,
                'updated_by' => 1,
            ]);

            $subMenus = $mainMenu->subMenus;
            foreach ($subMenus as $subMenu) {
                $roleSubMenu = PermissionRoleSubMenu::updateOrCreate([
                    'permission_role_main_menu_id' => $roleMainMenu->id,
                    'permission_sub_menu_id' => $subMenu->id,
                    'permission_role_id' => $role->id,
                ], [
                    'added_by' => 1,
                    'updated_by' => 1,
                ]);

                $actions = $subMenu->actions;
                foreach ($actions as $action) {
                    PermissionRoleSubMenuAction::updateOrCreate([
                        'permission_roles_sub_menue_id' => $roleSubMenu->id,
                        'permission_sub_menu_action_id' => $action->id,
                        'permission_role_id' => $role->id,
                    ], [
                        'added_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }
        }
    }

    private function grantHrManagerPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['قائمة شئون الموظفين', 'الحضور والانصراف', 'التحقيقات الإدارية', 'قائمة المهام', 'قائمة الطلبات'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantHrSpecialistPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['قائمة شئون الموظفين', 'الحضور والانصراف', 'قائمة المهام', 'قائمة الطلبات'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantFinanceManagerPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['قائمة أجور الموظفين'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantFinanceAccountantPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['قائمة أجور الموظفين'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantAttendanceOfficerPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['الحضور والانصراف'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantLegalAdvisorPermissions(PermissionRole $role)
    {
        $targetMainMenuNames = ['التحقيقات الإدارية'];
        $this->grantSpecificMainMenus($role, $targetMainMenuNames);
    }

    private function grantSpecificMainMenus(PermissionRole $role, array $targetMainMenuNames)
    {
        $mainMenus = PermissionMainMenu::whereIn('name', $targetMainMenuNames)->get();
        foreach ($mainMenus as $mainMenu) {
            $roleMainMenu = PermissionRoleMainMenu::updateOrCreate([
                'permission_role_id' => $role->id,
                'permission_main_menu_id' => $mainMenu->id,
            ], [
                'added_by' => 1,
                'updated_by' => 1,
            ]);

            $subMenus = $mainMenu->subMenus;
            foreach ($subMenus as $subMenu) {
                $roleSubMenu = PermissionRoleSubMenu::updateOrCreate([
                    'permission_role_main_menu_id' => $roleMainMenu->id,
                    'permission_sub_menu_id' => $subMenu->id,
                    'permission_role_id' => $role->id,
                ], [
                    'added_by' => 1,
                    'updated_by' => 1,
                ]);

                $actions = $subMenu->actions;
                foreach ($actions as $action) {
                    PermissionRoleSubMenuAction::updateOrCreate([
                        'permission_roles_sub_menue_id' => $roleSubMenu->id,
                        'permission_sub_menu_action_id' => $action->id,
                        'permission_role_id' => $role->id,
                    ], [
                        'added_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }
        }
    }
}

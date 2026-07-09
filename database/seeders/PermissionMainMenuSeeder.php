<?php

namespace Database\Seeders;

use App\Models\PermissionMainMenu;
use Illuminate\Database\Seeder;

class PermissionMainMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            'قائمة الضبط',
            'قائمة شئون الموظفين',
            'قائمة أجور الموظفين',
            'الحضور والانصراف',
            'التحقيقات الإدارية',
            'قائمة التسويات',
            'قائمة المهام',
            'قائمة الطلبات',
            'المحادثات',
            'مراقبة النظام',
            'الصلاحيات والادوار',
        ];

        foreach ($menus as $menu) {
            PermissionMainMenu::updateOrCreate(
                ['name' => $menu],
                [
                    'is_active' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Main menus seeded successfully!');
    }
}

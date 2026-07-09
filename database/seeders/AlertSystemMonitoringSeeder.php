<?php

namespace Database\Seeders;

use App\Models\AlertModule;
use App\Models\AlertMoveType;
use App\Models\AlertSystemMonitoring;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class AlertSystemMonitoringSeeder extends Seeder
{
    public function run(): void
    {
        $module = AlertModule::where('name', 'بيانات الموظفين')->where('company_id', 1)->first();
        $employee = Employee::where('company_id', 1)->first();

        if (!$module || !$employee) {
            return;
        }

        $moveType = AlertMoveType::where('alert_module_id', $module->id)->where('name', 'إضافة')->first();
        if (!$moveType) {
            return;
        }

        $monitoringLogs = [
            [
                'name' => 'إضافة موظف جديد',
                'content' => 'تم إضافة الموظف ' . $employee->name . ' بنجاح إلى النظام.',
                'is_important' => 1,
            ],
            [
                'name' => 'تعديل بيانات الموظف',
                'content' => 'تم تعديل بيانات الموظف ' . $employee->name . ' بواسطة مدير النظام.',
                'is_important' => 0,
            ]
        ];

        foreach ($monitoringLogs as $log) {
            AlertSystemMonitoring::create([
                'name' => $log['name'],
                'content' => $log['content'],
                'alert_module_id' => $module->id,
                'alert_move_type_id' => $moveType->id,
                'foreign_key_table_td' => $employee->id,
                'employee_id' => $employee->id,
                'is_important' => $log['is_important'],
                'is_active' => 1,
                'added_by' => 1,
                'company_id' => 1,
                'notes' => 'سجل مراقبة تجريبي تلقائي',
            ]);
        }

        $this->command->info('✅ Alert System Monitoring logs seeded successfully!');
    }
}

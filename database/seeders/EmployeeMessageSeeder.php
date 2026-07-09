<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\EmployeeMessage;
use Illuminate\Database\Seeder;

class EmployeeMessageSeeder extends Seeder
{
    public function run(): void
    {
        $employeeAdmins = Admin::where('is_employee', 1)->where('company_id', 1)->get();

        if ($employeeAdmins->isEmpty()) {
            return;
        }

        $chatLogs = [
            ['from_admin' => true, 'text' => 'مرحباً بك في نظام إدارة الموارد البشرية الجديد. هل تواجه أي مشكلة في استخدام النظام؟'],
            ['from_admin' => false, 'text' => 'أهلاً يا فندم، النظام ممتاز وسهل الاستخدام جداً. شكراً جزيلاً.'],
            ['from_admin' => true, 'text' => 'العفو، يمكنك دائماً تقديم طلباتك ومتابعة مهامك من خلال لوحة التحكم الخاصة بك.'],
            ['from_admin' => false, 'text' => 'تمام يا فندم، سأقوم بذلك. بالتوفيق.']
        ];

        foreach ($employeeAdmins as $empAdmin) {
            foreach ($chatLogs as $log) {
                EmployeeMessage::create([
                    'company_id' => 1,
                    'sender_id' => $log['from_admin'] ? 1 : $empAdmin->id,
                    'receiver_id' => $log['from_admin'] ? $empAdmin->id : 1,
                    'message' => $log['text'],
                    'is_read' => 1,
                ]);
            }
        }

        $this->command->info('✅ Employee Messages (Chat logs) seeded successfully!');
    }
}

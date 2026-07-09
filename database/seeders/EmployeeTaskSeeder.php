<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\EmployeeTaskComment;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class EmployeeTaskSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();

        if ($employees->isEmpty()) {
            return;
        }

        $tasksData = [
            [
                'title' => 'تحديث الملفات الطبية للمرضى الجدد',
                'content' => 'يرجى مراجعة وتحديث الملفات الطبية لكافة المرضى الذين تم إدخالهم في الأسبوع الأخير وتدقيق البيانات الشخصية.',
                'reply' => 'تم الانتهاء من تحديث 45 ملفاً وجاري مراجعة المتبقي.',
                'comment' => 'عمل ممتاز وسريع، شكراً لك.',
            ],
            [
                'title' => 'إعداد جدول النوبتجيات لشهر أغسطس',
                'content' => 'مطلوب إعداد مقترح لجدول النوبتجيات لشهر أغسطس بالتنسيق مع رئيس القسم لضمان التغطية الكاملة.',
                'reply' => 'قمت بإعداد المسودة المرفقة وأنتظر اعتمادها من رئيس القسم.',
                'comment' => 'يرجى الإسراع بالاعتماد قبل نهاية الأسبوع.',
            ],
            [
                'title' => 'تنظيم مخازن المستلزمات الطبية',
                'content' => 'يرجى عمل جرد سريع لمستلزمات العناية المركزة وتحديد النواقص لطلبها.',
                'reply' => null,
                'comment' => null,
            ]
        ];

        foreach ($employees as $employee) {
            // Find employee's admin account to use as comment sender if needed
            $empAdmin = Admin::where('employee_id', $employee->id)->first();
            $empAdminId = $empAdmin ? $empAdmin->id : 1;

            foreach ($tasksData as $index => $data) {
                // Determine completion state
                $isCompleted = ($index == 0) ? 1 : 0;
                
                $task = EmployeeTask::create([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'employee_id' => $employee->id,
                    'is_completed' => $isCompleted,
                    'is_archived' => 0,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                    'employee_reply' => $isCompleted ? $data['reply'] : null,
                    'employee_replied_at' => $isCompleted ? now()->subDays(1) : null,
                    'notes' => 'مهمة مولدة تلقائياً للتجربة',
                ]);

                if ($isCompleted && $data['comment']) {
                    // Create manager comment
                    EmployeeTaskComment::create([
                        'employee_task_id' => $task->id,
                        'admin_id' => 1, // Manager
                        'comment' => $data['comment'],
                    ]);

                    // Create employee reply comment
                    EmployeeTaskComment::create([
                        'employee_task_id' => $task->id,
                        'admin_id' => $empAdminId,
                        'comment' => 'شكرًا لتوجيهاتكم، تم التنفيذ بالكامل.',
                    ]);
                }
            }
        }

        $this->command->info('✅ Employee Tasks and Comments seeded successfully!');
    }
}

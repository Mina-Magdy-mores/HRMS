<?php

namespace Database\Seeders;

use App\Models\Qualification;
use Illuminate\Database\Seeder;

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        $qualifications = [
            ['name' => 'بكالوريوس طب وجراحة', 'status' => 1],
            ['name' => 'بكالوريوس تمريض', 'status' => 1],
            ['name' => 'بكالوريوس صيدلة', 'status' => 1],
            ['name' => 'بكالوريوس أسنان', 'status' => 1],
            ['name' => 'بكالوريوس علاج طبيعي', 'status' => 1],
            ['name' => 'بكالوريوس أشعة', 'status' => 1],
            ['name' => 'بكالوريوس مختبرات', 'status' => 1],
            ['name' => 'بكالوريوس إدارة', 'status' => 1],
            ['name' => 'بكالوريوس محاسبة', 'status' => 1],
            ['name' => 'بكالوريوس تجارة', 'status' => 1],
            ['name' => 'بكالوريوس آداب', 'status' => 1],
            ['name' => 'بكالوريوس تربية', 'status' => 1],
            ['name' => 'بكالوريوس علوم', 'status' => 1],
            ['name' => 'بكالوريوس حاسب آلي', 'status' => 1],
            ['name' => 'بكالوريوس هندسة', 'status' => 1],
            ['name' => 'بكالوريوس حقوق', 'status' => 1],
            ['name' => 'بكالوريوس اقتصاد', 'status' => 1],
            ['name' => 'بكالوريوس إعلام', 'status' => 1],
            ['name' => 'بكالوريوس سياحة وفنادق', 'status' => 1],
            ['name' => 'ماجستير إدارة مستشفيات', 'status' => 1],
            ['name' => 'ماجستير جودة', 'status' => 1],
            ['name' => 'دبلوم تمريض', 'status' => 1],
            ['name' => 'دبلوم مختبرات', 'status' => 1],
            ['name' => 'دبلوم أشعة', 'status' => 1],
            ['name' => 'دبلوم صيدلة', 'status' => 1],
            ['name' => 'دبلوم إدارة', 'status' => 1],
            ['name' => 'ثانوية عامة', 'status' => 1],
            ['name' => 'إعدادية', 'status' => 1],
            ['name' => 'دكتوراه', 'status' => 1],
            ['name' => 'زمالة', 'status' => 1],
        ];

        foreach ($qualifications as $qualification) {
            Qualification::updateOrCreate(
                ['name' => $qualification['name'], 'company_id' => 1],
                [
                    'status' => $qualification['status'],
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Qualifications seeded: ' . count($qualifications) . ' records');
    }
}

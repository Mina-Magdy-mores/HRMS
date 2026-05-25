<?php

namespace Database\Seeders;

use App\Models\AllowanceType;
use Illuminate\Database\Seeder;

class AllowanceTypeSeeder extends Seeder
{
    public function run(): void
    {
        $allowanceTypes = [
            ['name' => 'بدل نقل'],
            ['name' => 'بدل سكن'],
            ['name' => 'بدل غذاء'],
            ['name' => 'بدل مخاطر'],
            ['name' => 'بدل تمثيل'],
            ['name' => 'بدل إشراف'],
            ['name' => 'بدل تدريس'],
            ['name' => 'بدل طبيعة عمل'],
            ['name' => 'بدل سفر'],
            ['name' => 'بدل تدريب'],
            ['name' => 'بدل ساعات إضافية'],
            ['name' => 'بدل إنتاج'],
            ['name' => 'بدل جودة'],
            ['name' => 'بدل إجازات'],
            ['name' => 'بدل مكافأة نهاية خدمة'],
            ['name' => 'بدل غلاء معيشة'],
            ['name' => 'بدل عدوى'],
            ['name' => 'بدل ندرة'],
            ['name' => 'بدل خبرة'],
            ['name' => 'بدل تميز'],
        ];

        foreach ($allowanceTypes as $type) {
            AllowanceType::updateOrCreate(
                ['name' => $type['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Allowance Types seeded: ' . count($allowanceTypes) . ' records');
    }
}

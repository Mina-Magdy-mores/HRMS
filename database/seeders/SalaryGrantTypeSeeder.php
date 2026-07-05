<?php

namespace Database\Seeders;

use App\Models\SalaryGrantType;
use Illuminate\Database\Seeder;

class SalaryGrantTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'منحة عيد الفطر المبارك'],
            ['name' => 'منحة عيد الأضحى المبارك'],
            ['name' => 'منحة المولد النبوي الشريف'],
            ['name' => 'منحة العودة للمدارس والدراسة'],
            ['name' => 'منحة الزواج الاستثنائية'],
            ['name' => 'منحة المولود الجديد'],
            ['name' => 'منحة الأعياد والمناسبات الرسمية'],
            ['name' => 'منحة إضافية استثنائية'],
        ];

        foreach ($types as $type) {
            SalaryGrantType::updateOrCreate(
                ['name' => $type['name'], 'company_id' => 1],
                [
                    'company_id' => 1,
                    'status' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Salary Grant Types seeded: ' . count($types) . ' records successfully!');
    }
}

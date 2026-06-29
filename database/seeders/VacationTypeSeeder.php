<?php

namespace Database\Seeders;

use App\Models\VacationType;
use Illuminate\Database\Seeder;

class VacationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacationTypes = [
            ['name' => 'عارضة'],
            ['name' => 'اعتيادية'],
            ['name' => 'مرضية'],
            ['name' => 'إجازة رسمية'],
            ['name' => 'راحة أسبوعية'],
            ['name' => 'بدون راتب'],
            ['name' => 'وضع / أمومة'],
            ['name' => 'إجازة حج'],
            ['name' => 'إجازة زواج'],
            ['name' => 'عزاء / وفاة'],
            ['name' => 'مأمورية عمل'],
            ['name' => 'إذن رسمي'],
            ['name' => 'إجازة بدون إذن'],
            ['name' => 'إجازة ميلاد'],
            ['name' => 'إجازة عيد ميلاد'],
            ['name' => 'إجازة سنوية'],
        ];

        foreach ($vacationTypes as $type) {
            VacationType::updateOrCreate(
                ['name' => $type['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Vacation Types seeded: ' . count($vacationTypes) . ' records');
    }
}

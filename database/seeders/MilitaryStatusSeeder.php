<?php

namespace Database\Seeders;

use App\Models\MilitaryStatus;
use Illuminate\Database\Seeder;

class MilitaryStatusSeeder extends Seeder
{
    public function run(): void
    {
        $militaryStatuses = [
            ['name' => 'تم الأداء'],
            ['name' => 'أعفاء'],
            ['name' => 'مؤجل'],
            ['name' => 'خدمة بديلة'],
            ['name' => 'غير محدد'],
        ];

        foreach ($militaryStatuses as $status) {
            MilitaryStatus::updateOrCreate(
                ['name' => $status['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Military Statuses seeded: ' . count($militaryStatuses) . ' records');
    }
}
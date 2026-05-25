<?php

namespace Database\Seeders;

use App\Models\Bonus;
use Illuminate\Database\Seeder;

class BonusSeeder extends Seeder
{
    public function run(): void
    {
        $bonuses = [
            ['name' => 'حافز إنتاج'],
            ['name' => 'حافز حضور'],
            ['name' => 'حافز تميز'],
            ['name' => 'حافز إشراف'],
            ['name' => 'حافز إبداع'],
            ['name' => 'حافز عمل إضافي'],
            ['name' => 'حافز نوبتجيات'],
            ['name' => 'حافز تكليف'],
            ['name' => 'حافز نهاية سنة'],
            ['name' => 'حافز عيد'],
            ['name' => 'حافز مناسبة'],
            ['name' => 'حافز اقتراح متميز'],
            ['name' => 'حافز تدريب'],
            ['name' => 'حافز خبرة'],
            ['name' => 'حافز جودة'],
            ['name' => 'حافز إجادة'],
            ['name' => 'مكافأة'],
            ['name' => 'عمولة'],
            ['name' => 'نسبة أرباح'],
        ];

        foreach ($bonuses as $bonus) {
            Bonus::updateOrCreate(
                ['name' => $bonus['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Bonuses (Incentives) seeded: ' . count($bonuses) . ' records');
    }
}

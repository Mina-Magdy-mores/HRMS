<?php

namespace Database\Seeders;

use App\Models\Resignation;
use Illuminate\Database\Seeder;

class ResignationSeeder extends Seeder
{
    public function run(): void
    {
        $resignations = [
            ['name' => 'استقالة طوعية'],
            ['name' => 'استقالة إجبارية'],
            ['name' => 'استقالة لظروف صحية'],
            ['name' => 'استقالة لظروف أسرية'],
            ['name' => 'استقالة لظروف سفر'],
            ['name' => 'استقالة للتحاق بعمل آخر'],
            ['name' => 'استقالة نهائية'],
            ['name' => 'استقالة مؤقتة'],
            ['name' => 'استقالة لبلوغ سن التقاعد'],
            ['name' => 'استقالة لعدم الرضا عن العمل'],
        ];

        foreach ($resignations as $resignation) {
            Resignation::updateOrCreate(
                ['name' => $resignation['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Resignations seeded: ' . count($resignations) . ' records');
    }
}

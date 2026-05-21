<?php

namespace Database\Seeders;

use App\Models\DrivingLicenseType;
use Illuminate\Database\Seeder;

class DrivingLicenseTypeSeeder extends Seeder
{
    public function run(): void
    {
        $drivingLicenseTypes = [
            ['name' => 'دراجة نارية'],
            ['name' => 'سيارة خاصة'],
            ['name' => 'سيارة أجرة'],
            ['name' => 'نقل خفيف'],
            ['name' => 'نقل ثقيل'],
            ['name' => 'جرار زراعي'],
            ['name' => 'معدات ثقيلة'],
            ['name' => 'أتوبيس'],
            ['name' => 'توك توك'],
            ['name' => 'ميكروباص'],
            ['name' => 'بدون رخصة'],
        ];

        foreach ($drivingLicenseTypes as $type) {
            DrivingLicenseType::updateOrCreate(
                ['name' => $type['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Driving License Types seeded: ' . count($drivingLicenseTypes) . ' records');
    }
}

<?php

namespace Database\Seeders;

use App\Models\JobsCategory;
use Illuminate\Database\Seeder;

class JobsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'الاطباء', 'status' => 1],
            ['name' => 'التمريض', 'status' => 1],
            ['name' => 'الهيئة المعاونة', 'status' => 1],
            ['name' => 'الصيادلة', 'status' => 1],
            ['name' => 'الفنيين', 'status' => 1],
            ['name' => 'الاداريين', 'status' => 1],
            ['name' => 'الخدمات المعاونة', 'status' => 1],
            ['name' => 'الامن', 'status' => 1],
            ['name' => 'الاستقبال', 'status' => 1],
            ['name' => 'المحاسبين', 'status' => 1],
            ['name' => 'التسويق', 'status' => 1],
            ['name' => 'الموارد البشرية', 'status' => 1],
            ['name' => 'التكنولوجيا', 'status' => 1],
            ['name' => 'الصيانة', 'status' => 1],
            ['name' => 'المشتريات', 'status' => 1],
        ];

        foreach ($categories as $category) {
            JobsCategory::updateOrCreate(
                ['name' => $category['name'], 'company_id' => 1],
                [
                    'status' => $category['status'],
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Jobs Categories seeded: ' . count($categories) . ' records');
    }
}

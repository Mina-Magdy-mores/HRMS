<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    public function run(): void
    {
        $religions = [
            ['name' => 'الإسلام'],
            ['name' => 'المسيحية'],
            ['name' => 'اليهودية'],
            ['name' => 'البوذية'],
            ['name' => 'الهندوسية'],
            ['name' => 'السيخية'],
            ['name' => 'الملحد'],
            ['name' => 'أخرى'],
        ];

        foreach ($religions as $religion) {
            Religion::updateOrCreate(
                ['name' => $religion['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Religions seeded: ' . count($religions) . ' records');
    }
}

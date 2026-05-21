<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            ['name' => 'العربية'],
            ['name' => 'الإنجليزية'],
            ['name' => 'الفرنسية'],
            ['name' => 'الألمانية'],
            ['name' => 'الإسبانية'],
            ['name' => 'الإيطالية'],
            ['name' => 'الروسية'],
            ['name' => 'الصينية'],
            ['name' => 'اليابانية'],
            ['name' => 'التركية'],
            ['name' => 'الفارسية'],
            ['name' => 'الأردية'],
            ['name' => 'السواحيلية'],
            ['name' => 'الهندية'],
            ['name' => 'اليونانية'],
            ['name' => 'الهولندية'],
            ['name' => 'البرتغالية'],
            ['name' => 'البولندية'],
            ['name' => 'السويدية'],
            ['name' => 'النرويجية'],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['name' => $language['name'], 'company_id' => 1],
                [
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Languages seeded: ' . count($languages) . ' records');
    }
}

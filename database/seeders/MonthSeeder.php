<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    public function run(): void
    {
        $months = [
            ['name' => 'يناير', 'name_en' => 'January'],
            ['name' => 'فبراير', 'name_en' => 'February'],
            ['name' => 'مارس', 'name_en' => 'March'],
            ['name' => 'أبريل', 'name_en' => 'April'],
            ['name' => 'مايو', 'name_en' => 'May'],
            ['name' => 'يونيو', 'name_en' => 'June'],
            ['name' => 'يوليو', 'name_en' => 'July'],
            ['name' => 'أغسطس', 'name_en' => 'August'],
            ['name' => 'سبتمبر', 'name_en' => 'September'],
            ['name' => 'أكتوبر', 'name_en' => 'October'],
            ['name' => 'نوفمبر', 'name_en' => 'November'],
            ['name' => 'ديسمبر', 'name_en' => 'December'],
        ];

        foreach ($months as $month) {
            Month::updateOrCreate(
                ['name' => $month['name']],
                [
                    'name_en' => $month['name_en'],
                    'status' => 1,
                ]
            );
        }

        $this->command->info('✅ Months seeded: ' . count($months) . ' records');
    }
}

<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Seeder;

class WeekDaySeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            ['name' => 'السبت', 'name_en' => 'Saturday'],
            ['name' => 'الأحد', 'name_en' => 'Sunday'],
            ['name' => 'الاثنين', 'name_en' => 'Monday'],
            ['name' => 'الثلاثاء', 'name_en' => 'Tuesday'],
            ['name' => 'الأربعاء', 'name_en' => 'Wednesday'],
            ['name' => 'الخميس', 'name_en' => 'Thursday'],
            ['name' => 'الجمعة', 'name_en' => 'Friday'],
        ];

        foreach ($days as $day) {
            WeekDay::updateOrCreate(
                ['name' => $day['name']],
                [
                    'name_en' => $day['name_en'],
                    'status' => 1,
                ]
            );
        }

        $this->command->info('✅ WeekDays seeded: ' . count($days) . ' records');
    }
}

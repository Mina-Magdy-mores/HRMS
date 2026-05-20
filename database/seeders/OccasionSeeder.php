<?php

namespace Database\Seeders;

use App\Models\Occasion;
use Illuminate\Database\Seeder;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        $occasions = [
            [
                'name' => 'رأس السنة الميلادية',
                'from_date' => '2024-01-01',
                'to_date' => '2024-01-01',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد الميلاد المجيد (الأقباط)',
                'from_date' => '2024-01-07',
                'to_date' => '2024-01-07',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد الشرطة وثورة 25 يناير',
                'from_date' => '2024-01-25',
                'to_date' => '2024-01-25',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد الفطر المبارك',
                'from_date' => '2024-04-10',
                'to_date' => '2024-04-12',
                'days_count' => 3.00,
            ],
            [
                'name' => 'شم النسيم',
                'from_date' => '2024-04-20',
                'to_date' => '2024-04-20',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد العمال',
                'from_date' => '2024-05-01',
                'to_date' => '2024-05-01',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد الأضحى المبارك',
                'from_date' => '2024-06-16',
                'to_date' => '2024-06-19',
                'days_count' => 4.00,
            ],
            [
                'name' => 'ثورة 30 يونيو',
                'from_date' => '2024-06-30',
                'to_date' => '2024-06-30',
                'days_count' => 1.00,
            ],
            [
                'name' => 'رأس السنة الهجرية',
                'from_date' => '2024-07-07',
                'to_date' => '2024-07-07',
                'days_count' => 1.00,
            ],
            [
                'name' => 'ثورة 23 يوليو',
                'from_date' => '2024-07-23',
                'to_date' => '2024-07-23',
                'days_count' => 1.00,
            ],
            [
                'name' => 'المولد النبوي الشريف',
                'from_date' => '2024-09-15',
                'to_date' => '2024-09-15',
                'days_count' => 1.00,
            ],
            [
                'name' => 'عيد القوات المسلحة (نصر أكتوبر)',
                'from_date' => '2024-10-06',
                'to_date' => '2024-10-06',
                'days_count' => 1.00,
            ],
        ];

        foreach ($occasions as $occasion) {
            Occasion::updateOrCreate(
                [
                    'name' => $occasion['name'],
                    'from_date' => $occasion['from_date'],
                    'company_id' => 1
                ],
                [
                    'to_date' => $occasion['to_date'],
                    'days_count' => $occasion['days_count'],
                    'status' => 1,
                    'company_id' => 1,
                    'added_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Official Egyptian Occasions seeded: ' . count($occasions) . ' records');
    }
}

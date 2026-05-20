<?php

namespace Database\Seeders;

use App\Models\ShiftsType;
use Illuminate\Database\Seeder;

class ShiftsTypeSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'type' => 1,
                'type_name' => 'Shift Day',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 1,
                'type_name' => 'Shift Day Early',
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'total_hours' => 7.00,
            ],
            [
                'type' => 1,
                'type_name' => 'Shift Day Late',
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 2,
                'type_name' => 'Shift Night',
                'start_time' => '21:00:00',
                'end_time' => '05:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 2,
                'type_name' => 'Shift Night Early',
                'start_time' => '20:00:00',
                'end_time' => '04:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 2,
                'type_name' => 'Shift Night Late',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 1,
                'type_name' => 'Emergency Shift',
                'start_time' => '00:00:00',
                'end_time' => '12:00:00',
                'total_hours' => 12.00,
            ],
            [
                'type' => 2,
                'type_name' => 'Emergency Night',
                'start_time' => '12:00:00',
                'end_time' => '00:00:00',
                'total_hours' => 12.00,
            ],
            [
                'type' => 1,
                'type_name' => 'Morning Shift',
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'total_hours' => 8.00,
            ],
            [
                'type' => 2,
                'type_name' => 'Evening Shift',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'total_hours' => 8.00,
            ],
        ];

        foreach ($shifts as $shift) {
            ShiftsType::updateOrCreate(
                [
                    'start_time' => $shift['start_time'],
                    'end_time' => $shift['end_time'],
                    'company_id' => 1
                ],
                [
                    'type' => $shift['type'],
                    'total_hours' => $shift['total_hours'],
                    'status' => 1,
                    'company_id' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
        }

        $this->command->info('✅ Shifts Types seeded: ' . count($shifts) . ' records');
    }
}

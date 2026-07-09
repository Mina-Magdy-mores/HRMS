<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeRequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'طلب إجازة اعتيادية', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طلب إجازة عارضة', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طلب إجازة مرضية', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طلب سلفة مالية', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طلب تعديل بصمة', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'طلب استقالة', 'is_active' => 1, 'company_id' => 1, 'added_by' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($types as $type) {
            DB::table('employee_request_types')->updateOrInsert(
                ['name' => $type['name'], 'company_id' => $type['company_id']],
                $type
            );
        }
    }
}

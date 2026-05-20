<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'مدير النظام',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'status' => 1,
                'date' => date('Y-m-d H:i:s'),
                'company_id' => 1,
                'added_by' => 1,
                'updated_by' => 1,
            ]
        );

        $this->command->info('✅ Admin seeded successfully!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Admin_panel_setting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Admin::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('admin'), // password
            'status' => 1,
            'date' => date('Y-m-d H:i:s'),
            'company_id' => 1,
            'added_by' => 1,
            'updated_by' => 1
        ]);
        Admin_panel_setting::factory()->create([
            'company_name' => 'company_name',
            'status' => 1,
            'image' => 'image',
            'phone' => 'phone',
            'address' => 'address',
            'email' => 'email',
            'created_by' => 1,
            'updated_by' => 1,
            'company_id' => 1,
            'after_minute_calculate_delay' => 1,
            'after_minute_calculate_early_departure' => 1,
            'after_minute_quarter_day_cut' => 1,
            'after_days_half_day_cut' => 1,
            'after_days_allday_day_cut' => 1,
            'monthly_vacation_balance' => 1,
            'after_days_begin_vacation' => 1,
            'first_balance_begin_vacation' => 1,
            'sanctions_value_first_absence' => 1,
            'sanctions_value_second_absence' => 1,
            'sanctions_value_third_absence' => 1,
            'sanctions_value_fourth_absence' => 1
        ]);
    }
}

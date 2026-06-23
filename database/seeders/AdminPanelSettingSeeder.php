<?php

namespace Database\Seeders;

use App\Models\AdminPanelSetting;
use Illuminate\Database\Seeder;

class AdminPanelSettingSeeder extends Seeder
{
    public function run(): void
    {
        AdminPanelSetting::updateOrCreate(
            ['company_name' => 'مستشفى النور التخصصى', 'company_id' => 1],
            [
                'phone' => '0223456789',
                'address' => 'شارع النيل، مدينة نصر، القاهرة',
                'email' => 'admin@elnourhospital.com',
                'image' => 'settings/elnour_logo.png',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'company_id' => 1,
                'after_minute_calculate_delay' => 15.00,
                'after_minute_calculate_early_departure' => 15.00,
                'after_minute_quarter_day_cut' => 30.00,
                'after_days_half_day_cut' => 3.00,
                'after_days_allday_day_cut' => 6.00,
                'monthly_vacation_balance' => 2.50,
                'after_days_begin_vacation' => 30.00,
                'first_balance_begin_vacation' => 0.00,
                'sanctions_value_first_absence' => 1.00,
                'sanctions_value_second_absence' => 2.00,
                'sanctions_value_third_absence' => 3.00,
                'sanctions_value_fourth_absence' => 5.00,
                'after_mins_neglect' => 0,
                'after_shift_max_extra_hours' => 0,
            ]
        );

        $this->command->info('✅ Admin Panel Settings seeded: 1 record');
    }
}

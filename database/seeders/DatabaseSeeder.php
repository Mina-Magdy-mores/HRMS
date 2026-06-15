<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        $this->call([
                // 1. الأول المدير (عشان added_by يبقى موجود)
            AdminSeeder::class,

                // 2. البيانات الأساسية (اللي عنده foreign key للمدير)
            BloodGroupSeeder::class,
            MilitaryStatusSeeder::class,
            NationalitySeeder::class,
            ReligionSeeder::class,
            CountrySeeder::class,
            GovernorateSeeder::class,
            CitySeeder::class,
            DrivingLicenseTypeSeeder::class,
            LanguageSeeder::class,

                // 3. باقي البيانات
            AdminPanelSettingSeeder::class,
            BranchSeeder::class,
            DepartmentSeeder::class,
            JobsCategorySeeder::class,
            QualificationSeeder::class,
            ShiftsTypeSeeder::class,
            FinanceCalendarSeeder::class,
            MonthSeeder::class,
            WeekDaySeeder::class,
            FinanceMonthlyCalendarSeeder::class,
            OccasionSeeder::class,
            ResignationSeeder::class,

                //4. بيانات الموظفين
            EmployeeSeeder::class,

            AllowanceTypeSeeder::class,
            DeductionTypeSeeder::class,
            BonusSeeder::class,


        ]);

        $this->command->info('✅ All seeders completed successfully!');
    }
}

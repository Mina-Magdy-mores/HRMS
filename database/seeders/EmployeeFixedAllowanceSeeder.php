<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\AllowanceType;
use App\Models\EmployeeFixedAllowance;
use Illuminate\Database\Seeder;

class EmployeeFixedAllowanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::where('company_id', 1)->get();
        $allowanceTypes = AllowanceType::where('company_id', 1)->get();

        if ($employees->isEmpty() || $allowanceTypes->isEmpty()) {
            return;
        }

        foreach ($employees as $employee) {
            // Assign 1 or 2 fixed allowances to each employee
            $typesToAssign = $allowanceTypes->random(min(2, $allowanceTypes->count()));
            
            foreach ($typesToAssign as $type) {
                EmployeeFixedAllowance::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'allowance_type_id' => $type->id,
                        'company_id' => 1
                    ],
                    [
                        'amount' => rand(200, 1500),
                        'added_by' => 1,
                        'updated_by' => 1,
                    ]
                );
            }
        }

        $this->command->info('✅ Employee Fixed Allowances seeded successfully!');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\FinanceCalendar;
use App\Models\Month;
use App\Models\FinanceMonthlyCalendar;
use App\Models\Branche;
use App\Models\JobsCategory;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MainSalaryEmployee;
use App\Models\MainSalaryEmployeeDeduction;
use App\Models\Nationality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ArchiveMonthTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_month_archiving_rules()
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \Illuminate\Support\Facades\DB::statement('PRAGMA foreign_keys = OFF;');

        // 1. Create Admin
        $admin = Admin::create([
            'id' => 1,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'status' => 1,
            'company_id' => 1,
            'date' => '2026-06-10',
            'added_by' => 1,
            'updated_by' => 1,
        ]);

        // 2. Create Finance Calendar
        $financeCalendar = FinanceCalendar::create([
            'finance_yr' => 2026,
            'finance_yr_desc' => 'Financial Year 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'status' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 3. Create Month
        $month = Month::create([
            'name' => 'يناير',
            'name_en' => 'January',
            'status' => 1,
        ]);

        // 4. Create Finance Monthly Calendar
        $calendar = FinanceMonthlyCalendar::create([
            'financeCalendar_id' => $financeCalendar->id,
            'number_of_days' => 31,
            'year_and_month' => '2026-01',
            'finance_yr' => 2026,
            'month_id' => $month->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'status' => 1,
            'start_date_for_calculation' => '2026-01-01',
            'end_date_for_calculation' => '2026-01-31',
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 5. Create Branche, Job Category, Department
        $branch = Branche::create([
            'name' => 'Main Branch',
            'address' => 'Branch Address',
            'phone' => '1234567890',
            'status' => 1,
            'company_id' => 1,
            'created_by' => $admin->id,
        ]);

        $job = JobsCategory::create([
            'name' => 'Software Engineer',
            'status' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        $department = Department::create([
            'name' => 'Engineering',
            'number' => 'ENG-101',
            'status' => 1,
            'company_id' => 1,
            'created_by' => $admin->id,
        ]);

        $nationality = Nationality::create([
            'id' => 1,
            'name' => 'Egyptian',
            'status' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 6. Create Employees
        $employeeCreditor = Employee::create([
            'employee_code' => 1001,
            'name' => 'Creditor Employee',
            'gender' => 1,
            'nationality_id' => 1,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        $employeeDebtor = Employee::create([
            'employee_code' => 1002,
            'name' => 'Debtor Employee',
            'gender' => 1,
            'nationality_id' => 1,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        $employeeZero = Employee::create([
            'employee_code' => 1003,
            'name' => 'Zero Employee',
            'gender' => 1,
            'nationality_id' => 1,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        // 7. Create MainSalaryEmployee records
        // Creditor (employee_net_salary > 0)
        $recordCreditor = MainSalaryEmployee::create([
            'finance_monthly_calendar_id' => $calendar->id,
            'employee_id' => $employeeCreditor->id,
            'employee_name' => $employeeCreditor->name,
            'employee_status' => 1,
            'employee_job_id' => $job->id,
            'employee_branch_id' => $branch->id,
            'employee_department_id' => $department->id,
            'employee_net_salary' => 5000.00,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Debtor (employee_net_salary < 0)
        $recordDebtor = MainSalaryEmployee::create([
            'finance_monthly_calendar_id' => $calendar->id,
            'employee_id' => $employeeDebtor->id,
            'employee_name' => $employeeDebtor->name,
            'employee_status' => 1,
            'employee_job_id' => $job->id,
            'employee_branch_id' => $branch->id,
            'employee_department_id' => $department->id,
            'employee_net_salary' => -1500.00,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Zero balance (employee_net_salary == 0)
        $recordZero = MainSalaryEmployee::create([
            'finance_monthly_calendar_id' => $calendar->id,
            'employee_id' => $employeeZero->id,
            'employee_name' => $employeeZero->name,
            'employee_status' => 1,
            'employee_job_id' => $job->id,
            'employee_branch_id' => $branch->id,
            'employee_department_id' => $department->id,
            'employee_net_salary' => 0.00,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 8. Create child records for the creditor to verify cascaded archiving
        $deduction = MainSalaryEmployeeDeduction::create([
            'main_salary_employee_id' => $recordCreditor->id,
            'employee_id' => $employeeCreditor->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'deduction_type' => 1,
            'days_amount' => 1.0,
            'total' => 100.00,
            'notes' => 'Test Deduction',
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Act 1: Set one employee's salary on hold and test that bulk archiving is blocked
        $recordDebtor->payment_on_hold = 1;
        $recordDebtor->save();

        $this->actingAs($admin, 'admin');

        $response = $this->post(route('admin.main-salary-employee.archive-month'), [
            'id' => $calendar->id,
        ]);

        // Assert blocked response
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'false',
            'message' => 'عفواً، لا يمكن أرشفة وإغلاق الشهر بالكامل لوجود موظفين رواتبهم موقوفة. يرجى تفعيل رواتبهم أولاً أو معالجة حالتهم.',
        ]);

        // Act 2: Remove the hold and perform successful bulk archiving
        $recordDebtor->payment_on_hold = 0;
        $recordDebtor->save();

        $response = $this->post(route('admin.main-salary-employee.archive-month'), [
            'id' => $calendar->id,
        ]);

        // Assert successful response
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'true',
            'message' => 'تمت أرشفة وإغلاق الشهر المالي بالكامل بنجاح لكافة الموظفين',
        ]);

        // Verify Database outcomes
        $recordCreditor->refresh();
        $recordDebtor->refresh();
        $recordZero->refresh();
        $deduction->refresh();
        $calendar->refresh();

        // 1. Creditors Rule
        $this->assertEquals(1, $recordCreditor->is_archived);
        $this->assertEquals(1, $recordCreditor->archive_status_type); // 1 = Creditor
        $this->assertEquals(5000.00, $recordCreditor->archive_settlement_amount);
        $this->assertEquals(0.00, $recordCreditor->employee_net_salary_after_close_for_roll_over);
        $this->assertEquals(1, $recordCreditor->is_disbursed);

        // 2. Debtors Rule
        $this->assertEquals(1, $recordDebtor->is_archived);
        $this->assertEquals(2, $recordDebtor->archive_status_type); // 2 = Debtor
        $this->assertEquals(0.00, $recordDebtor->archive_settlement_amount);
        $this->assertEquals(-1500.00, $recordDebtor->employee_net_salary_after_close_for_roll_over);
        $this->assertEquals(0, $recordDebtor->is_disbursed);

        // 3. Zero Net Balance Rule
        $this->assertEquals(1, $recordZero->is_archived);
        $this->assertEquals(3, $recordZero->archive_status_type); // 3 = Net (Zero)
        $this->assertEquals(0.00, $recordZero->archive_settlement_amount);
        $this->assertEquals(0.00, $recordZero->employee_net_salary_after_close_for_roll_over);
        $this->assertEquals(0, $recordZero->is_disbursed);

        // 4. Child Records Archiving Rule
        $this->assertEquals(1, $deduction->is_archived);
        $this->assertNotNull($deduction->archived_at);
        $this->assertEquals($admin->id, $deduction->archived_by);

        // 5. Calendar Archiving Rule
        $this->assertEquals(2, $calendar->status); // 2 = Closed & Archived
    }
}

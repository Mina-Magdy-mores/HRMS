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

    public function test_parent_loan_auto_archiving_when_loading_index()
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

        // Create Branch, Job, Department, Nationality
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

        // 2. Create Employee
        $employee = Employee::create([
            'id' => 1,
            'employee_code' => 1001,
            'name' => 'Test Employee',
            'gender' => 1,
            'nationality_id' => $nationality->id,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        // 3. Create parent loan
        $loan = \App\Models\MainSalaryEmployeePLoan::create([
            'employee_id' => $employee->id,
            'employee_basic_salary' => 5000,
            'amount' => 2000,
            'number_of_installment_months' => 2,
            'installment_amount_monthly' => 1000,
            'next_installment_date' => '2026-06-01',
            'next_installment_year_and_month' => '2026-06',
            'paid_amount' => 0,
            'remaining_amount' => 2000,
            'is_disbursed' => 1,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 4. Create 2 installments, both paid and archived
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 2000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-06',
            'installment_status' => '1', // paid
            'is_archived' => 1, // archived
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 2000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-07',
            'installment_status' => '1', // paid
            'is_archived' => 1, // archived
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Act: log in as admin and hit index
        $this->actingAs($admin, 'admin');

        $response = $this->get(route('admin.main-salary-employee-ploans.index'));

        $response->assertStatus(200);

        // Assert parent loan is now archived in db
        $loan->refresh();
        $this->assertEquals(1, $loan->is_archived);
        $this->assertEquals($admin->id, $loan->archived_by);
        $this->assertNotNull($loan->archived_at);
        $this->assertEquals(2000, $loan->paid_amount);
        $this->assertEquals(0, $loan->remaining_amount);
    }

    public function test_pay_installment_cash_action()
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

        // Create Branch, Job, Department, Nationality
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

        // 2. Create Employee
        $employee = Employee::create([
            'id' => 1,
            'employee_code' => 1001,
            'name' => 'Test Employee',
            'gender' => 1,
            'nationality_id' => $nationality->id,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        // 3. Create parent loan
        $loan = \App\Models\MainSalaryEmployeePLoan::create([
            'employee_id' => $employee->id,
            'employee_basic_salary' => 5000,
            'amount' => 2000,
            'number_of_installment_months' => 2,
            'installment_amount_monthly' => 1000,
            'next_installment_date' => '2026-06-01',
            'next_installment_year_and_month' => '2026-06',
            'paid_amount' => 0,
            'remaining_amount' => 2000,
            'is_disbursed' => 1,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // 4. Create 2 installments, both pending
        $inst1 = \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 2000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-06',
            'installment_status' => '0', // pending
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        $inst2 = \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 2000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-07',
            'installment_status' => '0', // pending
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Act: log in as admin and post to cash payment route
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.main-salary-employee-ploans.pay-installment-cash'), [
            'id' => $inst1->id,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);

        // Assert updates
        $inst1->refresh();
        $inst2->refresh();
        $loan->refresh();

        $this->assertEquals('2', $inst1->installment_status); // Paid cash
        $this->assertEquals('0', $inst2->installment_status); // Still pending
        $this->assertEquals(1000, $loan->paid_amount);
        $this->assertEquals(1000, $loan->remaining_amount);
    }

    public function test_reschedule_loan_installments_action()
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

        // Create Branch, Job, Department, Nationality
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

        // 2. Create Employee
        $employee = Employee::create([
            'id' => 1,
            'employee_code' => 1001,
            'name' => 'Test Employee',
            'gender' => 1,
            'nationality_id' => $nationality->id,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        // 3. Create parent loan
        $loan = \App\Models\MainSalaryEmployeePLoan::create([
            'employee_id' => $employee->id,
            'employee_basic_salary' => 5000,
            'amount' => 3000,
            'number_of_installment_months' => 3,
            'installment_amount_monthly' => 1000,
            'next_installment_date' => '2026-06-01',
            'next_installment_year_and_month' => '2026-06',
            'paid_amount' => 1000,
            'remaining_amount' => 2000,
            'is_disbursed' => 1,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 1: paid & archived (should NOT be deleted)
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-06',
            'installment_status' => '1',
            'is_archived' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 2: pending, active (should be deleted)
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-07',
            'installment_status' => '0',
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 3: pending, active (should be deleted)
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-08',
            'installment_status' => '0',
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Act: log in as admin and post reschedule request
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.main-salary-employee-ploans.reschedule'), [
            'loan_id' => $loan->id,
            'cash_payment' => 500,
            'number_of_months' => 3,
            'start_date' => '2026-08-01'
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);

        // Assert parent loan and installments updates
        $loan->refresh();

        // 1. Total paid must be: 1000 (archived) + 500 (new cash) = 1500
        $this->assertEquals(1500, $loan->paid_amount);
        $this->assertEquals(1500, $loan->remaining_amount);

        // 2. Installments count: 1 (original archived) + 1 (cash payment installment) + 3 (new deferred installments) = 5
        $installments = \App\Models\MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->get();
        $this->assertCount(5, $installments);

        // 3. Verify the cash payment installment (should be recorded under the first available pending month = '2026-07')
        $cashInstallment = $installments->where('installment_status', '2')->first();
        $this->assertNotNull($cashInstallment);
        $this->assertEquals(500, $cashInstallment->installment_amount_monthly);
        $this->assertEquals('2026-07', $cashInstallment->next_installment_year_and_month);
        $this->assertEquals(1, $cashInstallment->is_archived);

        // 4. Verify the new deferred installments (amount should be 1500 / 3 = 500, starting from '2026-08')
        $newDeferred = $installments->where('installment_status', '0')->sortBy('id')->values();
        $this->assertCount(3, $newDeferred);
        
        $this->assertEquals(500, $newDeferred[0]->installment_amount_monthly);
        $this->assertEquals('2026-08', $newDeferred[0]->next_installment_year_and_month);

        $this->assertEquals(500, $newDeferred[1]->installment_amount_monthly);
        $this->assertEquals('2026-09', $newDeferred[1]->next_installment_year_and_month);

        $this->assertEquals(500, $newDeferred[2]->installment_amount_monthly);
        $this->assertEquals('2026-10', $newDeferred[2]->next_installment_year_and_month);
    }

    public function test_reschedule_loan_installments_optional_parameters_action()
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

        // Create Branch, Job, Department, Nationality
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

        // 2. Create Employee
        $employee = Employee::create([
            'id' => 1,
            'employee_code' => 1001,
            'name' => 'Test Employee',
            'gender' => 1,
            'nationality_id' => $nationality->id,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
        ]);

        // 3. Create parent loan
        $loan = \App\Models\MainSalaryEmployeePLoan::create([
            'employee_id' => $employee->id,
            'employee_basic_salary' => 5000,
            'amount' => 3000,
            'number_of_installment_months' => 3,
            'installment_amount_monthly' => 1000,
            'next_installment_date' => '2026-06-01',
            'next_installment_year_and_month' => '2026-06',
            'paid_amount' => 1000,
            'remaining_amount' => 2000,
            'is_disbursed' => 1,
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 1: paid & archived (should NOT be deleted)
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-06',
            'installment_status' => '1',
            'is_archived' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 2: pending, active (should be deleted, next_installment_year_and_month = '2026-07')
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-07',
            'installment_status' => '0',
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Installment 3: pending, active (should be deleted, next_installment_year_and_month = '2026-08')
        \App\Models\MainSalaryEmployeePLoanInstallment::create([
            'employee_id' => $employee->id,
            'main_salary_employee_p_loan_id' => $loan->id,
            'amount' => 3000,
            'installment_amount_monthly' => 1000,
            'next_installment_year_and_month' => '2026-08',
            'installment_status' => '0',
            'is_archived' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        // Act: log in as admin and post reschedule request with ONLY cash_payment
        // number_of_months and start_date are omitted, so:
        // - number_of_months defaults to remaining count = 2
        // - start_date defaults to first eligible installment's month = '2026-07-01'
        $this->actingAs($admin, 'admin');

        $response = $this->postJson(route('admin.main-salary-employee-ploans.reschedule'), [
            'loan_id' => $loan->id,
            'cash_payment' => 400
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);

        // Assert parent loan and installments updates
        $loan->refresh();

        // Total paid must be: 1000 (archived) + 400 (new cash) = 1400
        $this->assertEquals(1400, $loan->paid_amount);
        $this->assertEquals(1600, $loan->remaining_amount);

        // Installments count: 1 (original archived) + 1 (cash payment installment) + 2 (new deferred installments) = 4
        $installments = \App\Models\MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->get();
        $this->assertCount(4, $installments);

        // Verify the cash payment installment (should be recorded under the first available pending month = '2026-07')
        $cashInstallment = $installments->where('installment_status', '2')->first();
        $this->assertNotNull($cashInstallment);
        $this->assertEquals(400, $cashInstallment->installment_amount_monthly);
        $this->assertEquals('2026-07', $cashInstallment->next_installment_year_and_month);

        // Verify the new deferred installments (amount should be 1600 / 2 = 800)
        // And they should start from '2026-07' and '2026-08'
        $newDeferred = $installments->where('installment_status', '0')->sortBy('id')->values();
        $this->assertCount(2, $newDeferred);
        
        $this->assertEquals(800, $newDeferred[0]->installment_amount_monthly);
        $this->assertEquals('2026-07', $newDeferred[0]->next_installment_year_and_month);

        $this->assertEquals(800, $newDeferred[1]->installment_amount_monthly);
        $this->assertEquals('2026-08', $newDeferred[1]->next_installment_year_and_month);
    }

    public function test_default_arabic_notes_features()
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

        // 2. Create Finance Calendar & Month
        $financeCalendar = FinanceCalendar::create([
            'finance_yr' => 2026,
            'finance_yr_desc' => 'Financial Year 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'status' => 1,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        $month = Month::create([
            'name' => 'يناير',
            'name_en' => 'January',
            'status' => 1,
        ]);

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

        // 3. Create employee
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

        $employee = Employee::create([
            'employee_code' => 555,
            'name' => 'Employee Test',
            'gender' => 1,
            'nationality_id' => 1,
            'job_id' => $job->id,
            'department_id' => $department->id,
            'branch_id' => $branch->id,
            'company_id' => 1,
            'added_by' => $admin->id,
            'employment_status' => 1,
            'salary' => 5000,
            'payment_per_day' => 150,
        ]);

        // 4. Create MainSalaryEmployee
        $mainSalaryEmployee = MainSalaryEmployee::create([
            'finance_monthly_calendar_id' => $calendar->id,
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
            'employee_status' => 1,
            'employee_job_id' => $job->id,
            'employee_branch_id' => $branch->id,
            'employee_department_id' => $department->id,
            'employee_net_salary' => 5000.00,
            'is_archived' => 0,
            'is_disbursed' => 0,
            'company_id' => 1,
            'added_by' => $admin->id,
        ]);

        $this->actingAs($admin, 'admin');

        // Test 1: Store Permanent Loan with empty notes
        $response = $this->postJson(route('admin.main-salary-employee-ploans.store'), [
            'employee_id' => $employee->id,
            'employee_basic_salary' => 5000,
            'amount' => 3000,
            'number_of_installment_months' => 3,
            'installment_amount_monthly' => 1000,
            'next_installment_date' => '2026-06-12',
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);
        $loan = \App\Models\MainSalaryEmployeePLoan::where('employee_id', $employee->id)->first();
        $this->assertNotNull($loan);
        $this->assertEquals('تم إنشاء السلفة وجدولتها تلقائياً', $loan->notes);

        $installments = \App\Models\MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->get();
        $this->assertCount(3, $installments);
        foreach ($installments as $installment) {
            $this->assertEquals('قسط مجدول تلقائياً عند إنشاء السلفة', $installment->notes);
        }

        // Test 2: Update Permanent Loan with empty notes
        $response = $this->putJson(route('admin.main-salary-employee-ploans.update'), [
            'id' => $loan->id,
            'employee_id' => $employee->id,
            'amount' => 2000,
            'number_of_installment_months' => 2,
            'installment_amount_monthly' => 1000,
            'year_and_month_started' => '2026-07-01',
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);
        $loan->refresh();
        $this->assertEquals('تم تعديل السلفة وإعادة جولتها تلقائياً', $loan->notes);

        $installments = \App\Models\MainSalaryEmployeePLoanInstallment::where('main_salary_employee_p_loan_id', $loan->id)->get();
        $this->assertCount(2, $installments);
        foreach ($installments as $installment) {
            $this->assertEquals('قسط مجدول تلقائياً عند تعديل السلفة', $installment->notes);
        }

        // Test 3: Disburse the loan first
        $loan->update(['is_disbursed' => 1]);

        // Test 4: payInstallmentCash and check notes
        $firstInstallment = $installments->first();
        $response = $this->postJson(route('admin.main-salary-employee-ploans.pay-installment-cash'), [
            'id' => $firstInstallment->id,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'true']);
        $firstInstallment->refresh();
        $this->assertEquals('قسط مجدول تلقائياً عند تعديل السلفة (تم سداده نقداً بشكل مباشر)', $firstInstallment->notes);

        // Test 5: Temporary loan store
        $response = $this->postJson(route('admin.main-salary-employee-loans.store'), [
            'employee_id' => $employee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'amount' => 500,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $tempLoan = \App\Models\MainSalaryEmployeeLoan::where('employee_id', $employee->id)->first();
        $this->assertEquals('سلفة مؤقتة مضافة تلقائياً', $tempLoan->notes);

        // Test 6: Temporary loan update
        $response = $this->putJson(route('admin.main-salary-employee-loans.update'), [
            'id' => $tempLoan->id,
            'main_salary_employee_id' => $mainSalaryEmployee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'amount' => 600,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $tempLoan->refresh();
        $this->assertEquals('تم تعديل السلفة المؤقتة تلقائياً', $tempLoan->notes);

        // Test 7: Deduction store & update
        $response = $this->postJson(route('admin.main-salary-employee-deductions.store'), [
            'employee_id' => $employee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'deduction_type' => 1,
            'days_amount' => 1,
            'total' => 150,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $deduction = \App\Models\MainSalaryEmployeeDeduction::where('employee_id', $employee->id)->first();
        $this->assertEquals('جزاء شهري مضاف تلقائياً', $deduction->notes);

        $response = $this->putJson(route('admin.main-salary-employee-deductions.update'), [
            'id' => $deduction->id,
            'main_salary_employee_id' => $mainSalaryEmployee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'deduction_type' => 1,
            'days_amount' => 2,
            'total' => 300,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $deduction->refresh();
        $this->assertEquals('تم تعديل الجزاء الشهري تلقائياً', $deduction->notes);

        // Test 8: Addition store & update
        $response = $this->postJson(route('admin.main-salary-employee-additions.store'), [
            'employee_id' => $employee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'days_amount' => 1,
            'total' => 150,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $addition = \App\Models\MainSalaryEmployeeAddition::where('employee_id', $employee->id)->first();
        $this->assertEquals('إضافة شهري مضاف تلقائياً', $addition->notes);

        $response = $this->putJson(route('admin.main-salary-employee-additions.update'), [
            'id' => $addition->id,
            'main_salary_employee_id' => $mainSalaryEmployee->id,
            'finance_monthly_calendar_id' => $calendar->id,
            'days_amount' => 2,
            'total' => 300,
            'notes' => '',
        ], ['X-Requested-With' => 'XMLHttpRequest']);
        $response->assertStatus(200);
        $addition->refresh();
        $this->assertEquals('تم تعديل الإضافة الشهري تلقائياً', $addition->notes);
    }
}

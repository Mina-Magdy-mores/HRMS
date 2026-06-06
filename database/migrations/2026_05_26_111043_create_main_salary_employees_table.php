<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('main_salary_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_monthly_calendar_id')->constrained('finance_monthly_calendars')->onUpdate('cascade')->comment('ID of the finance monthly calendar');
            $table->foreignId('employee_id')->constrained('employees')->onUpdate('cascade')->comment('ID of the employee');
            $table->string('employee_name', 255)->comment('Name of the employee');
            $table->tinyInteger('sensitive')->nullable()->default(0)->comment('Indicates if the employee is sensitive');
            $table->tinyInteger('employee_status')->comment('Status of the employee');

            $table->foreignId('employee_job_id')->constrained('jobs_categories')->onUpdate('cascade')->nullable()->comment('ID of the employee job');
            $table->foreignId('employee_branch_id')->constrained('branches')->onUpdate('cascade')->comment('ID of the branch');
            $table->foreignId('employee_department_id')->constrained('departments')->onUpdate('cascade')->nullable()->comment('ID of the department');
            
            $table->decimal('employee_total_bonus', 15, 2)->nullable()->default(0)->comment('Total bonus of the employee');
            $table->decimal('employee_total_overtime_days_counter', 15, 2)->nullable()->default(0)->comment('Total overtime of the employee');
            $table->decimal('employee_total_overtime_payment_per_day', 15, 2)->nullable()->default(0)->comment('Overtime payment per day of the employee');
            $table->decimal('monthly_loan_amount', 15, 2)->nullable()->default(0)->comment('Amount of the monthly loan');
            $table->decimal('permanent_loan_amount', 15, 2)->nullable()->default(0)->comment('Amount of the permanent loan');
            $table->decimal('total_phone_payments', 15, 2)->nullable()->default(0)->comment('Total phone payments');
            $table->decimal('medical_insurance_amount', 15, 2)->nullable()->default(0)->comment('Amount of the medical insurance');
            $table->decimal('social_insurance_amount', 15, 2)->nullable()->default(0)->comment('Amount of the social insurance');
            
            $table->decimal('fixed_allowance', 15, 2)->nullable()->default(0)->comment('Fixed allowance for the employee');
            $table->decimal('variable_allowance', 15, 2)->nullable()->default(0)->comment('Variable allowance for the employee');
            $table->decimal('total_benefits', 15, 2)->nullable()->default(0)->comment('Total benefits');
            $table->decimal('employee_total_penalty_days', 15, 2)->nullable()->default(0)->comment('Number of penalty days of the employee');
            $table->decimal('employee_salary', 15, 2)->nullable()->default(0)->comment('Salary of the employee');
            $table->decimal('employee_rollover_amount', 15, 2)->nullable()->default(0)->comment('Rollover amount for the employee');
            $table->decimal('employee_last_month_salary', 15, 2)->nullable()->default(0)->comment('Last month salary of the employee');
            $table->decimal('employee_net_salary', 15, 2)->nullable()->default(0)->comment('Net salary of the employee');
            $table->decimal('employee_per_day_salary', 15, 2)->nullable()->default(0)->comment('Per day salary of the employee');
            $table->string('year_and_month', 8)->nullable()->default('')->comment('Year and month for the salary record');
            $table->integer('financial_year')->nullable()->default(0)->comment('Financial year for the salary record');
            $table->tinyInteger('payment_method')->nullable()->default(0)->comment('Payment method for the salary record');
            $table->tinyInteger('payment_on_hold')->nullable()->default(0)->comment('Status of the payment on hold');
            $table->foreignId('archived_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->tinyInteger('is_archived')->nullable()->default(0)->comment('Status of the archived employee');
            $table->dateTime('archived_at')->nullable()->comment('Date when the employee was archived');
            $table->integer('company_id');
            $table->foreignId('added_by')->constrained('admins')->cascadeOnUpdate();
            $table->foreignId('updated_by')->nullable()->constrained('admins')->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_salary_employees');
    }
};
